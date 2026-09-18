<?php

namespace App\Services;

use App\Models\Classroom;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use ZipArchive;

class MemberSpreadsheetService
{
    private const HEADERS = ['nomor_anggota', 'jenis_anggota', 'nama', 'nis_nip', 'kelas', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'telepon', 'email', 'tanggal_daftar', 'username', 'password'];

    public function parse(UploadedFile $file): array
    {
        $zip = new ZipArchive;
        abort_unless($zip->open($file->getRealPath()) === true, 422, 'File XLSX tidak dapat dibaca.');
        $sharedStrings = $this->sharedStrings($zip);
        $sheet = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml') ?: '');
        abort_unless($sheet !== false, 422, 'Sheet pertama pada file XLSX tidak ditemukan.');
        $sheet->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $rows = [];
        foreach ($sheet->xpath('//x:sheetData/x:row') ?: [] as $row) {
            $row->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $values = [];
            foreach ($row->xpath('./x:c') ?: [] as $cell) {
                preg_match('/[A-Z]+/', (string) $cell['r'], $match);
                $column = $this->columnNumber($match[0] ?? 'A');
                $type = (string) $cell['t'];
                $value = (string) ($cell->v ?? '');
                if ($type === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                }
                if ($type === 'inlineStr') {
                    $value = (string) ($cell->is->t ?? '');
                }
                $values[$column] = trim($value);
            }
            if ($values !== []) {
                $rows[] = array_values(array_replace(array_fill(0, max(array_keys($values) + [0]) + 1, ''), $values));
            }
        }
        abort_if(count($rows) < 2, 422, 'File XLSX harus memiliki header dan minimal satu baris data.');
        $headers = array_map(fn ($header) => $this->normalizeHeader($header), $rows[0]);
        abort_if(array_diff(['nomor_anggota', 'jenis_anggota', 'nama'], $headers) !== [], 422, 'Header wajib: nomor_anggota, jenis_anggota, nama.');

        return collect(array_slice($rows, 1))->map(function (array $row) use ($headers): array {
            $data = [];
            foreach ($headers as $index => $header) {
                if ($header !== '') {
                    $data[$header] = $row[$index] ?? null;
                }
            }

            return $data;
        })->filter(fn (array $row) => collect($row)->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty())->values()->all();
    }

    public function validateRows(array $rows): array
    {
        $seenNumbers = [];

        return collect($rows)->map(function (array $row, int $index) use (&$seenNumbers): array {
            $validator = Validator::make($row, [
                'nomor_anggota' => ['required', 'string', 'max:40', Rule::unique('members', 'nomor_anggota')],
                'jenis_anggota' => ['required', Rule::in(['siswa', 'guru'])],
                'nama' => ['required', 'string', 'max:100'],
                'nis_nip' => ['nullable', 'string', 'max:50', Rule::unique('members', 'nis_nip')],
                'kelas' => ['nullable', 'string', 'max:100'],
                'jenis_kelamin' => ['nullable', 'string', 'max:20'],
                'tanggal_lahir' => ['nullable', 'date'],
                'tanggal_daftar' => ['nullable', 'date'],
                'email' => ['nullable', 'email', 'max:255'],
                'username' => ['nullable', 'string', 'min:3', 'max:50', Rule::unique('users', 'username')],
                'password' => ['nullable', 'string', 'min:8'],
            ]);
            $isValid = $validator->passes();
            if (isset($seenNumbers[$row['nomor_anggota'] ?? ''])) {
                $validator->errors()->add('nomor_anggota', 'Nomor anggota duplikat di file.');
            }
            if (($row['nomor_anggota'] ?? '') !== '') {
                $seenNumbers[$row['nomor_anggota']] = true;
            }
            if (! empty($row['kelas']) && ! Classroom::where('nama', $row['kelas'])->exists()) {
                $validator->errors()->add('kelas', 'Kelas tidak ditemukan.');
            }
            $row['_row'] = $index + 2;
            $row['_errors'] = $validator->errors()->all();
            $row['_valid'] = $isValid && $row['_errors'] === [];

            return $row;
        })->all();
    }

    public function templatePath(): string
    {
        $path = storage_path('app/private/template-anggota.xlsx');
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Anggota" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');
        $cells = collect(self::HEADERS)->map(fn ($header, $index) => '<c r="'.$this->columnName($index + 1).'1" t="inlineStr"><is><t>'.$this->xml($header).'</t></is></c>')->implode('');
        $zip->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData><row r="1">'.$cells.'</row></sheetData></worksheet>');
        $zip->close();

        return $path;
    }

    private function sharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($xml === false) {
            return [];
        }
        $document = simplexml_load_string($xml);
        $document?->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        return collect($document?->xpath('//x:si') ?: [])->map(fn ($item) => collect($item->xpath('.//x:t') ?: [])->implode(''))->all();
    }

    private function normalizeHeader(string $header): string
    {
        return (string) Str::of($header)->trim()->lower()->replace([' ', '-'], '_');
    }

    private function columnNumber(string $letters): int
    {
        $number = 0;
        foreach (str_split($letters) as $letter) {
            $number = $number * 26 + ord($letter) - 64;
        }

        return $number - 1;
    }

    private function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $name = chr(65 + $remainder).$name;
            $number = intdiv($number - 1, 26);
        }

        return $name;
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
