<?php

namespace App\Repository;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRepository
{
    public function getAll(): Collection
    {
        return Cache::remember('categories.all', 3600, function () {
            return Category::with('children')->get();
        });
    }

    public function getAllChildren(): Collection
    {
        return Cache::remember('categories.tree', 3600, function () {
            return Category::with('allChildren')->whereNull('parent_id')->orderBy('order')->get();
        });
    }

    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }

    public function getTree()
    {
        return $this->getAllChildren();
    }

    public function store(array $data): Category
    {
        $category = Category::create($data);
        Cache::forget('categories.all');
        Cache::forget('categories.tree');
        return $category;
    }

    public function update(Category $category, array $data): bool
    {
        $result = $category->update($data);
        Cache::forget('categories.all');
        Cache::forget('categories.tree');
        return $result;
    }

    public function delete(Category $category): bool
    {
        $result = $category->delete();
        Cache::forget('categories.all');
        Cache::forget('categories.tree');
        return $result;
    }
}
