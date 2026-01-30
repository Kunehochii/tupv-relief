# TABANG - Donor Portal Design Guide

> This document provides design specifications for the TABANG Relief Donation Platform, ensuring consistent styling across all pages matching the landing page design.

---

## Brand Colors

Use the CSS custom properties defined in the color palette:

```css
:root {
    --dark-blue: #000167;
    --red: #dd3319;
    --vivid-red: #e51d00;
    --orange: #ffae44;
    --gray-blue: #8a95b6;
    --gray: #e6e6e4;
    --vivid-orange: #ea4f2d;
}
```

### Color Usage

| Color         | Hex       | Usage                                              |
| ------------- | --------- | -------------------------------------------------- |
| Dark Blue     | `#000167` | Brand text, primary buttons, headings, stat values |
| Red           | `#dd3319` | Accents, alerts                                    |
| Vivid Red     | `#e51d00` | Section titles, progress bars, CTA highlights      |
| Orange        | `#ffae44` | Warnings, secondary accents                        |
| Gray Blue     | `#8a95b6` | Secondary text, labels, muted content              |
| Gray          | `#e6e6e4` | Backgrounds, dividers, disabled states             |
| Vivid Orange  | `#ea4f2d` | **Navigation bar background**                      |

---

## Typography

- **Font Family:** `'Poppins', sans-serif`
- **Include via Google Fonts:**

```html
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
```

### Font Weights

| Weight | Usage                           |
| ------ | ------------------------------- |
| 400    | Body text                       |
| 500    | Navigation links, subtle emphasis |
| 600    | Buttons, labels                 |
| 700    | Section titles, card titles     |
| 800    | Brand name (TABANG), large stats |

---

## Navigation Bar (Navbar)

