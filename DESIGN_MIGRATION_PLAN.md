# Design System Migration Plan
## Lovable Frontend → Laravel Application

**Date**: 2025-11-30  
**Scope**: CSS/Styling Migration Only (Excluding React Components & 3D Elements)

---

## Executive Summary

This plan outlines the migration of the visual design system from the lovable-frontend (React/Vite) project to the Laravel application (Vue 3/Inertia.js) while preserving all existing Laravel functionality. The migration focuses exclusively on CSS, design tokens, typography, colors, animations, and visual styling.

### Key Decisions Made:
- ✅ **CSS/Styling Only**: Migrate visual design, not React components
- ✅ **Keep Tailwind v4**: Translate v3 config to v4's CSS-first approach
- ✅ **Font Replacement**: Replace Instrument Sans with Inter + Merriweather
- ✅ **Exclude 3D Components**: React Three Fiber components not migrated
- ✅ **Preserve Laravel**: All backend logic, routes, controllers remain unchanged

---

## Design System Analysis

### Source Project (lovable-frontend)
**Technology Stack:**
- React 18.3.1 + TypeScript
- Vite 5.4.19
- Tailwind CSS 3.4.17 (traditional config)
- shadcn/ui (React components)
- Radix UI primitives
- React Three Fiber (3D components)

**Design Tokens:**
```css
/* Color Palette - Dark, Sophisticated Theme */
--background: 0 0% 8%           /* Very dark gray */
--foreground: 36 44% 96%        /* Warm off-white */
--card: 0 0% 12%                /* Dark card background */
--primary: 36 44% 96%           /* Warm white */
--secondary: 25 15% 25%         /* Warm dark brown */
--accent: 140 20% 45%           /* Muted green */
--destructive: 0 62% 50%        /* Red */
--radius: 1rem                  /* Large border radius */

/* Custom Tag Colors */
--tag-financing: 210 50% 75%    /* Blue */
--tag-lifestyle: 140 20% 70%    /* Green */
--tag-community: 25 35% 60%     /* Orange */
--tag-wellness: 280 30% 75%     /* Purple */
--tag-travel: 195 50% 70%       /* Cyan */
--tag-creativity: 330 40% 75%   /* Pink */
--tag-growth: 50 45% 70%        /* Yellow */

/* Special Effects */
--gradient-accent: linear-gradient(135deg, hsl(140, 25%, 42%), hsl(140, 30%, 52%))
--gradient-destructive: linear-gradient(135deg, hsl(0, 65%, 48%), hsl(340, 65%, 52%))
--glow-accent: 0 0 30px hsla(140, 25%, 45%, 0.4), 0 10px 30px rgba(0, 0, 0, 0.3)
--glow-destructive: 0 0 40px hsla(0, 65%, 50%, 0.5), 0 10px 30px rgba(0, 0, 0, 0.3)
```

**Typography:**
- **Sans**: Inter (400, 500, 600, 700)
- **Serif**: Merriweather (400, 700, 900)
- **Mono**: Inconsolata
- **Letter Spacing**: ultra-wide (0.2em), extra-wide (0.15em)
- **Headings**: Use serif font with tight tracking

**Custom Utilities:**
- `.card-hover`: Smooth scale transition with shadow effects
- `.btn-voice-active`: Gradient background with glow effect
- `.btn-voice-recording`: Pulsing glow animation
- `.btn-voice-secondary`: Backdrop blur with subtle shadow

**Animations:**
- `fade-in`: Opacity transition
- `slide-in-from-bottom`: Translate Y + opacity
- `zoom-in`: Scale + opacity
- `pulse-glow`: Pulsing shadow effect (2s infinite)
- `accordion-down/up`: Height transitions

---

### Target Project (Laravel)
**Technology Stack:**
- Laravel (PHP backend)
- Vue 3.5.13 + TypeScript
- Inertia.js 2.1.0
- Vite 7.0.4
- Tailwind CSS 4.1.1 (CSS-first config)
- reka-ui (Vue components - shadcn equivalent)
- tw-animate-css 1.2.5

