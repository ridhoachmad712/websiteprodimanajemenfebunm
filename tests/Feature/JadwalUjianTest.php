<?php

namespace Tests\Feature;

use App\Models\Setting;
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
    }

    public function test_it_renders_parsed_schedule_from_the_configured_sheet(): void
    {
        Setting::set('jadwal_ujian.file_url', 'https://docs.google.com/file/d/ABC123/edit', 'jadwal');
        Setting::set('jadwal_ujian.header_row', '1', 'jadwal');

        Http::fake([
            'drive.google.com/*' => Http::response($this->buildXlsx([
                1 => ['A' => 'Hari', 'B' => 'Nama', 'C' => 'Ujian'],
                2 => ['A' => 'Senin', 'B' => 'Budi Santoso', 'C' => 'Skripsi'],
            ])),
        ]);

        $response = $this->get('/jadwal-ujian');

        $response->assertOk();
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

    /**
     * @param  array<int, array<string, string>>  $rows
     */
    private function buildXlsx(array $rows): string
    {
        $cells = '';
        foreach ($rows as $rowNumber => $columns) {
            $cells .= '<row r="'.$rowNumber.'">';
            foreach ($columns as $col => $value) {
                $cells .= '<c r="'.$col.$rowNumber.'" t="inlineStr"><is><t>'.htmlspecialchars($value, ENT_XML1).'</t></is></c>';
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
