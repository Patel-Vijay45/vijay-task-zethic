<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{

    public function __construct(private CategoryRepository $categoryRepo) {}

    public function getAllCategorys(array $conditions = [], array $fields = [])
    {
        return $this->categoryRepo->all($conditions);
    }

    public function getCategory($id)
    {
        return $this->categoryRepo->find($id);
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepo->create($data);
    }

    public function updateCategory($id, array $data)
    {
        return $this->categoryRepo->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepo->delete($id);
    }
}
