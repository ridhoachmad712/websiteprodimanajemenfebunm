<?php

namespace App\Support;

use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class XlsxSheetReader
{
    /**
     * @return array<int, array<string, string>>
     */
    public function read(string $binary, int $sheetNumber = 1, int $headerRow = 1): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekstensi PHP ZipArchive belum aktif.');
        }

        $path = $this->writeTempFile($binary);

        try {
            $zip = new ZipArchive();
            if ($zip->open($path) !== true) {
                throw new RuntimeException('File jadwal tidak dapat dibuka sebagai XLSX.');
            }

            $sheetPath = $this->sheetPath($zip, $sheetNumber);
            $sheetXml = $zip->getFromName($sheetPath);
            if ($sheetXml === false) {
                throw new RuntimeException('Sheet jadwal tidak ditemukan.');
            }

            $sharedStrings = $this->sharedStrings($zip);
            $rows = $this->rows($sheetXml, $sharedStrings);
            $zip->close();

            return $this->assocRows($rows, $headerRow);
        } finally {
            @unlink($path);
        }
    }

    private function writeTempFile(string $binary): string
    {
        $dir = storage_path('framework/cache');
        if (! is_dir($dir)) {
            $dir = storage_path('app');
        }

        $path = tempnam($dir, 'jadwal_');
        if ($path === false || file_put_contents($path, $binary) === false) {
            throw new RuntimeException('File sementara jadwal tidak dapat dibuat.');
        }

        return $path;
    }

    private function sheetPath(ZipArchive $zip, int $sheetNumber): string
    {
        $workbook = $this->xml($zip->getFromName('xl/workbook.xml') ?: '');
        $rels = $this->xml($zip->getFromName('xl/_rels/workbook.xml.rels') ?: '');

        $workbook->registerXPathNamespace('m', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $workbook->registerXPathNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');

        $sheets = $workbook->xpath('//m:sheets/m:sheet') ?: [];
        $sheet = $sheets[max(0, $sheetNumber - 1)] ?? null;
        if (! $sheet instanceof SimpleXMLElement) {
            throw new RuntimeException('Nomor sheet tidak tersedia.');
        }

        $attrs = $sheet->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $relationshipId = (string) ($attrs['id'] ?? '');
        if ($relationshipId === '') {
            throw new RuntimeException('Relasi sheet tidak valid.');
        }

        foreach ($rels->Relationship ?? [] as $rel) {
            $relAttrs = $rel->attributes();
            if ((string) $relAttrs['Id'] === $relationshipId) {
                $target = ltrim((string) $relAttrs['Target'], '/');

                return str_starts_with($target, 'xl/') ? $target : 'xl/'.$target;
            }
        }

        throw new RuntimeException('File sheet tidak ditemukan di workbook.');
    }

    /**
     * @return array<int, string>
     */
    private function sharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($xml === false) {
            return [];
        }

        $shared = [];
        $root = $this->xml($xml);
        $root->registerXPathNamespace('m', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        foreach ($root->xpath('//m:si') ?: [] as $si) {
            $si->registerXPathNamespace('m', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $parts = [];
            foreach ($si->xpath('.//m:t') ?: [] as $text) {
                $parts[] = (string) $text;
            }
            $shared[] = trim(implode('', $parts));
        }

        return $shared;
    }

    /**
     * @param array<int, string> $sharedStrings
     * @return array<int, array<string, string>>
     */
    private function rows(string $sheetXml, array $sharedStrings): array
    {
        $root = $this->xml($sheetXml);
        $root->registerXPathNamespace('m', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        $rows = [];
        foreach ($root->xpath('//m:sheetData/m:row') ?: [] as $row) {
            $rowNumber = (int) $row['r'];
            $values = [];

            foreach ($row->c ?? [] as $cell) {
                $cellRef = (string) $cell['r'];
                $column = preg_replace('/\d+/', '', $cellRef) ?: '';
                $values[$column] = $this->cellValue($cell, $sharedStrings);
            }

            if ($rowNumber > 0) {
                $rows[$rowNumber] = $values;
            }
        }

        return $rows;
    }

    /**
     * @param array<int, string> $sharedStrings
     */
    private function cellValue(SimpleXMLElement $cell, array $sharedStrings): string
    {
        $type = (string) ($cell['t'] ?? '');

        if ($type === 'inlineStr') {
            $cell->registerXPathNamespace('m', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $parts = [];
            foreach ($cell->xpath('.//m:t') ?: [] as $text) {
                $parts[] = (string) $text;
            }

            return trim(implode('', $parts));
        }

        $raw = (string) ($cell->v ?? '');
        if ($type === 's') {
            return $sharedStrings[(int) $raw] ?? '';
        }

        return trim($raw);
    }

    /**
     * @param array<int, array<string, string>> $rows
     * @return array<int, array<string, string>>
     */
    private function assocRows(array $rows, int $headerRow): array
    {
        $headerCells = $rows[$headerRow] ?? [];
        $headers = [];
        foreach ($headerCells as $column => $label) {
            $label = trim($label);
            if ($label !== '') {
                $headers[$column] = $label;
            }
        }

        $data = [];
        foreach ($rows as $rowNumber => $row) {
            if ($rowNumber <= $headerRow) {
                continue;
            }

            $item = [];
            foreach ($headers as $column => $label) {
                $item[$label] = trim($row[$column] ?? '');
            }

            if (trim(implode('', $item)) !== '') {
                $data[] = $item;
            }
        }

        return $data;
    }

    private function xml(string $content): SimpleXMLElement
    {
        if ($content === '') {
            throw new RuntimeException('XML XLSX kosong atau rusak.');
        }

        $xml = simplexml_load_string($content);
        if (! $xml instanceof SimpleXMLElement) {
            throw new RuntimeException('XML XLSX tidak valid.');
        }

        return $xml;
    }
}
