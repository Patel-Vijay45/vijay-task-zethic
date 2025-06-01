<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;

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
        $data['image'] = Storage::url('public/' . FileUploadHelper::uploadFile($data['image'], 'categories'));
        $data['category_banner'] = Storage::url('public/' . FileUploadHelper::uploadFile($data['category_banner'], 'categories'));
        return $this->categoryRepo->create($data);
    }

    public function updateCategory($category, array $data)
    {
        if (isset($data['image'])) {
            if ($category->image) {
                $oldPath = str_replace('/storage/', '', $category->image);
                Storage::delete('public/' . $oldPath);
            }
            $data['image'] = Storage::url('public/' . FileUploadHelper::uploadFile($data['image'], 'categories'));
        }
        if (isset($data['category_banner'])) {
            if ($category->category_banner) {
                $oldBanner = str_replace('/storage/', '', $category->category_banner);
                Storage::delete('public/' . $oldBanner);
            }
            $data['category_banner'] = Storage::url('public/' . FileUploadHelper::uploadFile($data['category_banner'], 'categories'));
        }

        return $this->categoryRepo->update($category->id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepo->delete($id);
    }
}
