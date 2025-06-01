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

        if (isset($conditions['status'])) {
            $categories->where('status', $conditions['status']);
        }
        if (isset($conditions['position'])) {
            $categories->where('position', $conditions['position']);
        }
        return $categories
            ->orderBy(isset($conditions['sort_key']) ? $conditions['sort_key'] : 'id', isset($conditions['sort_dir']) ? $conditions['sort_dir'] : 'desc')
            ->paginate($conditions['per_page'] ?? config('constants.per_page'));
    }

    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        $category = Category::create($data);
        return $category;
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
