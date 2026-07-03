<?php

namespace App\Repositories\Eloquent;

use App\Models\Member;
use App\Repositories\MemberRepositoryInterface;

class MemberRepository implements MemberRepositoryInterface
{
    public function all()
    {
        return Member::all();
    }

    public function paginate(int $perPage = 15)
    {
        return Member::paginate($perPage);
    }

    public function find(int $id)
    {
        return Member::find($id);
    }

    public function create(array $data)
    {
        return Member::create($data);
    }

    public function update(int $id, array $data)
    {
        $m = Member::findOrFail($id);
        $m->update($data);
        return $m;
    }

    public function delete(int $id)
    {
        $m = Member::findOrFail($id);
        return $m->delete();
    }
}
