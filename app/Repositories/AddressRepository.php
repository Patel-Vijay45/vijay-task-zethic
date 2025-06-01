<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository
{
    public function all(array $conditions = [], array $fields = [])
    {
        $fields = !empty($fields) ? $fields : "*";

        $address = Address::select($fields);
        if (isset($conditions['user_id'])) {
            $address->whereIn('user_id', $conditions['user_id']);
        }
        if (isset($conditions['first_name'])) {
            $address->where('first_name', 'like', '%' . trim($conditions['first_name']) . '%');
        }
        if (isset($conditions['last_name'])) {
            $address->where('last_name', 'like', '%' . trim($conditions['last_name']) . '%');
        }
        if (isset($conditions['phone_no'])) {
            $address->where('phone_no', 'like', '%' . trim($conditions['phone_no']) . '%');
        }
        if (isset($conditions['alternative_phone_no'])) {
            $address->where('alternative_phone_no', 'like', '%' . trim($conditions['alternative_phone_no']) . '%');
        }
        if (isset($conditions['address'])) {
            $address->where('address', 'like', '%' . trim($conditions['address']) . '%');
        }
        if (isset($conditions['city'])) {
            $address->where('city', 'like', '%' . trim($conditions['city']) . '%');
        }
        if (isset($conditions['state'])) {
            $address->where('state', 'like', '%' . trim($conditions['state']) . '%');
        }
        if (isset($conditions['country'])) {
            $address->where('country', 'like', '%' . trim($conditions['country']) . '%');
        }
        if (isset($conditions['pincode'])) {
            $address->where('pincode', 'like', '%' . trim($conditions['pincode']) . '%');
        }
        if (isset($conditions['country'])) {
            $address->where('country', 'like', '%' . trim($conditions['country']) . '%');
        }
        if (isset($conditions['is_default'])) {
            $address->where('is_default',  $conditions['is_default']);
        }

        return $address
            ->orderBy('id', 'desc')
            ->paginate($conditions['per_page'] ?? config('constants.per_page'));
    }

    public function find($id)
    {
        return Address::findOrFail($id);
    }

    public function create(array $data)
    {
        return Address::create($data);
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
