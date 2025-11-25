# Product Card Component Documentation

## Overview
The product card component is a reusable, customizable Blade component for displaying product information in a visually appealing card format. It's designed to work seamlessly with the Lunora jewelry e-commerce platform.

## Location
`resources/views/components/product-card.blade.php`

## Basic Usage

### Simple Product Card
```blade
<x-product-card :product="$product" />
```

### With Custom Options
```blade
<x-product-card 
    :product="$product"
    variant="featured"
    :showRating="true"
    badgeColor="maroon"
    imageHeight="h-80"
/>
```

## Props

### Required Props
- **product** (Product Model): The product instance to display
  - Must have: `id`, `name`, `slug`, `price_pkr`, `featured_image`, `category`, `description`, `sku`

### Optional Props

#### Display Options
- **variant** (string, default: 'default')
  - Options: `'default'`, `'featured'`, `'compact'`, `'minimal'`
  - Controls the overall card style and layout

- **showPrice** (boolean, default: true)
  - Show/hide the price section

- **showRating** (boolean, default: false)
  - Show/hide the star rating display

- **showBadge** (boolean, default: true)
  - Show/hide the badge (Featured, Out of Stock, etc.)

- **badgeText** (string, default: null)
  - Custom badge text. If null, auto-determines based on product status

- **badgeColor** (string, default: 'gold')
  - Options: `'gold'`, `'maroon'`, `'red'`, `'green'`
  - Controls badge background color

#### Styling Options
- **hoverEffect** (boolean, default: true)
  - Enable/disable hover animations and overlay actions

- **imageHeight** (string, default: 'h-64')
  - Tailwind height class for image container
  - Examples: `'h-48'`, `'h-64'`, `'h-80'`, `'h-96'`

- **class** (string, default: '')
  - Additional CSS classes to apply to the card container

## Features

### Visual Elements
1. **Product Image**
   - Lazy loading enabled
   - Hover zoom effect (when hoverEffect enabled)
   - Fallback placeholder image

2. **Badge**
   - Auto-displays "Featured" for featured products
   - Auto-displays "Out of Stock" for unavailable products
   - Customizable text and color

3. **Product Information**
   - Category name (in gold accent color)
   - Product name with hover effect
   - Truncated description (2 lines max)
   - Optional star rating

4. **Pricing**
   - Current price in PKR
   - Original price (if on sale)
   - Discount percentage badge (if applicable)

5. **Stock Status**
   - Visual indicator (green checkmark for in stock)
   - SKU display
   - Out of stock overlay

6. **Action Buttons**
   - "View Details" button (maroon)
   - "Add to Cart" button (gold)
   - Disabled state when out of stock

### Hover Effects
When `hoverEffect` is enabled:
- Card scales up slightly (105%)
- Shadow increases
- Image zooms in
- Dark overlay appears with action buttons
- "View" and "Add" quick action buttons

## Color Scheme

The component uses the Lunora brand colors:
- **Primary**: Maroon (#450a0a) - buttons, text
- **Accent**: Gold (#f59e0b) - highlights, badges, hover states
- **Status**: Green (in stock), Red (out of stock)

## Usage Examples

### Featured Product Display
```blade
<x-product-card 
    :product="$featuredProduct"
    variant="featured"
    :showRating="true"
    badgeText="New Arrival"
    badgeColor="gold"
    imageHeight="h-80"
/>
```

### Compact Grid Display
```blade
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach ($products as $product)
        <x-product-card 
            :product="$product"
            imageHeight="h-48"
            :hoverEffect="true"
        />
    @endforeach
</div>
```

### Minimal Card (No Hover Effects)
```blade
<x-product-card 
    :product="$product"
    :hoverEffect="false"
    :showRating="false"
/>
```

### Custom Styling
```blade
<x-product-card 
    :product="$product"
    class="border-2 border-gold-500 rounded-2xl"
    imageHeight="h-72"
/>
```

## JavaScript Integration

The component includes two JavaScript function calls:
- `addToCart(productId)` - Adds product to cart
- `addToWishlist(productId)` - (Optional) Adds product to wishlist

Make sure these functions are defined in your main JavaScript file:

```javascript
function addToCart(productId) {
    // Implementation for adding to cart
    console.log('Adding product', productId, 'to cart');
    // Make AJAX request to add-to-cart endpoint
}
```

## Responsive Design

The component is fully responsive:
- **Mobile (< 640px)**: Full width, optimized spacing
- **Tablet (640px - 1024px)**: 2-column grid
- **Desktop (> 1024px)**: 3-4 column grid

## Accessibility

- Semantic HTML structure
- Proper alt text for images
- Keyboard navigation support
- ARIA labels for interactive elements
- Color contrast meets WCAG standards

## Performance Considerations

1. **Image Optimization**
   - Lazy loading enabled by default
   - Use optimized image sizes
   - Consider using WebP format

2. **Database Queries**
   - Eager load relationships when displaying multiple cards
   - Example: `Product::with('category', 'images')->get()`

3. **Caching**
   - Consider caching product data for frequently accessed products

## Customization Guide

### Adding Custom Badge Colors
Edit `resources/views/components/product-card.blade.php`:

```php
$badgeColors = [
    'gold' => 'bg-gold-500 text-maroon-950',
    'maroon' => 'bg-maroon-950 text-white',
    'red' => 'bg-red-500 text-white',
    'green' => 'bg-green-500 text-white',
    'purple' => 'bg-purple-500 text-white', // Add new color
];
```

### Modifying Hover Effects
To customize hover animations, modify the Tailwind classes:
- `hover:scale-105` - Change scale amount
- `hover:shadow-xl` - Change shadow intensity
- `group-hover:scale-110` - Change image zoom

### Changing Button Styles
Update the button classes in the component to match your design preferences.

## Browser Support

- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- IE11: Not supported (uses modern CSS features)

## Related Components

- `resources/views/components/product-grid.blade.php` - Grid wrapper for multiple cards
- `resources/views/components/product-carousel.blade.php` - Carousel display
- `resources/views/components/product-modal.blade.php` - Quick view modal

## Troubleshooting

### Image Not Displaying
- Check if `featured_image` attribute exists on product
- Verify image path is correct
- Check file permissions

### Buttons Not Working
- Ensure `addToCart()` function is defined in JavaScript
- Check browser console for errors
- Verify product ID is being passed correctly

### Styling Issues
- Clear browser cache
- Rebuild Tailwind CSS
- Check for CSS conflicts with other components

## Future Enhancements

- [ ] Add wishlist button
- [ ] Add quick view modal
- [ ] Add size/color variant selector
- [ ] Add customer reviews display
- [ ] Add product comparison checkbox
- [ ] Add social sharing buttons
- [ ] Add video preview support
