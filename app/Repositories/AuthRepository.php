<?php

namespace App\Repositories;

use App\Models\Auth;

class AuthRepository
{
    public function all()
    {
        return Auth::all();
    }

    public function find($id)
    {
        return Auth::findOrFail($id);
    }

    public function create(array $data)
    {
        return Auth::create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }
}
