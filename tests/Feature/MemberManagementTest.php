<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\Services\MemberSpreadsheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;
use ZipArchive;

class MemberManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_member_and_login_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/anggota', [
            'nomor_anggota' => 'SIPUS-00001', 'jenis_anggota' => 'siswa', 'nama' => 'Siswa Satu', 'nis_nip' => '12345',
            'tanggal_daftar' => '2026-07-01', 'username' => 'siswa.satu', 'password' => 'password123', 'password_confirmation' => 'password123', 'status' => true,
        ])->assertRedirect(route('members.index'));

        $member = Member::query()->where('nomor_anggota', 'SIPUS-00001')->firstOrFail();
        $this->assertSame('siswa.satu', $member->user?->username);
        $this->assertSame('siswa', $member->user?->role);
    }

    public function test_non_admin_cannot_manage_members(): void
    {
        $user = User::factory()->create(['role' => 'guru']);

        $this->actingAs($user)->get('/anggota')->assertForbidden();
    }

    public function test_xlsx_rows_are_parsed_and_validated_before_import(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);
        $file = $this->xlsxFile('SIPUS-00002', 'siswa', 'Siswa Dua');

        $rows = app(MemberSpreadsheetService::class)->parse($file);
        $validated = app(MemberSpreadsheetService::class)->validateRows($rows);

        $this->assertSame('Siswa Dua', $validated[0]['nama']);
        $this->assertTrue($validated[0]['_valid']);
        $this->actingAs($admin)->withHeader('X-Inertia', 'true')->post('/anggota/import/preview', ['file' => $file])->assertOk();

        $token = (string) Str::uuid();
        Storage::disk('local')->put("member-imports/{$token}.json", json_encode($rows, JSON_THROW_ON_ERROR));
        $this->actingAs($admin)->post('/anggota/import/confirm', ['token' => $token])->assertRedirect(route('members.index'));
        $this->assertDatabaseHas('members', ['nomor_anggota' => 'SIPUS-00002', 'nama' => 'Siswa Dua']);
    }

    private function xlsxFile(string $number, string $type, string $name): UploadedFile
    {
        $path = app(MemberSpreadsheetService::class)->templatePath();
        $zip = new ZipArchive;
        $zip->open($path);
        $headers = ['nomor_anggota', 'jenis_anggota', 'nama', 'nis_nip', 'kelas', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'telepon', 'email', 'tanggal_daftar', 'username', 'password'];
        $headerCells = collect($headers)->map(fn ($header, $index) => '<c r="'.chr(65 + $index).'1" t="inlineStr"><is><t>'.$header.'</t></is></c>')->implode('');
        $values = [$number, $type, $name, '', '', 'P', '', '', '', '', '', '2026-07-01', '', ''];
        $valueCells = collect($values)->map(fn ($value, $index) => '<c r="'.chr(65 + $index).'2" t="inlineStr"><is><t>'.$value.'</t></is></c>')->implode('');
        $zip->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData><row r="1">'.$headerCells.'</row><row r="2">'.$valueCells.'</row></sheetData></worksheet>');
        $zip->close();

        return new UploadedFile($path, 'anggota.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }
}
