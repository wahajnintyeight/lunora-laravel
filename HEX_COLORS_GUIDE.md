# Using Hex Colors in Tailwind CSS

Instead of using color classes like `bg-amber-500`, you can use hex colors directly with Tailwind's arbitrary value syntax.

## Syntax

Wrap hex colors in square brackets: `[#hexcode]`

## Examples

### Background Colors
- `bg-amber-500` → `bg-[#f59e0b]`
- `bg-rose-900` → `bg-[#881337]`
- `bg-slate-50` → `bg-[#f8fafc]`

### Text Colors
- `text-amber-700` → `text-[#b45309]`
- `text-rose-700` → `text-[#be123c]`
- `text-slate-600` → `text-[#475569]`

### Border Colors
- `border-amber-400` → `border-[#fbbf24]`
- `border-amber-400/30` → `border-[#fbbf24]/30` (with opacity)

### Gradient Colors
- `from-amber-500` → `from-[#f59e0b]`
- `to-rose-600` → `to-[#e11d48]`
- `via-rose-900` → `via-[#881337]`

## Color Palette Reference

### Amber
- 50: `#fffbeb`
- 100: `#fef3c7`
- 200: `#fde68a`
- 300: `#fcd34d`
- 400: `#fbbf24`
- 500: `#f59e0b` ⭐ Primary
- 600: `#d97706`
- 700: `#b45309`
- 800: `#92400e`
- 900: `#78350f`
- 950: `#451a03`

### Rose
- 50: `#fff1f2`
- 100: `#ffe4e6`
- 200: `#fecdd3`
- 300: `#fda4af`
- 400: `#fb7185`
- 500: `#f43f5e`
- 600: `#e11d48`
- 700: `#be123c`
- 800: `#9f1239`
- 900: `#881337` ⭐ Primary
- 950: `#4c0519`

### Slate
- 50: `#f8fafc`
- 100: `#f1f5f9`
- 200: `#e2e8f0`
- 300: `#cbd5e1`
- 400: `#94a3b8`
- 500: `#64748b`
- 600: `#475569`
- 700: `#334155`
- 800: `#1e293b`
- 900: `#0f172a`
- 950: `#020617`

## Benefits

1. **No configuration needed** - Works immediately
2. **Exact colors** - Use any hex color you want
3. **No build issues** - Doesn't depend on Tailwind config
4. **Flexible** - Easy to change colors on the fly

## Tips

- Use opacity with `/` syntax: `bg-[#f59e0b]/50` for 50% opacity
- Works with all Tailwind utilities: `hover:bg-[#f59e0b]`, `focus:ring-[#f59e0b]`, etc.
- Can mix with regular classes: `bg-[#f59e0b] text-white rounded-lg`

