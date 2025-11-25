<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PlaceholderImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        
        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        // Create image manager
        $manager = new ImageManager(new Driver());
        
        // Ensure directories exist
        Storage::makeDirectory('public/products');
        Storage::makeDirectory('public/products/thumbnails');
        Storage::makeDirectory('public/products/medium');

        $imageCount = 0;
        
        foreach ($products as $product) {
            // Skip if product already has images
            if ($product->images()->count() > 0) {
                continue;
            }
            
            $numImages = rand(2, 4);
            
            for ($i = 1; $i <= $numImages; $i++) {
                $filename = "product-{$product->id}-{$i}.jpg";
                $imagePath = "products/{$filename}";
                $thumbnailPath = "products/thumbnails/product-{$product->id}-{$i}-thumb.jpg";
                $mediumPath = "products/medium/product-{$product->id}-{$i}-medium.jpg";
                
                // Create placeholder image
                $this->createPlaceholderImage($manager, $imagePath, $product->name, 800, 800);
                $this->createPlaceholderImage($manager, $thumbnailPath, $product->name, 200, 200);
                $this->createPlaceholderImage($manager, $mediumPath, $product->name, 400, 400);
                
                // Create database record
                ProductImage::create([
                    'product_id' => $product->id,
                    'file_path' => $imagePath,
                    'alt_text' => $product->name . " - Image {$i}",
                    'sort_order' => $i,
                    'is_primary' => $i === 1,
                ]);
                
                $imageCount++;
            }
        }

        $this->command->info("Created {$imageCount} placeholder images for products!");
    }

    private function createPlaceholderImage($manager, string $path, string $productName, int $width, int $height): void
    {
        // Create a simple placeholder image
        $image = $manager->create($width, $height);
        
        // Set background color based on product type
        $bgColor = $this->getColorForProduct($productName);
        $image->fill($bgColor);
        
        // Add text
        $fontSize = min($width, $height) / 15;
        $textColor = $this->getContrastColor($bgColor);
        
        // Add product name (truncated if too long)
        $displayName = strlen($productName) > 20 ? substr($productName, 0, 17) . '...' : $productName;
        
        $image->text($displayName, $width / 2, $height / 2 - 20, function ($font) use ($fontSize, $textColor) {
            $font->size($fontSize);
            $font->color($textColor);
            $font->align('center');
            $font->valign('middle');
        });
        
        // Add "PLACEHOLDER" text
        $image->text('PLACEHOLDER', $width / 2, $height / 2 + 20, function ($font) use ($fontSize, $textColor) {
            $font->size($fontSize * 0.6);
            $font->color($textColor);
            $font->align('center');
            $font->valign('middle');
        });
        
        // Save image
        Storage::put($path, $image->toJpeg(80));
    }

    private function getColorForProduct(string $productName): string
    {
        $name = strtolower($productName);
        
        if (str_contains($name, 'ring')) {
            return '#FFD700'; // Gold
        } elseif (str_contains($name, 'necklace') || str_contains($name, 'pendant')) {
            return '#C0C0C0'; // Silver
        } elseif (str_contains($name, 'earring')) {
            return '#E6E6FA'; // Lavender
        } elseif (str_contains($name, 'bracelet')) {
            return '#FFC0CB'; // Pink
        } elseif (str_contains($name, 'watch')) {
            return '#2F4F4F'; // Dark Slate Gray
        } elseif (str_contains($name, 'set')) {
            return '#DDA0DD'; // Plum
        } else {
            return '#F5F5DC'; // Beige
        }
    }

    private function getContrastColor(string $bgColor): string
    {
        // Simple contrast logic - return black for light colors, white for dark
        $darkColors = ['#2F4F4F'];
        
        return in_array($bgColor, $darkColors) ? '#FFFFFF' : '#000000';
    }
}