The navbar must match the landing page style. This is a **custom navbar** (not Bootstrap's default).

### HTML Structure

```html
<nav class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ route('home') }}" class="navbar-brand-custom">TABANG</a>
        <div class="d-flex align-items-center">
            <!-- Navigation links go here -->
            <a href="#" class="nav-link-custom">Link Text</a>
            <a href="#" class="nav-link-custom">Another Link</a>
        </div>
    </div>
</nav>
```

### CSS Styles

```css
/* Navigation */
.navbar-custom {
    background: var(--vivid-orange);  /* #ea4f2d */
    padding: 1rem 2rem;
    position: absolute;  /* or sticky for scrollable pages */
    width: 100%;
    z-index: 100;
}

.navbar-brand-custom {
    font-weight: 800;
    font-size: 1.8rem;
    color: var(--dark-blue) !important;  /* #000167 */
    text-decoration: none;
}

.nav-link-custom {
    color: #ffffff !important;
    font-weight: 500;
    margin-left: 1.5rem;
    text-decoration: none;
    transition: opacity 0.3s;
}

.nav-link-custom:hover {
    opacity: 0.8;
}
```

### Key Navbar Rules

1. **Background:** Vivid Orange (`#ea4f2d`)
2. **Brand Name:** Dark Blue (`#000167`), weight 800, 1.8rem
3. **Nav Links:** White (`#ffffff`), weight 500, with 1.5rem left margin
4. **Hover Effect:** Reduce opacity to 0.8
5. **Position:** Use `position: absolute` for hero overlays, `position: sticky` for regular pages

---

## Buttons

### Primary Button (Dark Blue)

```css
.btn-primary-custom {
    background: var(--dark-blue);
    border: 2px solid var(--dark-blue);
    color: #ffffff;
    padding: 1.25rem 3rem;
    font-weight: 600;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 1.1rem;
    transition: all 0.3s;
    text-decoration: none;
    text-align: center;
}

.btn-primary-custom:hover {
    background: #000050;
    color: #ffffff;
}
```

### Outline Button (Gray Blue)

```css
.btn-outline-custom {
    background: transparent;
    border: 2px solid var(--gray-blue);
    color: var(--gray-blue);
    padding: 1.25rem 3rem;
    font-weight: 600;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 1.1rem;
    transition: all 0.3s;
    text-decoration: none;
    text-align: center;
}

.btn-outline-custom:hover {
    background: var(--gray-blue);
    color: #ffffff;
}
```

---

## Section Titles

```css
.section-title {
    color: var(--vivid-red);  /* #e51d00 */
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 2rem;
}
```

---

## Cards

### Drive Card

```css
.drive-card {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.drive-card:hover {
    transform: translateY(-5px);
}

.drive-card-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.drive-card-body {
    padding: 1rem;
    background: #ffffff;
}

.drive-card-title {
    font-weight: 700;
    font-size: 1rem;
    color: #333;
    margin-bottom: 0.5rem;
}
```

### Progress Bar

```css
.drive-progress-bar {
    height: 8px;
    background: var(--gray);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.25rem;
}

.drive-progress-fill {
    height: 100%;
    background: var(--vivid-red);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.drive-progress-percent {
    font-size: 0.75rem;
    color: var(--vivid-red);
    font-weight: 600;
}
```

---

## Stats Display

### Large Stats (Accomplishments)

```css
.stat-number {
    font-size: 4rem;
    font-weight: 800;
    color: var(--dark-blue);
    line-height: 1;
}

.stat-label {
    color: var(--gray-blue);
    font-size: 0.9rem;
    margin-top: 0.5rem;
}
```

### Quick Stats Bar (Dashboard)

```css
.quick-stats-bar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.quick-stat {
    text-align: center;
    padding: 10px;
}

.quick-stat .stat-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #000167;
}

.quick-stat .stat-label {
    display: block;
    font-size: 0.85rem;
    color: #8a95b6;
    text-transform: uppercase;
}
```

---

## Page Layout

### Base Structure

```css
body {
    font-family: 'Poppins', sans-serif;
    background-color: #ffffff;
    overflow-x: hidden;
}
```

### Container Padding (Dashboard Pages)

```css
.container-fluid {
    padding: 2rem;
}

@media (min-width: 768px) {
    .container-fluid {
        padding: 2rem 3rem;
    }
}
```

---

## Required External Resources

Include these in the `<head>` section:

```html
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

<!-- Google Fonts (Poppins) -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
```

---

## Example: Full Page with Custom Navbar

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Title - TABANG</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --dark-blue: #000167;
            --red: #dd3319;
            --vivid-red: #e51d00;
            --orange: #ffae44;
            --gray-blue: #8a95b6;
            --gray: #e6e6e4;
            --vivid-orange: #ea4f2d;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
        }
        
        .navbar-custom {
            background: var(--vivid-orange);
            padding: 1rem 2rem;
            width: 100%;
            z-index: 100;
        }
        
        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--dark-blue) !important;
            text-decoration: none;
        }
        
        .nav-link-custom {
            color: #ffffff !important;
            font-weight: 500;
            margin-left: 1.5rem;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        .nav-link-custom:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="navbar-brand-custom">TABANG</a>
            <div class="d-flex align-items-center">
                <a href="{{ route('donor.dashboard') }}" class="nav-link-custom">Dashboard</a>
                <a href="{{ route('donor.pledges.index') }}" class="nav-link-custom">My Pledges</a>
                <a href="{{ route('donor.map') }}" class="nav-link-custom">Map</a>
            </div>
        </div>
    </nav>
    
    <!-- Page Content -->
    <div class="container py-4">
        <!-- Your content here -->
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

## Design Reference Image

The landing page features:

1. **Orange navbar** with "TABANG" brand in dark blue and white navigation links
2. **Map component** showing drive locations with red markers
3. **Disaster photo** in grayscale with rounded corners
4. **Call-to-action banner** with:
   - Orange/red background behind location name
   - "NEEDS YOUR HELP" text in dark blue
   - Brief description text
   - Two buttons: "DONATE" (orange/red) and "PLEDGE" (dark blue)

---

## Mobile Responsiveness

- Navigation should collapse into a hamburger menu on mobile
- Cards should stack vertically on small screens
- Stats should display in 2-column grid on mobile
- Buttons should be full-width on small screens

---

## AI Agent Instructions

When implementing new pages or components:

1. **Always use the custom navbar** (`.navbar-custom`) instead of Bootstrap's default navbar
2. **Use the defined color variables** from `:root`
3. **Use Poppins font** for all text
4. **Follow the button styles** - primary (dark blue) and outline (gray blue)
5. **Apply card hover effects** with subtle shadow and transform
6. **Use section-title class** for headings with vivid-red color

---

_This document should be referenced when creating or modifying any user-facing pages to maintain design consistency._
