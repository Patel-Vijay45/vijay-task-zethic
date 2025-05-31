<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function all(array $conditions = [], array $fields = [])
    {
        $fields = !empty($fields) ? $fields : "*";

        $categories = Category::select($fields);


        if (isset($conditions['name']) && !empty($conditions['name'])) {
            $categories->where('name', 'like', '%' .  trim($conditions['name']) . '%');
        }

        return $categories
            ->orderBy('id', 'desc')
            ->paginate($conditions['per_page'] ?? config('constants.per_page'));
    }

    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
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
