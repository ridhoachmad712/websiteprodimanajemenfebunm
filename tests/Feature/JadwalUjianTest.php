<?php

namespace Tests\Feature;

use App\Models\Setting;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use ZipArchive;

class JadwalUjianTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->travelTo(CarbonImmutable::parse('2026-10-02 00:30:00', 'Asia/Makassar'));
    }

    public function test_it_renders_parsed_schedule_from_the_configured_sheet(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/ABC123/edit', 'jadwal');
        Setting::set('jadwal_ujian.header_row', '1', 'jadwal');

        Http::fake([
            'drive.google.com/*' => Http::response($this->buildXlsx([
                1 => ['A' => 'Hari', 'B' => 'Tanggal', 'C' => 'Nama', 'D' => 'Ujian'],
                2 => ['A' => 'Senin', 'B' => '2026-10-02', 'C' => 'Budi Santoso', 'D' => 'Skripsi'],
            ])),
        ]);

        $response = $this->get('/jadwal-ujian');

        $response->assertOk();
        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
        $response->assertSeeInOrder(['Beranda', 'Jadwal Ujian', 'Cari jadwal', 'Waktu Makassar (WITA)', 'Budi Santoso']);
        $response->assertDontSee('Daftar Jadwal Ujian');
        $response->assertSee('Jumat, 2 Okt 2026');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Skripsi');
        $response->assertDontSee('belum dapat dimuat');
    }

    public function test_it_degrades_gracefully_when_the_sheet_is_unreachable(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/FAILS/edit', 'jadwal');

        Http::fake(['drive.google.com/*' => Http::response('', 500)]);

        $response = $this->get('/jadwal-ujian');

        $response->assertOk();
        $response->assertSee('belum dapat dimuat');
    }

    public function test_it_displays_excel_serial_dates_as_readable_dates(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/DATES/edit', 'jadwal');
        Setting::set('jadwal_ujian.header_row', '1', 'jadwal');

        Http::fake([
            'drive.google.com/*' => Http::response($this->buildXlsx([
                1 => ['A' => 'Tanggal', 'B' => 'Nama', 'C' => 'NO. HANDPHONE'],
                2 => ['A' => 46315.0, 'B' => 'Budi Santoso', 'C' => '081234567890'],
            ])),
        ]);

        $this->get('/jadwal-ujian')->assertOk()
            ->assertSee('20 Okt 2026')
            ->assertDontSee('46315')
            ->assertDontSee('NO. HANDPHONE')
            ->assertDontSee('081234567890');
    }

    public function test_it_shows_only_today_and_future_schedules_nearest_first(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/UPCOMING/edit', 'jadwal');
        Setting::set('jadwal_ujian.header_row', '1', 'jadwal');

        Http::fake([
            'drive.google.com/*' => Http::response($this->buildXlsx([
                1 => ['A' => 'TANGGAL', 'B' => 'NAMA', 'C' => 'UJIAN'],
                2 => ['A' => 46298, 'B' => 'Jadwal mendatang', 'C' => 'Skripsi'],
                3 => ['A' => 46296, 'B' => 'Jadwal kemarin', 'C' => 'Proposal'],
                4 => ['A' => 46297, 'B' => 'Jadwal hari ini', 'C' => 'Skripsi'],
                5 => ['A' => '', 'B' => 'Tanpa tanggal', 'C' => 'Skripsi'],
                6 => ['A' => '04 Okt 2026', 'B' => 'Tanggal tertulis', 'C' => 'Proposal'],
            ])),
        ]);

        $this->get('/jadwal-ujian')->assertOk()
            ->assertSeeInOrder(['Jadwal hari ini', 'Jadwal mendatang', 'Tanggal tertulis'])
            ->assertDontSee('Jadwal kemarin')
            ->assertDontSee('Tanpa tanggal')
            ->assertSee('value="skripsi"', false)
            ->assertDontSee('belum dapat dimuat');
    }

    public function test_date_filter_updates_at_midnight_without_refreshing_the_sheet_cache(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/MIDNIGHT/edit', 'jadwal');
        Setting::set('jadwal_ujian.header_row', '1', 'jadwal');

        Http::fake([
            'drive.google.com/*' => Http::response($this->buildXlsx([
                1 => ['A' => 'Tanggal', 'B' => 'Nama'],
                2 => ['A' => '02/10/2026', 'B' => 'Ujian Jumat'],
            ])),
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-10-02 23:59:00', 'Asia/Makassar'));
        $this->get('/jadwal-ujian')->assertOk()->assertSee('Ujian Jumat');

        $this->travelTo(CarbonImmutable::parse('2026-10-03 00:01:00', 'Asia/Makassar'));
        $this->get('/jadwal-ujian')->assertOk()
            ->assertDontSee('Ujian Jumat')
            ->assertSee('Belum ada jadwal ujian yang akan datang.');

        Http::assertSentCount(1);
    }

    public function test_it_formats_uppercase_schedule_text_without_changing_sheet_data(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/FORMAT/edit', 'jadwal');
        Setting::set('jadwal_ujian.header_row', '1', 'jadwal');

        Http::fake([
            'drive.google.com/*' => Http::response($this->buildXlsx([
                1 => ['A' => 'Hari', 'B' => 'Tanggal', 'C' => 'Waktu', 'D' => 'Nama', 'E' => 'Ujian', 'F' => 'Pembimbing 1', 'G' => 'Penguji 1'],
                2 => ['A' => 'KAMIS', 'B' => '2026-10-08', 'C' => '09.00 WITA', 'D' => '  NUR   ASIFAH ASWA  ', 'E' => 'PROPOSAL PENELITIAN MBKM', 'F' => 'DR. BUDI SANTOSO, M.SI.', 'G' => 'Prof. M. Ikhwan, Ph.D.'],
                3 => ['A' => 'Jumat', 'B' => '2026-10-09', 'C' => '10.00 WITA', 'D' => 'Budi Santoso', 'E' => 'Ujian Hasil Penelitian', 'F' => 'Dr. Siti Aminah, M.Si.', 'G' => 'ANDI RAHMAN, S.PD., M.PD.'],
            ])),
        ]);

        $this->get('/jadwal-ujian')->assertOk()
            ->assertSee('Kamis')
            ->assertSee('Nur Asifah Aswa')
            ->assertSee('Proposal Penelitian MBKM')
            ->assertSee('Dr. Budi Santoso, M.Si.')
            ->assertSee('Prof. M. Ikhwan, Ph.D.')
            ->assertSee('Andi Rahman, S.Pd., M.Pd.')
            ->assertSee('Ujian Hasil Penelitian')
            ->assertSee('09.00 WITA')
            ->assertDontSee('NUR ASIFAH ASWA')
            ->assertDontSee('PROPOSAL PENELITIAN MBKM');

        $cacheKey = 'jadwal-ujian.'.md5('https://docs.google.com/file/d/FORMAT/edit|1|1');
        $this->assertSame('PROPOSAL PENELITIAN MBKM', Cache::get($cacheKey)[0]['Ujian']);
    }

    public function test_it_filters_past_start_times_and_sorts_today_by_time(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/TIMES/edit', 'jadwal');
        Setting::set('jadwal_ujian.header_row', '1', 'jadwal');

        Http::fake([
            'drive.google.com/*' => Http::response($this->buildXlsx([
                1 => ['A' => 'Hari', 'B' => 'Tanggal', 'C' => 'Waktu', 'D' => 'Nama', 'E' => 'Ujian'],
                2 => ['A' => 'Jumat', 'B' => '2026-10-02', 'C' => '14.00 WITA', 'D' => 'Jadwal sore', 'E' => 'Skripsi'],
                3 => ['A' => 'Jumat', 'B' => '2026-10-02', 'C' => '11.00 WITA', 'D' => 'Jadwal lewat', 'E' => 'Skripsi'],
                4 => ['A' => 'Jumat', 'B' => '2026-10-02', 'C' => '13:30 WITA', 'D' => 'Jadwal sekarang', 'E' => 'Skripsi'],
                5 => ['A' => 'Jumat', 'B' => '2026-10-02', 'C' => '', 'D' => 'Jam belum ada', 'E' => 'Skripsi'],
                6 => ['A' => 'Sabtu', 'B' => '2026-10-03', 'C' => '09.00 WITA', 'D' => 'Jadwal besok', 'E' => 'Skripsi'],
            ])),
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-10-02 13:30:00', 'Asia/Makassar'));

        $this->get('/jadwal-ujian')->assertOk()
            ->assertSee('Jumat, 2 Okt 2026')
            ->assertSeeInOrder(['Jadwal sekarang', 'Jadwal sore', 'Jam belum ada', 'Jadwal besok'])
            ->assertDontSee('Jadwal lewat')
            ->assertSee('Waktu belum diisi')
            ->assertDontSee('Jumat</span>', false);
    }

    public function test_it_marks_suspicious_dates_and_incomplete_schedule_details(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/INCOMPLETE/edit', 'jadwal');
        Setting::set('jadwal_ujian.header_row', '1', 'jadwal');

        Http::fake([
            'drive.google.com/*' => Http::response($this->buildXlsx([
                1 => ['A' => 'Tanggal', 'B' => 'Waktu', 'C' => 'Nama', 'D' => 'Ujian', 'E' => 'Pembimbing 1'],
                2 => ['A' => '2926-08-27', 'B' => '25.90 WITA', 'C' => '', 'D' => '', 'E' => 'Dr. Budi'],
            ])),
        ]);

        $this->get('/jadwal-ujian')->assertOk()
            ->assertSee('Periksa tanggal di Sheet')
            ->assertSee('Periksa waktu di Sheet')
            ->assertSee('Nama belum diisi')
            ->assertSee('Jenis ujian belum diisi')
            ->assertSee('Dr. Budi')
            ->assertSee('Moderator/Sekretaris')
            ->assertSee('Pembimbing 1')
            ->assertSee('Penguji 1')
            ->assertDontSee('<details', false);
    }

    /**
     * @param  array<int, array<string, string|float|int>>  $rows
     */
    private function buildXlsx(array $rows): string
    {
        $cells = '';
        foreach ($rows as $rowNumber => $columns) {
            $cells .= '<row r="'.$rowNumber.'">';
            foreach ($columns as $col => $value) {
                $cells .= is_numeric($value) && ! is_string($value)
                    ? '<c r="'.$col.$rowNumber.'"><v>'.$value.'</v></c>'
                    : '<c r="'.$col.$rowNumber.'" t="inlineStr"><is><t>'.htmlspecialchars($value, ENT_XML1).'</t></is></c>';
            }
            $cells .= '</row>';
        }

        $sheet = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<sheetData>'.$cells.'</sheetData></worksheet>';

        $workbook = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            .'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets></workbook>';

        $rels = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" '
            .'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" '
            .'Target="worksheets/sheet1.xml"/></Relationships>';

        $path = tempnam(sys_get_temp_dir(), 'xlsxtest_');
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::OVERWRITE);
        $zip->addFromString('xl/workbook.xml', $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $rels);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        $binary = (string) file_get_contents($path);
        @unlink($path);

        return $binary;
    }
}
