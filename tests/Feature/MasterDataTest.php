<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookType;
use App\Models\Classroom;
use App\Models\Publisher;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_access_master_data(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $this->actingAs($guru)->get('/school-years')->assertForbidden();
    }

    public function test_admin_can_create_and_update_school_year(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/school-years', ['nama' => '2026/2027', 'semester' => '1', 'mulai' => '2026-07-01', 'selesai' => '2026-12-31', 'is_aktif' => true])->assertRedirect(route('school-years.index'));
        $schoolYear = SchoolYear::query()->where('nama', '2026/2027')->firstOrFail();
        $this->assertTrue($schoolYear->is_aktif);

        $this->actingAs($admin)->patch(route('school-years.update', $schoolYear), ['nama' => '2026/2027', 'semester' => '1', 'mulai' => '2026-07-01', 'selesai' => '2027-06-30', 'is_aktif' => false])->assertRedirect(route('school-years.index'));
        $updated = $schoolYear->fresh();
        $this->assertSame('2027-06-30', $updated?->selesai?->toDateString());
        $this->assertFalse($updated?->is_aktif);
    }

    public function test_only_one_school_year_can_be_active(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $activeYear = SchoolYear::factory()->create(['is_aktif' => true]);

        $this->actingAs($admin)->post('/school-years', ['nama' => '2027/2028', 'semester' => '1', 'mulai' => '2027-07-01', 'selesai' => '2027-12-31', 'is_aktif' => true])->assertRedirect(route('school-years.index'));

        $this->assertFalse($activeYear->fresh()->is_aktif);
        $this->assertTrue(SchoolYear::query()->where('nama', '2027/2028')->firstOrFail()->is_aktif);
    }

    public function test_admin_can_open_school_year_create_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('school-years.create'))->assertOk();
    }

    public function test_admin_can_create_classroom_and_deletion_is_protected_when_used(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $schoolYear = SchoolYear::factory()->create();

        $this->actingAs($admin)->post('/classrooms', ['nama' => 'VII A', 'tingkat' => 'VII', 'school_year_id' => $schoolYear->id, 'wali_nama' => 'Budi'])->assertRedirect(route('classrooms.index'));
        $classroom = Classroom::query()->where('nama', 'VII A')->firstOrFail();
        $this->actingAs($admin)->delete(route('school-years.destroy', $schoolYear))->assertSessionHas('error');
        $this->assertDatabaseHas('classes', ['id' => $classroom->id]);
    }

    public function test_unique_master_data_is_validated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        BookType::factory()->create(['nama' => 'Fiksi']);
        Publisher::factory()->create(['nama' => 'Penerbit Utama']);

        $this->actingAs($admin)->post('/book-types', ['nama' => 'Fiksi'])->assertSessionHasErrors('nama');
        $this->actingAs($admin)->post('/publishers', ['nama' => 'Penerbit Utama'])->assertSessionHasErrors('nama');
    }

    public function test_book_type_is_normalized_and_can_be_filtered(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/book-types', ['nama' => '  Fiksi  ', 'kode' => ' fik '])->assertRedirect(route('book-types.index'));

        $type = BookType::query()->where('nama', 'Fiksi')->firstOrFail();
        $this->assertSame('FIK', $type->kode);
        $this->actingAs($admin)->get('/book-types?status=active')->assertOk();
        $this->actingAs($admin)->patch(route('book-types.update', $type), ['nama' => 'Fiksi', 'kode' => 'FIK', 'aktif' => false])->assertRedirect(route('book-types.index'));
        $this->assertFalse($type->fresh()->aktif);
    }

    public function test_used_book_type_cannot_be_deleted_and_inactive_type_cannot_be_assigned(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $type = BookType::factory()->create(['aktif' => false]);

        $this->actingAs($admin)->post('/buku', ['judul' => 'Buku Nonaktif', 'jenis_buku_id' => $type->id])->assertSessionHasErrors('jenis_buku_id');

        $type->update(['aktif' => true]);
        Book::factory()->create(['jenis_buku_id' => $type->id]);
        $this->actingAs($admin)->delete(route('book-types.destroy', $type))->assertSessionHas('error');
        $this->assertDatabaseHas('book_types', ['id' => $type->id]);
    }
}
