<?php

namespace Tests\Feature;

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
}
