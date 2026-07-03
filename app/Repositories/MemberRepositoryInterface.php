<?php

namespace App\Repositories;

interface MemberRepositoryInterface
{
    public function all();
    public function paginate(int $perPage = 15);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