**Current Design:**
- **Font**: Instrument Sans (400, 500, 600)
- **Colors**: Neutral palette (light theme default)
- **Radius**: 0.5rem (smaller than lovable-frontend)
- **Dark Mode**: Supported via class-based toggling

---

## Migration Strategy

### Phase 1: Font Integration
**File**: [`resources/views/app.blade.php`](resources/views/app.blade.php:39)

**Current:**
```html
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
```

**New:**
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700;900&display=swap" rel="stylesheet">
```

---

### Phase 2: CSS Variables Migration
**File**: [`resources/css/app.css`](resources/css/app.css:1)

**Strategy**: Replace existing `:root` and `.dark` CSS variables with lovable-frontend's sophisticated dark palette.

**Key Changes:**
1. **Background**: Change from light default to dark (8% lightness)
2. **Radius**: Increase from 0.5rem to 1rem
3. **Accent Color**: Change from neutral to muted green (140 20% 45%)
4. **Add Custom Variables**: Tag colors, gradients, glows, shadows

**Tailwind v4 Translation:**
Since Laravel uses Tailwind v4's `@theme` directive, we need to translate the v3 config to CSS variables within the `@theme` block.

---

### Phase 3: Theme Configuration
**File**: [`resources/css/app.css`](resources/css/app.css:10)

**Add to `@theme inline` block:**
```css
@theme inline {
    /* Font Families */
    --font-sans: Inter, ui-sans-serif, system-ui, sans-serif;
    --font-serif: Merriweather, Georgia, serif;
    --font-mono: Inconsolata, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    --font-display: Inter, system-ui, sans-serif;
    --font-body: Inter, system-ui, sans-serif;
    
    /* Letter Spacing */
    --letter-spacing-ultra-wide: 0.2em;
    --letter-spacing-extra-wide: 0.15em;
    
    /* Box Shadows */
    --shadow-2xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-xs: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-sm: 0 2px 4px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
}
```

---

### Phase 4: Custom Animations
**File**: [`resources/css/app.css`](resources/css/app.css:1)

**Add after `@layer utilities` block:**
```css
@layer utilities {
    /* Existing utilities... */
    
    /* Custom Animations from lovable-frontend */
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slide-in-from-bottom {
        from {
            transform: translateY(1rem);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes zoom-in {
        from {
            transform: scale(0.95);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: var(--glow-destructive);
        }
        50% {
            box-shadow: 0 0 50px hsla(0, 65%, 50%, 0.7), 0 10px 40px rgba(0, 0, 0, 0.4);
        }
    }
}
```

---

### Phase 5: Custom Utility Classes
**File**: [`resources/css/app.css`](resources/css/app.css:82)

**Add to `@layer utilities` block:**
```css
@layer utilities {
    .card-hover {
        @apply transition-all duration-500 hover:scale-[1.02];
        box-shadow: 0 4px 20px -4px hsl(var(--shadow-soft) / 0.1);
    }
    
    .card-hover:hover {
        box-shadow: 0 20px 40px -10px hsl(var(--shadow-soft) / 0.15);
    }
    
    .btn-voice-active {
        background: var(--gradient-accent);
        box-shadow: var(--glow-accent);
        @apply transition-all duration-300 hover:scale-105;
    }
    
    .btn-voice-recording {
        background: var(--gradient-destructive);
        box-shadow: var(--glow-destructive);
        @apply transition-all duration-300;
        animation: pulse-glow 2s ease-in-out infinite;
    }
    
    .btn-voice-secondary {
        @apply bg-secondary/60 hover:bg-secondary/80 backdrop-blur-sm transition-all duration-300 hover:scale-105;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    
    /* Animation helper classes */
    .animate-in {
        animation-fill-mode: both;
    }
    
    .fade-in-0 {
        animation-name: fade-in;
    }
    
    .slide-in-from-bottom-4 {
        animation-name: slide-in-from-bottom;
    }
    
    .zoom-in-50 {
        animation-name: zoom-in;
    }
    
    .duration-300 { animation-duration: 300ms; }
    .duration-500 { animation-duration: 500ms; }
    .duration-700 { animation-duration: 700ms; }
    .duration-1000 { animation-duration: 1000ms; }
    
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
}
```

---

### Phase 6: Base Styles
**File**: [`resources/css/app.css`](resources/css/app.css:165)

**Update `@layer base` block:**
```css
@layer base {
    * {
        @apply border-border outline-ring/50;
    }
    
    html {
        scroll-behavior: smooth;
    }
    
    body {
        @apply bg-background text-foreground font-sans antialiased;
    }
    
    h1, h2, h3, h4, h5, h6 {
        @apply font-serif tracking-tight;
    }
}
```

---

## Detailed File Changes

### 1. [`resources/views/app.blade.php`](resources/views/app.blade.php:1)

**Changes:**
- Replace Bunny Fonts with Google Fonts (Inter + Merriweather)
- Update inline background color to match new dark theme
- Add preconnect for fonts.gstatic.com

**Lines to modify:**
- Line 39-40: Font links
- Line 25-30: Inline background colors

---

### 2. [`resources/css/app.css`](resources/css/app.css:1)

**Changes:**
- Replace `:root` color variables (lines 92-127) with lovable-frontend palette
- Replace `.dark` color variables (lines 129-163) with lovable-frontend dark mode
- Add custom CSS variables for tags, gradients, glows, shadows
- Update `@theme inline` block with new font families and shadows
- Add custom animations and keyframes
- Add custom utility classes
- Update base styles for headings

**Structure:**
```
@import 'tailwindcss';
@import 'tw-animate-css';

@source directives...
@custom-variant dark...

@theme inline {
    /* Add font families, shadows, letter spacing */
}

:root {
    /* Replace with lovable-frontend color palette */
    /* Add custom variables: tags, gradients, glows */
}

.dark {
    /* Replace with lovable-frontend dark mode colors */
}

@layer base {
    /* Update with heading styles, smooth scroll */
}

@layer utilities {
    /* Add custom animations */
    /* Add custom utility classes */
}
```

---

## Color Palette Comparison

### Background Colors
| Element | Current (Light) | New (Dark) | Change |
|---------|----------------|------------|--------|
| Background | `hsl(0 0% 100%)` | `hsl(0 0% 8%)` | ⚠️ Dark by default |
| Card | `hsl(0 0% 100%)` | `hsl(0 0% 12%)` | ⚠️ Dark cards |
| Foreground | `hsl(0 0% 3.9%)` | `hsl(36 44% 96%)` | ⚠️ Warm white text |

### Accent Colors
| Element | Current | New | Change |
|---------|---------|-----|--------|
| Primary | `hsl(0 0% 9%)` | `hsl(36 44% 96%)` | ⚠️ Light primary |
| Accent | `hsl(0 0% 96.1%)` | `hsl(140 20% 45%)` | ⚠️ Green accent |
| Secondary | `hsl(0 0% 92.1%)` | `hsl(25 15% 25%)` | ⚠️ Dark brown |

### Border Radius
| Current | New | Impact |
|---------|-----|--------|
| 0.5rem | 1rem | ⚠️ Rounder corners throughout |

---

## Typography Changes

### Font Families
| Purpose | Current | New |
|---------|---------|-----|
| Sans | Instrument Sans | **Inter** |
| Serif | *(not defined)* | **Merriweather** |
| Mono | *(system default)* | **Inconsolata** |
| Display | Instrument Sans | **Inter** |
| Body | Instrument Sans | **Inter** |

### Font Weights
- **Inter**: 400 (regular), 500 (medium), 600 (semibold), 700 (bold)
- **Merriweather**: 400 (regular), 700 (bold), 900 (black)

### Heading Styles
**New behavior**: All headings (`h1-h6`) will use **Merriweather serif** font with tight tracking, creating a more editorial, sophisticated look.

---

## Custom Features to Migrate

### 1. Tag Color System
Seven semantic tag colors for categorization:
- Financing (blue)
- Lifestyle (green)
- Community (orange)
- Wellness (purple)
- Travel (cyan)
- Creativity (pink)
- Growth (yellow)

**Usage**: `bg-[hsl(var(--tag-financing))]`

### 2. Button Variants
Three special voice/interaction button styles:
- **Active**: Green gradient with glow
- **Recording**: Red gradient with pulsing glow animation
- **Secondary**: Translucent with backdrop blur

### 3. Card Hover Effect
Smooth scale animation with progressive shadow enhancement.

### 4. Animation Classes
Reusable animation utilities with configurable duration and delay.

---

## Breaking Changes & Considerations

### ⚠️ Visual Breaking Changes

1. **Dark Theme by Default**
   - Current: Light theme default
   - New: Dark theme default (8% lightness background)
   - **Impact**: Entire application will appear dark unless explicitly set to light mode
   - **Mitigation**: Users can still toggle via appearance settings

2. **Larger Border Radius**
   - Current: 0.5rem
   - New: 1rem
   - **Impact**: All rounded elements (buttons, cards, inputs) will be noticeably rounder
   - **Mitigation**: None needed - this is intentional design change

3. **Font Replacement**
   - Current: Instrument Sans (geometric, modern)
   - New: Inter (neutral, versatile) + Merriweather (serif for headings)
   - **Impact**: Headings will have serif font, body text slightly different appearance
   - **Mitigation**: None needed - improves typography hierarchy

4. **Color Scheme Shift**
   - Current: Neutral grays
   - New: Warm tones (36° hue) with green accents
   - **Impact**: Warmer overall appearance, green accent color instead of neutral
   - **Mitigation**: None needed - this is the desired aesthetic

### ✅ Non-Breaking Changes

1. **Vue Components**: All existing Vue components remain unchanged
2. **Laravel Backend**: No changes to routes, controllers, models, middleware
3. **Inertia.js**: No changes to page components or data flow
4. **Authentication**: Fortify integration unchanged
5. **Subscriptions**: Cashier integration unchanged
6. **Database**: No migrations or schema changes

---

## Implementation Checklist

### Pre-Migration
- [x] Analyze lovable-frontend design system
- [x] Document all design tokens and custom styles
- [x] Identify breaking changes
- [ ] Create backup of current [`resources/css/app.css`](resources/css/app.css:1)
- [ ] Create backup of current [`resources/views/app.blade.php`](resources/views/app.blade.php:1)

### Migration Steps
- [ ] Update font links in [`app.blade.php`](resources/views/app.blade.php:39)
- [ ] Update inline background colors in [`app.blade.php`](resources/views/app.blade.php:24)
- [ ] Replace `:root` CSS variables in [`app.css`](resources/css/app.css:92)
- [ ] Replace `.dark` CSS variables in [`app.css`](resources/css/app.css:129)
- [ ] Add custom CSS variables (tags, gradients, glows)
- [ ] Update `@theme inline` block with fonts and shadows
- [ ] Add custom animations (@keyframes)
- [ ] Add custom utility classes
- [ ] Update `@layer base` with heading styles

### Post-Migration Testing
- [ ] Test light mode appearance
- [ ] Test dark mode appearance
- [ ] Test appearance toggle functionality
- [ ] Verify all existing pages render correctly
- [ ] Check authentication flows (login, register, 2FA)
- [ ] Check settings pages (profile, password, appearance)
- [ ] Check subscription/checkout pages
- [ ] Verify responsive design on mobile/tablet
- [ ] Test all interactive elements (buttons, forms, modals)
- [ ] Validate accessibility (contrast ratios, focus states)

---

## Tailwind v4 CSS-First Translation

### Challenge
Lovable-frontend uses Tailwind v3's JavaScript config:
```typescript
// tailwind.config.ts
theme: {
  extend: {
    colors: { ... },
    fontFamily: { ... },
    boxShadow: { ... }
  }
}
```

Laravel uses Tailwind v4's CSS-first approach:
```css
@theme inline {
  --color-primary: ...;
  --font-sans: ...;
}
```

### Solution
Map v3 config to v4 CSS variables:

**Fonts:**
```css
/* v3: theme.fontFamily.sans */
--font-sans: Inter, system-ui, sans-serif;

/* v3: theme.fontFamily.serif */
--font-serif: Merriweather, Georgia, serif;
```

**Shadows:**
```css
/* v3: theme.boxShadow.2xs */
--shadow-2xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
```

**Letter Spacing:**
```css
/* v3: theme.letterSpacing.ultra-wide */
--letter-spacing-ultra-wide: 0.2em;
```

**Colors:**
Colors are already CSS variables in both systems, so they translate directly.

---

## Asset Migration

### Images/Assets
**Source**: [`lovable-frontend/src/assets/`](lovable-frontend/src/assets/)
- `chat-bubble-3d.png`
- `hero-ai-companion.jpg`
- `hero-phone-call.png`

**Action**: Copy to `public/` directory if needed for visual consistency.

**Note**: These may be specific to lovable-frontend's content. Only migrate if they're part of the design system (e.g., placeholder images, icons).

---

## Component Compatibility

### Existing Vue Components (No Changes Needed)
The following Vue components will automatically adopt the new styling through Tailwind classes:

- ✅ [`Button.vue`](resources/js/components/ui/button/Button.vue) - Uses same class structure
- ✅ [`Card.vue`](resources/js/components/ui/card/Card.vue) - Uses same class structure
- ✅ [`Input.vue`](resources/js/components/ui/input/Input.vue) - Uses CSS variables
- ✅ [`Alert.vue`](resources/js/components/ui/alert/Alert.vue) - Uses CSS variables
- ✅ All other reka-ui components - Built on same design token system

### Custom Components
- ✅ [`AppHeader.vue`](resources/js/components/AppHeader.vue) - Will inherit new colors
- ✅ [`AppSidebar.vue`](resources/js/components/AppSidebar.vue) - Will use new sidebar colors
- ✅ [`AppLogo.vue`](resources/js/components/AppLogo.vue) - No changes needed

---

## Testing Strategy

### Visual Regression Testing
1. **Before Migration**: Take screenshots of all pages
2. **After Migration**: Compare screenshots
3. **Expected Differences**:
   - Darker overall appearance
   - Rounder corners
   - Different font (Inter/Merriweather vs Instrument Sans)
   - Green accent color vs neutral
   - Warmer color temperature

### Functional Testing
1. **Authentication**: Login, register, password reset, 2FA
2. **Settings**: Profile update, password change, appearance toggle
3. **Subscriptions**: Plan selection, checkout flow
4. **Navigation**: Sidebar, breadcrumbs, user menu
5. **Forms**: All input fields, validation, error states
6. **Modals/Dialogs**: Open/close, focus management
7. **Dark Mode**: Toggle between light/dark/system

---

## Rollback Plan

If issues arise, rollback is simple:

1. **Restore CSS**: Replace [`resources/css/app.css`](resources/css/app.css:1) with backup
2. **Restore Fonts**: Replace font links in [`app.blade.php`](resources/views/app.blade.php:39) with backup
3. **Clear Cache**: Run `php artisan optimize:clear`
4. **Rebuild Assets**: Run `npm run build`

**No database changes**, so rollback is risk-free.

---

## Timeline Estimate

| Phase | Task | Estimated Time |
|-------|------|----------------|
| 1 | Font integration | 5 minutes |
| 2 | CSS variables migration | 15 minutes |
| 3 | Theme configuration | 10 minutes |
| 4 | Custom animations | 10 minutes |
| 5 | Custom utilities | 10 minutes |
| 6 | Base styles update | 5 minutes |
| 7 | Testing & validation | 30 minutes |
| 8 | Documentation | 10 minutes |
| **Total** | | **~95 minutes** |

---

## Success Criteria

### Visual Appearance
- ✅ Application uses dark theme by default (8% lightness background)
- ✅ Headings use Merriweather serif font
- ✅ Body text uses Inter sans-serif font
- ✅ Border radius is 1rem (rounder corners)
- ✅ Accent color is muted green (140 20% 45%)
- ✅ Warm color temperature (36° hue) throughout
- ✅ Custom animations work (fade-in, slide-in, zoom-in, pulse-glow)
- ✅ Custom utility classes available (card-hover, btn-voice-*)

### Functionality
- ✅ All existing Laravel routes work
- ✅ All Vue components render correctly
- ✅ Authentication flows unchanged
- ✅ Subscription/payment flows unchanged
- ✅ Dark mode toggle works
- ✅ No console errors
- ✅ No broken layouts

### Performance
- ✅ No increase in CSS bundle size beyond expected
- ✅ Fonts load efficiently (preconnect, display=swap)
- ✅ No layout shift during font loading

---

## Risk Assessment

### Low Risk ✅
- Font replacement (cosmetic only)
- CSS variable updates (scoped to styling)
- Custom animations (additive, don't break existing)
- Utility classes (optional usage)

### Medium Risk ⚠️
- Dark theme default (major visual change)
  - **Mitigation**: Users can toggle to light mode
  - **Mitigation**: System preference detection still works
- Border radius increase (affects all rounded elements)
  - **Mitigation**: Consistent across all components
  - **Mitigation**: Can be adjusted if needed

### No Risk 🟢
- Backend functionality (untouched)
- Database (no migrations)
- API endpoints (unchanged)
- Business logic (preserved)

---

## Post-Migration Recommendations

### Optional Enhancements
1. **Add Tag Color Utilities**: Create Tailwind utilities for tag colors
   ```css
   .tag-financing { @apply bg-[hsl(var(--tag-financing))]; }
   ```

2. **Create Voice Button Components**: Add Vue components using btn-voice-* classes

3. **Enhance Card Components**: Apply card-hover class to interactive cards

4. **Animation Presets**: Create Vue composables for common animation patterns

### Future Considerations
1. **Component Library Alignment**: Consider migrating more shadcn/ui components to reka-ui
2. **Design System Documentation**: Create Storybook or similar for design tokens
3. **Accessibility Audit**: Verify WCAG compliance with new color scheme
4. **Performance Optimization**: Lazy load fonts, optimize CSS delivery

---

## Files Modified Summary

| File | Type | Changes |
|------|------|---------|
| [`resources/views/app.blade.php`](resources/views/app.blade.php:1) | Blade Template | Font links, background colors |
| [`resources/css/app.css`](resources/css/app.css:1) | CSS | Colors, fonts, animations, utilities |

**Total Files**: 2  
**Lines Changed**: ~150-200 lines

---

## Appendix: Complete CSS Variable Reference

### Color Variables (HSL Format)
```css
/* Light/Dark agnostic */
--background: 0 0% 8%
--foreground: 36 44% 96%
--card: 0 0% 12%
--card-foreground: 36 44% 96%
--popover: 0 0% 8%
--popover-foreground: 36 44% 96%
--primary: 36 44% 96%
--primary-foreground: 0 0% 18%
--secondary: 25 15% 25%
--secondary-foreground: 36 44% 96%
--muted: 0 0% 18%
--muted-foreground: 0 0% 65%
--accent: 140 20% 45%
--accent-foreground: 36 44% 96%
--destructive: 0 62% 50%
--destructive-foreground: 36 44% 96%
--border: 0 0% 18%
--input: 0 0% 18%
--ring: 36 44% 96%
--radius: 1rem

/* Custom tags */
--tag-financing: 210 50% 75%
--tag-lifestyle: 140 20% 70%
--tag-community: 25 35% 60%
--tag-wellness: 280 30% 75%
--tag-travel: 195 50% 70%
--tag-creativity: 330 40% 75%
--tag-growth: 50 45% 70%

/* Special tokens */
--cream: 40 40% 90%
--cream-foreground: 0 0% 18%
--surface-elevated: 36 44% 98%
--shadow-soft: 0 0% 0%

/* Gradients */
--gradient-accent: linear-gradient(135deg, hsl(140, 25%, 42%), hsl(140, 30%, 52%))
--gradient-destructive: linear-gradient(135deg, hsl(0, 65%, 48%), hsl(340, 65%, 52%))

/* Glows */
--glow-accent: 0 0 30px hsla(140, 25%, 45%, 0.4), 0 10px 30px rgba(0, 0, 0, 0.3)
--glow-destructive: 0 0 40px hsla(0, 65%, 50%, 0.5), 0 10px 30px rgba(0, 0, 0, 0.3)
```

---

## Next Steps

Once this plan is approved, I will switch to **Code mode** to implement the migration following this exact specification.

**Estimated Total Time**: ~2 hours (including testing)

**Recommended Approach**: Implement in a feature branch, test thoroughly, then merge to main.