<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Программирование',
                'slug' => 'programming',
                'description' => 'Статьи о программировании',
                'children' => [
                    [
                        'name' => 'PHP',
                        'slug' => 'php',
                        'description' => 'Статьи о PHP'
                    ],
                    [
                        'name' => 'JavaScript',
                        'slug' => 'javascript',
                        'description' => 'Статьи о JavaScript',
                        'children' => [
                            [
                                'name' => 'React',
                                'slug' => 'react',
                                'description' => 'Статьи о React'
                            ],
                            [
                                'name' => 'Vue',
                                'slug' => 'vue',
                                'description' => 'Статьи о Vue'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Дизайн',
                'slug' => 'design',
                'description' => 'Статьи о дизайне'
            ],
            [
                'name' => 'Маркетинг',
                'slug' => 'marketing',
                'description' => 'Статьи о маркетинге'
            ]
        ];

        $this->createCategories($categories);
    }

    private function createCategories($categories, $parentId = null)
    {
        foreach ($categories as $category) {
            $children = $category['children'] ?? [];
            unset($category['children']);

            $newCategory = Category::create([
                ...$category,
                'parent_id' => $parentId
            ]);

            if (!empty($children)) {
                $this->createCategories($children, $newCategory->id);
            }
        }
    }
}
