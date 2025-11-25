<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Rings',
                'slug' => 'rings',
                'description' => 'Beautiful rings for every occasion',
                'is_active' => true,
                'children' => [
                    ['name' => 'Engagement Rings', 'slug' => 'engagement-rings', 'description' => 'Stunning engagement rings'],
                    ['name' => 'Wedding Rings', 'slug' => 'wedding-rings', 'description' => 'Perfect wedding bands'],
                    ['name' => 'Fashion Rings', 'slug' => 'fashion-rings', 'description' => 'Trendy fashion rings'],
                    ['name' => 'Statement Rings', 'slug' => 'statement-rings', 'description' => 'Bold statement pieces'],
                ]
            ],
            [
                'name' => 'Necklaces',
                'slug' => 'necklaces',
                'description' => 'Elegant necklaces and pendants',
                'is_active' => true,
                'children' => [
                    ['name' => 'Pendants', 'slug' => 'pendants', 'description' => 'Beautiful pendant necklaces'],
                    ['name' => 'Chains', 'slug' => 'chains', 'description' => 'Classic chain necklaces'],
                    ['name' => 'Chokers', 'slug' => 'chokers', 'description' => 'Stylish choker necklaces'],
                    ['name' => 'Statement Necklaces', 'slug' => 'statement-necklaces', 'description' => 'Eye-catching statement pieces'],
                ]
            ],
            [
                'name' => 'Earrings',
                'slug' => 'earrings',
                'description' => 'Stunning earrings for every style',
                'is_active' => true,
                'children' => [
                    ['name' => 'Studs', 'slug' => 'stud-earrings', 'description' => 'Classic stud earrings'],
                    ['name' => 'Hoops', 'slug' => 'hoop-earrings', 'description' => 'Elegant hoop earrings'],
                    ['name' => 'Drop Earrings', 'slug' => 'drop-earrings', 'description' => 'Beautiful drop earrings'],
                    ['name' => 'Chandelier', 'slug' => 'chandelier-earrings', 'description' => 'Glamorous chandelier earrings'],
                ]
            ],
            [
                'name' => 'Bracelets',
                'slug' => 'bracelets',
                'description' => 'Stylish bracelets and bangles',
                'is_active' => true,
                'children' => [
                    ['name' => 'Tennis Bracelets', 'slug' => 'tennis-bracelets', 'description' => 'Classic tennis bracelets'],
                    ['name' => 'Charm Bracelets', 'slug' => 'charm-bracelets', 'description' => 'Personalized charm bracelets'],
                    ['name' => 'Bangles', 'slug' => 'bangles', 'description' => 'Traditional and modern bangles'],
                    ['name' => 'Cuff Bracelets', 'slug' => 'cuff-bracelets', 'description' => 'Bold cuff bracelets'],
                ]
            ],
            [
                'name' => 'Watches',
                'slug' => 'watches',
                'description' => 'Luxury timepieces',
                'is_active' => true,
                'children' => [
                    ['name' => 'Women\'s Watches', 'slug' => 'womens-watches', 'description' => 'Elegant watches for women'],
                    ['name' => 'Men\'s Watches', 'slug' => 'mens-watches', 'description' => 'Sophisticated watches for men'],
                    ['name' => 'Smart Watches', 'slug' => 'smart-watches', 'description' => 'Modern smart timepieces'],
                ]
            ],
            [
                'name' => 'Sets',
                'slug' => 'jewelry-sets',
                'description' => 'Complete jewelry sets',
                'is_active' => true,
                'children' => [
                    ['name' => 'Bridal Sets', 'slug' => 'bridal-sets', 'description' => 'Complete bridal jewelry sets'],
                    ['name' => 'Matching Sets', 'slug' => 'matching-sets', 'description' => 'Coordinated jewelry sets'],
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $category = Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            foreach ($children as $childData) {
                $childData['parent_id'] = $category->id;
                $childData['is_active'] = true;
                
                Category::firstOrCreate(
                    ['slug' => $childData['slug']],
                    $childData
                );
            }
        }

        $this->command->info('Categories created successfully!');
    }
}