<?php

namespace Tests\Unit;

use App\Support\XlsxSheetReader;
use Tests\TestCase;
use ZipArchive;

class XlsxSheetReaderTest extends TestCase
{
    public function test_it_parses_rows_keyed_by_header_labels(): void
    {
        $xlsx = $this->buildXlsx([
            1 => ['A' => 'Hari', 'B' => 'Nama'],
            2 => ['A' => 'Senin', 'B' => 'Budi'],
            3 => ['A' => 'Selasa', 'B' => 'Ani'],
        ]);

        $rows = (new XlsxSheetReader)->read($xlsx, sheetNumber: 1, headerRow: 1);

        $this->assertCount(2, $rows);
        $this->assertSame(['Hari' => 'Senin', 'Nama' => 'Budi'], $rows[0]);
        $this->assertSame(['Hari' => 'Selasa', 'Nama' => 'Ani'], $rows[1]);
    }

    public function test_it_respects_a_non_first_header_row_and_skips_blank_rows(): void
    {
        $xlsx = $this->buildXlsx([
            4 => ['A' => 'Hari', 'B' => 'Nama'],
            5 => ['A' => '', 'B' => ''],
            6 => ['A' => 'Rabu', 'B' => 'Cici'],
        ]);

        $rows = (new XlsxSheetReader)->read($xlsx, sheetNumber: 1, headerRow: 4);

        $this->assertCount(1, $rows);
        $this->assertSame(['Hari' => 'Rabu', 'Nama' => 'Cici'], $rows[0]);
    }

    /**
     * Bangun XLSX minimal (memakai inlineStr) untuk pengujian parser.
     *
     * @param  array<int, array<string, string>>  $rows  nomor baris => [kolom => nilai]
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
