# Quick UI Reference Guide

## 🎨 Color Palette

### Primary Colors
- **Dark Background**: `rgb(17, 24, 39)` - #111827
- **Card Background**: `rgb(31, 41, 55)` - #1F2937
- **Primary Blue**: `rgb(59, 130, 246)` - #3B82F6
- **Secondary Gray**: `rgb(55, 65, 81)` - #374151

### Interactive Colors
- **Success Green**: `rgb(22, 163, 74)` - #16A34A
- **Danger Red**: `rgb(220, 38, 38)` - #DC2626
- **Warning Yellow**: Planned

### Text Colors
- **Primary Text**: White `rgb(255, 255, 255)`
- **Secondary Text**: `rgb(209, 213, 219)` - #D1D5DB
- **Muted Text**: `rgb(107, 114, 128)` - #6B7280
- **Accent Text**: Blue `rgb(59, 130, 246)`

## 🔘 Button Classes

### Available Button Styles
```html
<!-- Primary Button (Blue Gradient) -->
<button class="btn-primary">
  ✅ Upload Media
</button>

<!-- Secondary Button (Gray) -->
<button class="btn-secondary">
  ❌ Cancel
</button>

<!-- Danger Button (Red) -->
<button class="btn-danger">
  🗑️ Delete
</button>

<!-- Success Button (Green) -->
<button class="btn-success">
  ✓ Save
</button>
```

### Button Specifications
- **Min Height**: 48px (mobile touch target)
- **Padding**: 0.75rem 1.5rem
- **Font Weight**: 600 (semibold)
- **Border Radius**: 0.5rem (8px)
- **Transition**: 200ms

## 📝 Form Elements

### Standard Input
```html
<input type="text" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px]" placeholder="Enter text...">
```

### Textarea
```html
<textarea class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="4"></textarea>
```

### Select Box
```html
<select class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px]">
  <option>Option 1</option>
</select>
```

### File Input
```html
<input type="file" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px]">
```

### Form Label (with Icon)
```html
<label class="block text-sm font-semibold mb-2 text-blue-400">
  📝 Title
</label>
```

## 🎯 Card Component

### Basic Card
```html
<div class="card">
  <!-- Content here -->
</div>
```

### Card Specifications
- **Background**: Gradient (dark → slightly lighter)
- **Border**: 1px solid rgba(75, 85, 99, 0.5)
- **Border Radius**: 0.75rem (12px)
- **Padding**: 1rem (mobile) / 1.5rem (desktop)
- **Shadow**: `0 4px 12px rgba(0, 0, 0, 0.2)`
- **Hover Shadow**: `0 6px 20px rgba(59, 130, 246, 0.15)`

## 📱 Responsive Grid

### Grid Classes
```html
<!-- Grid Container -->
<div class="grid gap-4">
  <!-- Grid items -->
</div>

<!-- Desktop 3 Columns -->
<div class="grid grid-cols-3 gap-4">

<!-- Tablet 2 Columns -->
<div class="grid grid-cols-2 gap-4">

<!-- Mobile 1 Column (default) -->
<div class="grid grid-cols-1 gap-4">
```

### Common Responsive Patterns
```html
<!-- 1 col on mobile, 2 on tablet, 3 on desktop -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

<!-- Full width on mobile, 2/3 on desktop -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="md:col-span-2">Main content</div>
  <div>Sidebar</div>
</div>
```

## 🏷️ Typography

### Heading Sizes
```html
<h2 class="text-2xl sm:text-3xl font-bold">Main Title</h2>
<h3 class="text-xl font-bold">Subheading</h3>
<h4 class="text-lg font-semibold">Section Title</h4>
```

### Text Sizes
```html
<p class="text-base">Normal paragraph</p>
<p class="text-sm">Small text</p>
<p class="text-xs">Extra small text</p>
```

### Text Colors
```html
<p class="text-white">Primary text</p>
<p class="text-gray-300">Secondary text</p>
<p class="text-gray-400">Muted text</p>
<p class="text-blue-400">Accent text</p>
<p class="text-red-400">Error text</p>
```

## 🎨 Spacing Guide

### Margin
```css
.mt-2  { margin-top: 0.5rem }    /* 8px */
.mt-4  { margin-top: 1rem }      /* 16px */
.mt-6  { margin-top: 1.5rem }    /* 24px */
.mt-8  { margin-top: 2rem }      /* 32px */
.mt-12 { margin-top: 3rem }      /* 48px */
```

