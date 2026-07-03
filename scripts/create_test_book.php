<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $book = App\Models\Book::create([
        'title' => 'Script Test Book '.time(),
        'isbn' => 'SCRIPT-'.time(),
        'publication_year' => 2026,
    ]);
    echo "CREATED:" . $book->id . PHP_EOL;
} catch (Throwable $e) {
    echo "ERROR:" . $e->getMessage() . PHP_EOL;
}
