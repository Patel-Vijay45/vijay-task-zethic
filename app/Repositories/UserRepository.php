<?php

namespace App\Repositories;

use App\Models\User; 

class UserRepository
{
    public function all(array $conditions = [], array $fields = [])
    {
        $fields = !empty($fields) ? $fields : "*";

        $admins = User::select($fields);

        if (isset($conditions['is_active']) && !empty($conditions['is_active'])) {
            $admins->where('is_active', $conditions['is_active']);
        }

        if (isset($conditions['name']) && !empty($conditions['name'])) {
            $admins->where('name', 'like', '%' .  trim($conditions['name']) . '%');
        }

        if (isset($conditions['email']) && !empty($conditions['email'])) {
            $admins->where('email', 'like', '%' .  trim($conditions['email']) . '%');
        }

        if (isset($conditions['role']) && !empty($conditions['role'])) {
            $admins->where('role', 'like', '%' .  trim($conditions['role']) . '%');
        }

        return $admins
            ->orderBy('id', 'desc')
            ->paginate($conditions['per_page'] ?? config('constants.per_page'));
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
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
