<?php

namespace App\Repositories\Eloquent;

use App\Models\Book;
use App\Repositories\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function all()
    {
        return Book::all();
    }

    public function paginate(int $perPage = 15)
    {
        return Book::paginate($perPage);
    }

    public function find(int $id)
    {
        return Book::find($id);
    }

    public function create(array $data)
    {
        return Book::create($data);
    }

    public function update(int $id, array $data)
    {
        $b = Book::findOrFail($id);
        $b->update($data);
        return $b;
    }

    public function delete(int $id)
    {
        $b = Book::findOrFail($id);
        return $b->delete();
    }
}
