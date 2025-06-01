<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function __construct(private UserRepository $userRepo) {}

    public function getAllUsers(array $conditions = [], array $fields = [])
    {
        return $this->userRepo->all($conditions, $fields);
    }

    public function getUser($id)
    {
        return $this->userRepo->find($id);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepo->create($data);
    }

    public function updateUser($id, array $data)
    {
        return $this->userRepo->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userRepo->delete($id);
    }
}