### Padding
```css
.px-2  { padding: 0 0.5rem }     /* 0 8px */
.px-4  { padding: 0 1rem }       /* 0 16px */
.px-6  { padding: 0 1.5rem }     /* 0 24px */
.py-3  { padding: 0.75rem 0 }    /* 12px 0 */
.py-4  { padding: 1rem 0 }       /* 16px 0 */
.p-6   { padding: 1.5rem }       /* 24px all */
```

## 🖼️ Common Layout Patterns

### Header with Button
```html
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
  <h2 class="text-3xl font-bold">Title</h2>
  <button class="btn-primary">Action</button>
</div>
```

### Form with Left Content and Right Sidebar
```html
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="md:col-span-2">
    <!-- Form fields -->
  </div>
  <div class="md:col-span-1">
    <div class="sticky top-6">
      <!-- Sidebar content -->
    </div>
  </div>
</div>
```

### Table or Card List
```html
<div class="card">
  <div class="overflow-x-auto">
    <table class="w-full">
      <!-- Table content -->
    </table>
  </div>
</div>
```

## 🔗 Icon Usage Guide

### Common Icons Used
- 📝 Text/Title field
- 📄 Description/Content
- 🎬 Movie/Media
- 📺 Series/Television
- 📂 Category/Folder
- 📅 Year/Date
- 👤 Director/Person
- ⏱️ Duration/Time
- 🎞️ Episodes/Clips
- 🎭 Cast/Actors
- 🎨 Poster/Image
- ✅ Success/Confirm
- ❌ Cancel/Delete
- 🗑️ Delete/Remove
- ⚙️ Settings/Actions
- 📸 Photo/Image
- ➕ Add/Create
- ✏️ Edit
- 🔍 Search

## 📐 Responsive Breakpoints

```css
/* Mobile First */
Default        /* 0px - 639px */

/* Tablet and Up */
sm:             /* 640px+ */

/* Desktop and Up */
md:             /* 768px+ */

/* Large Desktop and Up */
lg:             /* 1024px+ */

/* Extra Large */
xl:             /* 1280px+ */

/* 2XL */
2xl:            /* 1536px+ */
```

### Example Usage
```html
<!-- Desktop: 3 columns, Tablet: 2 columns, Mobile: 1 column -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
```

## ✨ Common Effects

### Hover Effects
```html
<!-- Hover background -->
<button class="hover:bg-blue-600">Button</button>

<!-- Hover text color -->
<a class="hover:text-blue-400">Link</a>

<!-- Hover scale -->
<div class="hover:scale-105">Hover me</div>

<!-- Hover shadow -->
<div class="hover:shadow-lg">Card</div>
```

### Transitions
```html
<!-- Smooth transition -->
<button class="transition hover:bg-blue-600">Hover</button>

<!-- With specific duration -->
<div class="transition duration-200 hover:scale-105">
```

## 🎯 Accessibility Classes

### Touch Targets
```html
<!-- Min 48px height for buttons/inputs -->
<button class="min-h-[48px]">Touch me</button>
<input class="min-h-[48px]">
```

### Focus States
```html
<!-- Clear focus indicator -->
<input class="focus:ring-2 focus:ring-blue-500">
```

### Semantic Markup
```html
<label for="title">Title</label>
<input id="title" type="text">
```

## 🚀 Performance Tips

1. **Use card class** for consistent styling
2. **Use grid for layouts** (responsive by default)
3. **Min-height for inputs**: 48px (touch friendly)
4. **Use transition class**: For smooth effects
5. **Avoid inline styles**: Use utility classes
6. **Mobile first**: Start with mobile design, add breakpoints

## 🔧 Customization

### Change Primary Color
1. Find `.btn-primary` in CSS
2. Change `rgb(37, 99, 235)` and `rgb(59, 130, 246)` values
3. Update related hover colors

### Change Background
1. Find `body` styling
2. Change gradient colors in `background`

### Adjust Spacing
1. Modify `px-4`, `py-3`, etc. values
2. Update in responsive sections (sm:, md:, lg:)

## 📚 Resources

- [CSS Color Codes](https://www.color-hex.com/)
- [Accessibility Checklist](https://www.a11yproject.com/)
- [Responsive Design](https://web.dev/responsive-web-design-basics/)

---

**Version**: 1.0
**Last Updated**: 2024
**Status**: Production Ready
