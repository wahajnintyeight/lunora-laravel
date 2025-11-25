# Lunora Color System - Consistency Guide

## Brand Colors

### Primary Colors
- **Maroon (Primary Brand Color)**: `#450a0a` / `maroon-950`
  - Used for: Main buttons, primary text, brand identity
  - Dark mode background: `#2a0606` / `maroon-950`
  - Hover state: `maroon-900` (#5c0e0e)

- **Gold (Accent Color)**: `#f59e0b` / `gold-500`
  - Used for: Links, focus states, accents, hover effects
  - Light variant: `gold-300` (#fcd34d)
  - Dark variant: `gold-600` (#d97706)

## Tailwind Custom Colors

All colors are defined in `tailwind.config.js` under custom color palettes:

```javascript
'gold': {
    50: '#fffbeb',
    100: '#fef3c7',
    200: '#fde68a',
    300: '#fcd34d',
    400: '#fbbf24',
    500: '#f59e0b',    // Primary accent
    600: '#d97706',
    700: '#b45309',
    800: '#92400e',
    900: '#78350f',
    950: '#451a03',
},
'maroon': {
    50: '#fdf2f2',
    100: '#fce7e7',
    200: '#fbd5d5',
    300: '#f8b4b4',
    400: '#f87171',
    500: '#ef4444',
    600: '#dc2626',
    700: '#b91c1c',
    800: '#991b1b',
    900: '#7f1d1d',
    950: '#450a0a',    // Primary brand color
},
'lunora': {
    'primary': '#450a0a',      // Maroon
    'accent': '#f59e0b',       // Gold
    'light': '#fef3c7',        // Light gold
    'dark': '#2a0606',         // Dark maroon
},
```

## Component Color Usage

### Buttons
- **Primary Button (CTA)**
  - Background: `bg-maroon-950`
  - Text: `text-white`
  - Hover: `hover:bg-maroon-900`
  - Focus Ring: `focus:ring-gold-500`
  - Example: `class="bg-maroon-950 text-white hover:bg-maroon-900 focus:ring-2 focus:ring-gold-500"`

- **Secondary Button**
  - Background: `bg-white` or `bg-gray-100`
  - Text: `text-maroon-950`
  - Border: `border-2 border-maroon-950`
  - Hover: `hover:bg-gray-50`

### Links & Text
- **Primary Links**: `text-maroon-950 hover:text-gold-500`
- **Accent Links**: `text-gold-500 hover:text-gold-600`
- **Dark Mode Links**: `dark:text-gold-500 dark:hover:text-gold-300`

### Form Elements
- **Input Borders**: `border-2 border-gray-300`
- **Input Focus**: `focus:ring-2 focus:ring-gold-500 focus:border-gold-500`
- **Input Dark Mode**: `dark:bg-maroon-950 dark:border-maroon-900`
- **Checkbox**: `text-maroon-950 focus:ring-gold-500`

### Backgrounds
- **Light Mode**: `bg-white` or `bg-gray-50`
- **Dark Mode**: `dark:bg-maroon-950` or `dark:bg-[#2a0606]`
- **Gradient**: `bg-gradient-to-br from-[#fef3c7] via-[#fcd34d] to-[#f59e0b]`

## Dark Mode Considerations

- Replace `dark:bg-neutral-*` with `dark:bg-maroon-*`
- Replace `dark:text-neutral-*` with `dark:text-gray-*` or `dark:text-gold-*`
- Use `dark:border-maroon-900` instead of `dark:border-neutral-700`

## Migration Checklist

When updating components, replace:
- `#450a0a` → `maroon-950`
- `#f59e0b` → `gold-500`
- `#fcd34d` → `gold-300`
- `#2a0606` → `maroon-950` (dark mode)
- `neutral-*` → `maroon-*` (dark mode backgrounds)
- `neutral-*` → `gray-*` (dark mode text)

## Examples

### Login Button
```html
<button class="w-full py-3 px-4 font-semibold text-white bg-maroon-950 rounded-lg hover:bg-maroon-900 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 dark:focus:ring-offset-maroon-950 transition-colors duration-200 shadow-lg">
    Sign in
</button>
```

### Form Input
```html
<input 
    type="email"
    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 dark:bg-maroon-950 dark:border-maroon-900 dark:text-white dark:focus:ring-gold-500"
/>
```

### Link
```html
<a href="#" class="text-maroon-950 hover:text-gold-500 dark:text-gold-500 dark:hover:text-gold-300 transition-colors">
    Link Text
</a>
```

## Files Updated
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/layouts/auth.blade.php`
- `tailwind.config.js`

## Future Updates
When adding new components or pages, always:
1. Use the custom color names from `tailwind.config.js`
2. Follow the button and link patterns above
3. Ensure dark mode compatibility
4. Test on both light and dark themes
