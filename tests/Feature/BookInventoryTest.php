<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BookType;
use App\Models\Publisher;
use App\Models\User;
use App\Services\BookSpreadsheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;
use ZipArchive;

class BookInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_book_with_authors_and_cover(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $type = BookType::factory()->create();
        $publisher = Publisher::factory()->create();
        $author = Author::factory()->create();

        $coverPath = tempnam(sys_get_temp_dir(), 'sipus-cover-');
        file_put_contents($coverPath, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='));
        $cover = new UploadedFile($coverPath, 'cover.png', 'image/png', null, true);
        $response = $this->actingAs($admin)->post('/buku', ['kode_buku' => 'BK-001', 'judul' => 'Belajar Laravel', 'jenis_buku_id' => $type->id, 'penerbit_id' => $publisher->id, 'tahun_terbit' => 2026, 'authors' => [$author->id], 'cover' => $cover]);

        $book = Book::query()->where('kode_buku', 'BK-001')->firstOrFail();
        $response->assertRedirect(route('books.show', $book));
        $this->assertSame([$author->id], $book->authors()->pluck('authors.id')->all());
        Storage::disk('public')->assertExists($book->cover_path);
    }

    public function test_admin_can_add_copy_and_inventory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create();

        $this->actingAs($admin)->post(route('book-copies.store', $book), ['book_id' => $book->id, 'kode_inventaris' => 'INV-BK-001', 'lokasi_rak' => 'Rak A-01', 'kondisi' => 'baik', 'status' => 'tersedia'])->assertRedirect(route('books.show', $book));
        $this->actingAs($admin)->post('/inventaris', ['kode_inventaris' => 'INV-001', 'nama_barang' => 'Kursi baca', 'jenis' => 'Mebel', 'jumlah' => 4, 'satuan' => 'unit', 'kondisi' => 'baik', 'status' => 'aktif'])->assertRedirect(route('inventories.index'));

        $this->assertDatabaseHas('book_copies', ['kode_inventaris' => 'INV-BK-001', 'status' => 'tersedia']);
        $this->assertDatabaseHas('inventories', ['kode_inventaris' => 'INV-001', 'jumlah' => 4]);
    }

    public function test_book_with_copy_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create();
        BookCopy::factory()->create(['book_id' => $book->id]);

        $this->actingAs($admin)->delete(route('books.destroy', $book))->assertSessionHas('error');
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_non_admin_cannot_manage_books(): void
    {
        $user = User::factory()->create(['role' => 'guru']);

        $this->actingAs($user)->get('/buku')->assertForbidden();
        $this->actingAs($user)->get('/inventaris')->assertForbidden();
    }

    public function test_books_can_be_imported_from_xlsx_using_active_book_type(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);
        $type = BookType::factory()->create(['nama' => 'Fiksi', 'kode' => 'FIK', 'aktif' => true]);
        $publisher = Publisher::factory()->create(['nama' => 'Penerbit Demo']);
        $author = Author::factory()->create(['nama' => 'Penulis Demo']);
        $file = $this->bookXlsxFile('BK-IMPORT-001', 'Buku Import', 'FIK', $publisher->nama, $author->nama);

        $service = app(BookSpreadsheetService::class);
        $rows = $service->validateRows($service->parse($file));
        $this->assertTrue($rows[0]['_valid']);
        $this->actingAs($admin)->withHeader('X-Inertia', 'true')->post(route('books.import.preview'), ['file' => $file])->assertOk();

        $token = (string) Str::uuid();
        Storage::disk('local')->put("book-imports/{$token}.json", json_encode($rows, JSON_THROW_ON_ERROR));
        $this->actingAs($admin)->post(route('books.import.confirm'), ['token' => $token])->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', ['kode_buku' => 'BK-IMPORT-001', 'jenis_buku_id' => $type->id, 'penerbit_id' => $publisher->id]);
    }

    private function bookXlsxFile(string $code, string $title, string $type, string $publisher, string $author): UploadedFile
    {
        $path = app(BookSpreadsheetService::class)->templatePath();
        $zip = new ZipArchive;
        $zip->open($path);
        $headers = ['kode_buku', 'judul', 'jenis_buku', 'penerbit', 'pengarang', 'tahun_terbit', 'deskripsi'];
        $headerCells = collect($headers)->map(fn ($header, $index) => '<c r="'.chr(65 + $index).'1" t="inlineStr"><is><t>'.$header.'</t></is></c>')->implode('');
        $values = [$code, $title, $type, $publisher, $author, '2026', 'Buku hasil import'];
        $valueCells = collect($values)->map(fn ($value, $index) => '<c r="'.chr(65 + $index).'2" t="inlineStr"><is><t>'.$value.'</t></is></c>')->implode('');
        $zip->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData><row r="1">'.$headerCells.'</row><row r="2">'.$valueCells.'</row></sheetData></worksheet>');
        $zip->close();

        return new UploadedFile($path, 'buku.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }
}
