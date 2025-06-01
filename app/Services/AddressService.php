<?php

namespace App\Services;

use App\Repositories\AddressRepository;
use Illuminate\Support\Facades\Auth;

class AddressService
{
    public function __construct(private AddressRepository $addressRepo) {}

    public function getAllAddresss(array $conditions = [], array $fields = [])
    {
        $user = Auth::user();
        if ($user->role == 'user') {
            $conditions['user_id'] = [$user->id];
        }
        return $this->addressRepo->all($conditions);
    }

    public function getAddress($id)
    {
        return $this->addressRepo->find($id);
    }

    public function createAddress(array $data)
    {
        $data['user_id'] = Auth::user()->id;
        return $this->addressRepo->create($data);
    }

    public function updateAddress($id, array $data)
    {
        return $this->addressRepo->update($id, $data);
    }

    public function deleteAddress($id)
    {
        return $this->addressRepo->delete($id);
    }
}
