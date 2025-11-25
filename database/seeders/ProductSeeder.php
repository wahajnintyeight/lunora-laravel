<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Engagement Rings
            [
                'category_slug' => 'engagement-rings',
                'name' => 'Classic Solitaire Diamond Ring',
                'sku' => 'ENG001',
                'description' => 'A timeless solitaire engagement ring featuring a brilliant cut diamond set in 18k white gold. Perfect for the classic bride who appreciates elegance and simplicity.',
                'material' => '18k White Gold',
                'brand' => 'Lunora',
                'price_pkr' => 25000000, // PKR 250,000
                'compare_at_price_pkr' => 28000000, // PKR 280,000
                'stock' => 15,
                'is_featured' => true,
                'variants' => [
                    ['options' => ['Size' => '5', 'Diamond' => '1 Carat'], 'sku' => 'ENG001-5-1CT', 'stock' => 5],
                    ['options' => ['Size' => '6', 'Diamond' => '1 Carat'], 'sku' => 'ENG001-6-1CT', 'stock' => 5],
                    ['options' => ['Size' => '7', 'Diamond' => '1 Carat'], 'sku' => 'ENG001-7-1CT', 'stock' => 5],
                ]
            ],
            [
                'category_slug' => 'engagement-rings',
                'name' => 'Vintage Halo Engagement Ring',
                'sku' => 'ENG002',
                'description' => 'An exquisite vintage-inspired halo engagement ring with intricate detailing and a center diamond surrounded by smaller diamonds.',
                'material' => '18k Rose Gold',
                'brand' => 'Lunora',
                'price_pkr' => 32000000, // PKR 320,000
                'stock' => 8,
                'is_featured' => true,
            ],

            // Wedding Rings
            [
                'category_slug' => 'wedding-rings',
                'name' => 'Classic Wedding Band',
                'sku' => 'WED001',
                'description' => 'A simple and elegant wedding band crafted in premium gold. Available in multiple widths and finishes.',
                'material' => '18k Yellow Gold',
                'brand' => 'Lunora',
                'price_pkr' => 8500000, // PKR 85,000
                'stock' => 25,
                'variants' => [
                    ['options' => ['Size' => '5', 'Width' => '2mm'], 'sku' => 'WED001-5-2MM', 'stock' => 5],
                    ['options' => ['Size' => '6', 'Width' => '2mm'], 'sku' => 'WED001-6-2MM', 'stock' => 5],
                    ['options' => ['Size' => '7', 'Width' => '2mm'], 'sku' => 'WED001-7-2MM', 'stock' => 5],
                    ['options' => ['Size' => '5', 'Width' => '4mm'], 'sku' => 'WED001-5-4MM', 'stock' => 5],
                    ['options' => ['Size' => '6', 'Width' => '4mm'], 'sku' => 'WED001-6-4MM', 'stock' => 5],
                ]
            ],

            // Necklaces
            [
                'category_slug' => 'pendants',
                'name' => 'Heart Diamond Pendant',
                'sku' => 'PEN001',
                'description' => 'A beautiful heart-shaped pendant with diamonds, perfect for expressing love and affection.',
                'material' => '18k White Gold',
                'brand' => 'Lunora',
                'price_pkr' => 15000000, // PKR 150,000
                'stock' => 12,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'chains',
                'name' => 'Gold Chain Necklace',
                'sku' => 'CHN001',
                'description' => 'A classic gold chain necklace available in different lengths. Perfect for layering or wearing alone.',
                'material' => '22k Yellow Gold',
                'brand' => 'Lunora',
                'price_pkr' => 12000000, // PKR 120,000
                'stock' => 20,
                'variants' => [
                    ['options' => ['Length' => '16 inches'], 'sku' => 'CHN001-16', 'stock' => 7],
                    ['options' => ['Length' => '18 inches'], 'sku' => 'CHN001-18', 'stock' => 7],
                    ['options' => ['Length' => '20 inches'], 'sku' => 'CHN001-20', 'stock' => 6],
                ]
            ],

            // Earrings
            [
                'category_slug' => 'stud-earrings',
                'name' => 'Diamond Stud Earrings',
                'sku' => 'EAR001',
                'description' => 'Classic diamond stud earrings that add sparkle to any outfit. Available in different carat weights.',
                'material' => '18k White Gold',
                'brand' => 'Lunora',
                'price_pkr' => 18000000, // PKR 180,000
                'stock' => 15,
                'is_featured' => true,
                'variants' => [
                    ['options' => ['Carat' => '0.5 CT'], 'sku' => 'EAR001-05CT', 'price_pkr' => 15000000, 'stock' => 5],
                    ['options' => ['Carat' => '1.0 CT'], 'sku' => 'EAR001-1CT', 'price_pkr' => 18000000, 'stock' => 5],
                    ['options' => ['Carat' => '1.5 CT'], 'sku' => 'EAR001-15CT', 'price_pkr' => 22000000, 'stock' => 5],
                ]
            ],
            [
                'category_slug' => 'hoop-earrings',
                'name' => 'Gold Hoop Earrings',
                'sku' => 'EAR002',
                'description' => 'Elegant gold hoop earrings available in different sizes. A versatile addition to any jewelry collection.',
                'material' => '18k Yellow Gold',
                'brand' => 'Lunora',
                'price_pkr' => 9500000, // PKR 95,000
                'stock' => 18,
            ],

            // Bracelets
            [
                'category_slug' => 'tennis-bracelets',
                'name' => 'Diamond Tennis Bracelet',
                'sku' => 'BRA001',
                'description' => 'A stunning tennis bracelet featuring a continuous line of diamonds. Perfect for special occasions.',
                'material' => '18k White Gold',
                'brand' => 'Lunora',
                'price_pkr' => 45000000, // PKR 450,000
                'stock' => 6,
                'is_featured' => true,
            ],

            // Watches
            [
                'category_slug' => 'womens-watches',
                'name' => 'Elegant Ladies Watch',
                'sku' => 'WAT001',
                'description' => 'A sophisticated ladies watch with diamond markers and a mother-of-pearl dial.',
                'material' => '18k Rose Gold',
                'brand' => 'Lunora',
                'price_pkr' => 35000000, // PKR 350,000
                'stock' => 10,
                'is_featured' => true,
            ],

            // Jewelry Sets
            [
                'category_slug' => 'bridal-sets',
                'name' => 'Complete Bridal Set',
                'sku' => 'SET001',
                'description' => 'A complete bridal jewelry set including necklace, earrings, and bracelet. Perfect for the special day.',
                'material' => '22k Gold with Pearls',
                'brand' => 'Lunora',
                'price_pkr' => 75000000, // PKR 750,000
                'stock' => 4,
                'is_featured' => true,
            ]
        ];

        foreach ($products as $productData) {
            $category = Category::where('slug', $productData['category_slug'])->first();
            if (!$category) {
                continue;
            }

            $variants = $productData['variants'] ?? [];
            unset($productData['variants'], $productData['category_slug']);

            $productData['category_id'] = $category->id;
            $productData['slug'] = \Str::slug($productData['name']);
            $productData['is_active'] = true;

            $product = Product::updateOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );

            // Create product options and variants if specified
            if (!empty($variants)) {
                $this->createProductVariants($product, $variants);
            }

            // Create placeholder images
            $this->createProductImages($product);
        }

        $this->command->info('Products created successfully!');
    }

    private function createProductVariants(Product $product, array $variants): void
    {
        // Extract unique option names and values
        $optionGroups = [];
        foreach ($variants as $variant) {
            foreach ($variant['options'] as $optionName => $optionValue) {
                if (!isset($optionGroups[$optionName])) {
                    $optionGroups[$optionName] = [];
                }
                if (!in_array($optionValue, $optionGroups[$optionName])) {
                    $optionGroups[$optionName][] = $optionValue;
                }
            }
        }

        // Create product options and values
        foreach ($optionGroups as $optionName => $values) {
            $option = ProductOption::firstOrCreate([
                'product_id' => $product->id,
                'name' => $optionName,
            ]);

            foreach ($values as $value) {
                ProductOptionValue::firstOrCreate([
                    'product_option_id' => $option->id,
                    'value' => $value,
                ]);
            }
        }

        // Create variants
        foreach ($variants as $variantData) {
            $options = $variantData['options'];
            unset($variantData['options']);

            $variantData['product_id'] = $product->id;
            $variantData['options_json'] = json_encode($options);

            ProductVariant::firstOrCreate(
                ['sku' => $variantData['sku']],
                $variantData
            );
        }
    }

    private function createProductImages(Product $product): void
    {
        // Real-world jewelry image URLs mapped by product SKU
        $imageUrls = [
            'ENG001' => [ // Classic Solitaire Diamond Ring
                'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1544376664-80b17f09d399?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=800&h=800&fit=crop&crop=center',
            ],
            'ENG002' => [ // Vintage Halo Engagement Ring
                'https://images.unsplash.com/photo-1596944924616-7b38e7cfac36?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1602173574767-37ac01994b2a?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop&crop=center',
            ],
            'WED001' => [ // Classic Wedding Band
                'https://images.unsplash.com/photo-1544376664-80b17f09d399?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&h=800&fit=crop&crop=center',
            ],
            'PEN001' => [ // Heart Diamond Pendant
                'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1596944924616-7b38e7cfac36?w=800&h=800&fit=crop&crop=center',
            ],
            'CHN001' => [ // Gold Chain Necklace
                'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1611652022419-a9419f74343d?w=800&h=800&fit=crop&crop=center',
            ],
            'EAR001' => [ // Diamond Stud Earrings
                'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop&crop=center',
            ],
            'EAR002' => [ // Gold Hoop Earrings
                'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1611652022419-a9419f74343d?w=800&h=800&fit=crop&crop=center',
            ],
            'BRA001' => [ // Diamond Tennis Bracelet
                'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1544376664-80b17f09d399?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=800&h=800&fit=crop&crop=center',
            ],
            'WAT001' => [ // Elegant Ladies Watch
                'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1508057198894-247b23fe5ade?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?w=800&h=800&fit=crop&crop=center',
            ],
            'SET001' => [ // Complete Bridal Set
                'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1596944924616-7b38e7cfac36?w=800&h=800&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1544376664-80b17f09d399?w=800&h=800&fit=crop&crop=center',
            ],
        ];

        // Get images for this product's SKU, or use default jewelry images
        $defaultImages = [
            'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=800&h=800&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1596944924616-7b38e7cfac36?w=800&h=800&fit=crop&crop=center',
        ];

        $productImages = $imageUrls[$product->sku] ?? $defaultImages;

        // Delete existing placeholder images first
        $product->images()->where('file_path', 'like', 'products/placeholder-%')->delete();

        // Create new images with real URLs
        foreach ($productImages as $index => $imageUrl) {
            ProductImage::updateOrCreate([
                'product_id' => $product->id,
                'sort_order' => $index + 1,
            ], [
                'file_path' => $imageUrl, // Store URL directly
                'alt_text' => $product->name . " - Image " . ($index + 1),
                'is_primary' => $index === 0,
            ]);
        }
    }
}
