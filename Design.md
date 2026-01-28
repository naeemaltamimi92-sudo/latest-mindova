# Mindova Website Design

## Overview
- **Motion Style**: Cinematic depth with liquid 3D transformations and orbital choreography
- **Animation Intensity**: Ultra-Dynamic
- **Technology Stack**: GSAP + ScrollTrigger, Three.js for hero shader, CSS Houdini, Web Animations API

## Brand Foundation

### Colors
- Primary Purple: #5A3DEB
- Primary Light: #D4CBFA
- Primary Dark: #3C28A7
- Accent Indigo: #4f46e5
- Accent Pink: #ec4899
- Text Dark: #1f2937
- Text Light: #6b7280
- White: #ffffff
- Background Light: #F5F5F5
- Success Green: #10b981

### Typography

**Font Families:**
- Primary: "Inter Tight", sans-serif

**Font URLs:**
- Google Fonts: `https://fonts.googleapis.com/css?family=Inter+Tight:100,200,300,regular,500,600,700,800,900`
- Webfont Loader: `https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js`

**Font Sizes & Weights:**
- H1: 4rem (64px), 500 weight, 100% line-height
- H2: 3rem (48px), 500 weight, 100% line-height
- H3: 2.4rem (38px), 500 weight, 100% line-height
- H4: 2rem (32px), 500 weight, 130% line-height
- H5: 1.6rem (26px), 500 weight, 130% line-height
- H6: 1.2rem (19px), 500 weight, 130% line-height
- Body: 1rem (16px), 400 weight, 140% line-height
- Text Large: 1.25rem (20px)
- Text Small: 0.875rem (14px)

### Core Message
Where human expertise meets AI innovation — a platform that transforms how the world solves problems together.

---

## Global Motion System

### Animation Timing

**Easing Library:**
```css
--ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
--ease-in-expo: cubic-bezier(0.7, 0, 0.84, 0);
--ease-elastic: cubic-bezier(0.68, -0.55, 0.265, 1.55);
--ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
--ease-dramatic: cubic-bezier(0.87, 0, 0.13, 1);
--ease-bounce: cubic-bezier(0.34, 1.56, 0.64, 1);
--ease-liquid: cubic-bezier(0.23, 1, 0.32, 1);
```

**Duration Scale:**
- Micro: 150ms (hover states, toggles)
- Fast: 300ms (button interactions)
- Normal: 500ms (element transitions)
- Slow: 800ms (section reveals)
- Dramatic: 1200ms (hero sequences)
- Ambient: 15000ms-25000ms (continuous floating)

**Stagger Patterns:**
- Cascade: 80ms between elements (top-down reveals)
- Ripple: 120ms from center outward
- Wave: 100ms with sine-wave offset
- Random: 50-150ms randomized delays

### Continuous Effects

**Floating Ambient Motion:**
- Primary elements: Gentle Y-axis oscillation (15px range, 8s duration)
- Secondary accents: Subtle rotation (±3°, 12s duration)
- Decorative shapes: Complex orbital paths using CSS offset-path

**Living Gradients:**
- Hero background: Mesh gradient with 4 control points
- Animation: 20s infinite loop, control points drift in circular patterns
- Colors shift between primary purple, accent indigo, and soft pink

### Scroll Engine

**Parallax Configuration:**
- Layer 1 (Background): 0.3x scroll speed
- Layer 2 (Midground): 0.6x scroll speed
- Layer 3 (Content): 1.0x scroll speed (normal)
- Layer 4 (Foreground accents): 1.3x scroll speed

**Pin Points:**
- Hero section: Pin for 50vh additional scroll (content animates within)
- How It Works: Pin for 100vh, cards stack and unstack
- CTA Section: Pin briefly for dramatic scale reveal

**Progress-Driven Animations:**
- Global progress bar: Top of viewport, accent color
- Section indicators: Dots on right edge, fill based on scroll position

---

## Section 1: Navigation

### Layout

**Spatial Composition:**
- Fixed position with backdrop-filter: blur(20px)
- Glassmorphism effect: background: rgba(255, 255, 255, 0.8)
- Border-bottom: 1px solid rgba(90, 61, 235, 0.1)
- Z-index: 1000
- Height: 80px (desktop), 64px (mobile)

**Dynamic Behavior:**
- On scroll > 100px: Compress to 64px height, increase blur
- Logo scales from 1.0 to 0.85 on scroll
- Background opacity increases from 0.8 to 0.95

### Content

**Logo:** Mindova wordmark (SVG)
- **Links:** /

**Navigation Links:**
- **Link 1:** Text: "How it works" → /how-it-works
- **Link 2:** Text: "Challenges" → /challenges
- **Link 3:** Text: "Community" → /community
- **Link 4:** Text: "Success Stories" → /success-stories

**Auth Buttons:**
- **Login:** Secondary button style → /login
- **Get Started:** Primary gradient button → /register

### Motion Choreography

#### Entrance Sequence
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Logo | Fade + Scale | opacity 0→1, scale 0.8→1 | 600ms | 0ms | ease-out-expo |
| Nav Links | Stagger Fade + Slide | opacity 0→1, y: -20→0 | 400ms | 100ms each | ease-out-expo |
| Auth Buttons | Scale Pop | scale 0→1.1→1 | 500ms | 500ms | ease-elastic |

#### Scroll Effects
| Trigger | Element | Effect | Start | End | Values |
|---------|---------|--------|-------|-----|--------|
| 100px scroll | Navbar | Compress | 0px | 100px | height: 80→64px |
| 100px scroll | Background | Solidify | 0px | 100px | opacity: 0.8→0.95 |
| 100px scroll | Logo | Shrink | 0px | 100px | scale: 1→0.85 |

#### Interaction Effects

**Logo Hover:**
- Subtle glow: box-shadow spreads with purple pulse
- Scale: 1.05 over 200ms

**Link Hover:**
- Underline draws from center: width 0→100%, 250ms
- Color shifts to primary purple
- Y-offset: -2px lift

**Button Hover (Get Started):**
- Gradient animates position (background-position shift)
- Scale: 1.02
- Box-shadow expands with colored glow

**Mobile Menu Toggle:**
- Hamburger lines morph to X with rotation
- Menu slides from right with backdrop blur
- Links stagger in from right (80ms delay each)

---

## Section 2: Hero

### Layout

**Revolutionary Spatial Design:**
- Full viewport height (100vh) with scroll-triggered content choreography
- Asymmetric content placement: Text block offset to left (15% from edge)
- Floating accent elements orbit around content on offset-paths
- Depth layers: Background shader → Gradient overlays → Content → Floating accents

**Grid Structure:**
```css
display: grid;
grid-template-columns: 1fr 1fr;
grid-template-rows: 1fr;
align-items: center;
padding: 0 8%;
```

**Visual Architecture:**
- Left column: Headline, subtext, CTA buttons, stats row
- Right column: Abstract floating shapes (decorative, no image)
- Background: Animated mesh gradient shader with noise texture

### Content

**Headline (Split for Animation):**
- Line 1: "Where Human Expertise"
- Line 2: "Meets AI Innovation"

**Subheadline:**
"Join a global community of problem-solvers. Submit challenges, collaborate with top talent, and build solutions that matter."

**CTA Buttons:**
- Primary: "Start Solving" → /register
- Secondary: "Explore Challenges" → /challenges

**Stats Row:**
| Value | Label |
|-------|-------|
| 2,486 | Active Projects |
| 4,953 | Expert Solvers |
| 86 | Countries |

### Advanced Effects

#### Shader Background (WebGL)

**Mesh Gradient Shader:**
```glsl
// Animated mesh gradient with 4 moving control points
// Colors: #5A3DEB, #4f46e5, #ec4899, #D4CBFA
// Noise overlay for texture
// Animation: 20s infinite loop
```

**Parameters:**
- Color stops: 4 points with smooth interpolation
- Noise scale: 2.5
- Animation speed: 0.0003
- Blend mode: soft-light with base gradient

#### 3D Elements

**Floating Orbs (Right Column):**
- 5 spherical gradient orbs at varying depths
- Z-depth range: -200px to 100px
- Parallax response to mouse position (subtle, 20px max)
- Continuous slow rotation and floating

**Orb Specifications:**
| Orb | Size | Color | Depth | Animation |
|-----|------|-------|-------|-----------|
| 1 | 200px | Primary purple gradient | -100px | Float Y ±30px, 10s |
| 2 | 120px | Indigo to pink | -50px | Float Y ±20px, 8s |
| 3 | 80px | Light purple | 0px | Float Y ±15px, 12s |
| 4 | 60px | Pink accent | 50px | Float Y ±10px, 6s |
| 5 | 40px | White glow | 100px | Float Y ±8px, 7s |

### Motion Choreography

#### Entrance Sequence (Pinned)
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Shader BG | Fade In | opacity 0→1 | 1200ms | 0ms | ease-smooth |
| Headline Line 1 | Clip Reveal + Rise | clipPath reveal, y: 60→0 | 800ms | 400ms | ease-out-expo |
| Headline Line 2 | Clip Reveal + Rise | clipPath reveal, y: 60→0 | 800ms | 550ms | ease-out-expo |
| Subheadline | Fade + Rise | opacity 0→1, y: 40→0 | 600ms | 800ms | ease-out-expo |
| Primary CTA | Scale Pop | scale 0.8→1.05→1 | 500ms | 1000ms | ease-elastic |
| Secondary CTA | Fade Slide | opacity 0→1, x: -20→0 | 400ms | 1100ms | ease-out-expo |
| Stats Row | Stagger Rise | opacity 0→1, y: 30→0 | 400ms | 100ms stagger | ease-out-expo |
| Orbs | Scale + Fade | scale 0→1, opacity 0→1 | 1000ms | 600ms | ease-elastic |

#### Scroll Effects (Pinned Section)
| Trigger | Element | Effect | Start | End | Values |
|---------|---------|--------|-------|-----|--------|
| 0-30vh | Headline | Parallax Up | 0vh | 30vh | y: 0→-50px, opacity: 1→0.3 |
| 0-30vh | Subheadline | Parallax Up | 0vh | 30vh | y: 0→-30px, opacity: 1→0 |
| 0-50vh | Orbs | Scatter | 0vh | 50vh | spread outward, scale: 1→1.3 |
| 20-50vh | CTAs | Fade Out | 20vh | 50vh | opacity: 1→0 |
| 0-50vh | Shader | Intensity Shift | 0vh | 50vh | saturation increase |

#### Continuous Animations

**Orbital Motion:**
- Orbs follow elliptical paths using offset-path
- Path size varies per orb (200-400px radius)
- Duration: 15-25s per complete orbit
- Direction alternates (clockwise/counter-clockwise)

**Glow Pulses:**
- Each orb has subtle box-shadow pulse
- Shadow expands/contracts over 4s
- Creates "breathing" effect

#### Interaction Effects

**Mouse Parallax (CSS Custom Properties):**
```css
.hero-container {
  --mouse-x: 0;
  --mouse-y: 0;
}
.orb {
  transform: translate(
    calc(var(--mouse-x) * 20px),
    calc(var(--mouse-y) * 20px)
  );
}
```

**CTA Magnetic Effect (CSS :hover):**
- On hover, button attracted to cursor (max 8px displacement)
- Achieved via transform on :hover state
- Spring-back on mouse leave

### Responsive Behavior

**Desktop (>991px):**
- Full shader background with all orbs
- Two-column layout
- All animations active

**Tablet (768-991px):**
- Simplified shader (reduced complexity)
- Orbs reduced to 3, smaller sizes
- Single column, centered content

**Mobile (<768px):**
- Static gradient background (no shader)
- Orbs removed for performance
- Stacked layout, reduced motion

---

## Section 3: Logo Ecosystem

### Layout

**Orbital Carousel Design:**
- Full-width section with overflow hidden
- Logos arranged on invisible circular path
- Continuous rotation animation
- Pause on hover with smooth deceleration

**Spatial Composition:**
```css
.logo-track {
  display: flex;
  gap: 80px;
  animation: orbit 30s linear infinite;
}
```

### Content

**Section Label:** "Trusted by innovative teams worldwide"

**Company Logos (SVG, monochrome):**
- Acme Corp
- GlobalTech
- InnovateLabs
- FutureSystems
- TechVentures
- DataFlow
- CloudNine
- SmartSolutions

*Note: Each logo duplicated for seamless infinite scroll*

### Motion Choreography

#### Entrance Sequence
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Section Label | Fade + Rise | opacity 0→1, y: 20→0 | 500ms | 0ms | ease-out-expo |
| Logo Track | Fade In | opacity 0→1 | 800ms | 200ms | ease-smooth |

#### Continuous Animations

**Infinite Scroll:**
- Track translates X: 0 → -50% (seamless loop)
- Duration: 30s linear
- Pause on hover with 500ms deceleration

**Individual Logo Hover:**
- Grayscale → Full color transition
- Scale: 1.1
- Y-offset: -5px
- Other logos dim slightly (opacity 0.5)

#### Scroll Effects
| Trigger | Element | Effect | Start | End | Values |
|---------|---------|--------|-------|-----|--------|
| Section enter | Track | Speed up | 0% | 50% | animation-duration: 30s→20s |
| Section center | Track | Normal speed | 50% | 100% | 30s |

### Advanced Effects

**Edge Fades:**
- Left edge: gradient mask from transparent to white
- Right edge: gradient mask from white to transparent
- Creates "emerging from mist" effect

**Hover Pause with Momentum:**
```css
.logo-track:hover {
  animation-play-state: paused;
  /* Smooth deceleration handled by browser */
}
```

---

## Section 4: How It Works

### Layout

**Stacked Card Reveal:**
- Section pins for 100vh of scroll
- Cards stack on top of each other initially
- As user scrolls, cards unstack and spread
- Final state: 3 cards in horizontal row

**Spatial Composition:**
```css
.how-it-works-container {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 40px;
  perspective: 1000px;
}
```

**Card Depth Arrangement:**
- Initial: All cards centered, stacked with 20px Z-offset each
- Final: Cards spread horizontally with subtle 3D rotation

### Content

**Section Header:**
- Eyebrow: "The Process"
- Headline: "How Mindova Works"
- Subtext: "Three simple steps to transform your challenges into solutions"

**Step Cards:**

| Step | Title | Description | Icon |
|------|-------|-------------|------|
| 01 | Submit Your Challenge | Define the problem you're facing. Our AI analyzes your requirements and categorizes the challenge for optimal matching. | File-plus icon |
| 02 | AI Matches Experts | Our intelligent system identifies the perfect mix of skills from our global network of verified problem-solvers. | Users icon |
| 03 | Collaborate & Solve | Work together in a secure environment with built-in tools, NDAs, and progress tracking until your solution is ready. | Lightbulb icon |

### Motion Choreography

#### Entrance Sequence
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Eyebrow | Fade + Letter spacing | opacity 0→1, spacing: 0.5em→0.2em | 600ms | 0ms | ease-out-expo |
| Headline | Split word reveal | Each word rises with 80ms stagger | 600ms | 200ms | ease-out-expo |
| Subtext | Fade + Rise | opacity 0→1, y: 30→0 | 500ms | 600ms | ease-out-expo |

#### Scroll Effects (Pinned Section)
| Trigger | Element | Effect | Start | End | Values |
|---------|---------|--------|-------|-----|--------|
| 0-33% | Card 1 | Unstack + Position | stacked | position 1 | rotateY: -5°, x: spread |
| 33-66% | Card 2 | Unstack + Position | stacked | position 2 | rotateY: 0°, x: center |
| 66-100% | Card 3 | Unstack + Position | stacked | position 3 | rotateY: 5°, x: spread |
| 0-100% | All Cards | Depth shift | z: -50 | z: 0 | translateZ increases |

#### Card Hover Effects

**3D Tilt (CSS :hover with transform):**
```css
.step-card:hover {
  transform: perspective(1000px) rotateX(5deg) rotateY(-5deg) translateZ(20px);
  box-shadow: 20px 20px 60px rgba(90, 61, 235, 0.15);
}
```

**Icon Animation:**
- Continuous subtle bounce on idle (2s loop)
- On card hover: Scale 1.2 + rotate 10° + color shift to primary

**Step Number:**
- Large number in background (opacity 0.1)
- On hover: Opacity increases to 0.2, shifts position

#### Continuous Animations

**Connecting Lines:**
- SVG path connects card 1 → 2 → 3
- Path draws itself as cards unstack
- Animated dash-offset creates "flowing data" effect

**Card Idle Float:**
- Each card has subtle independent Y-float
- Range: ±5px, duration: 4-6s (varied per card)

### Advanced Effects

**Number Counter:**
- Step numbers (01, 02, 03) count up on reveal
- Duration: 800ms with ease-out

**Gradient Border:**
- Cards have 1px gradient border on hover
- Gradient animates position (rotating effect)
- Colors: Primary purple → Indigo → Pink → Purple

---

## Section 5: Platform Features

### Layout

**Hexagonal Cluster Grid:**
- 6 feature cards arranged in honeycomb-inspired pattern
- Center card larger (featured), others orbit around
- On mobile: Standard 2-column grid

**Spatial Composition:**
```css
.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-template-rows: repeat(2, auto);
  gap: 30px;
}
/* Center card spans for emphasis */
.feature-card.featured {
  grid-column: 2;
  grid-row: 1 / 3;
  align-self: center;
}
```

### Content

**Section Header:**
- Eyebrow: "Platform"
- Headline: "Everything You Need to Innovate"

**Feature Cards:**

| Feature | Description | Icon |
|---------|-------------|------|
| **AI-Powered Matching** | Intelligent algorithms connect your challenge with the perfect experts in seconds, not weeks. | Zap icon |
| **Global Talent Pool** | Access verified professionals from 86+ countries across every discipline imaginable. | Globe icon |
| **Secure Collaboration** | Enterprise-grade security with built-in NDAs, encrypted communications, and IP protection. | Shield-check icon |
| **Real-time Analytics** | Track progress, measure impact, and gain insights with comprehensive dashboards. | Bar-chart-3 icon |
| **Smart Workflows** | Automated task assignment, deadline management, and milestone tracking. | Git-branch icon |
| **Verified Results** | Blockchain-backed certificates and reputation scoring you can trust. | Award icon |

### Motion Choreography

#### Entrance Sequence
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Section Header | Rise + Fade | y: 50→0, opacity 0→1 | 700ms | 0ms | ease-out-expo |
| Featured Card | Scale + Rotate In | scale: 0.5→1, rotate: -10°→0 | 800ms | 200ms | ease-elastic |
| Orbital Cards | Spiral In | From off-screen positions, spiral to grid | 600ms | 100ms stagger | ease-out-expo |

#### Scroll Effects
| Trigger | Element | Effect | Start | End | Values |
|---------|---------|--------|-------|-----|--------|
| Section 0-100% | All Cards | Stagger Reveal | 0% | 100% | Cards reveal in spiral pattern |
| Section 50-100% | Featured Card | Scale Pulse | 50% | 100% | Subtle scale 1→1.02→1 |

#### Card Interactions

**Hover State (CSS :hover):**
```css
.feature-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 30px 60px rgba(90, 61, 235, 0.2);
  border-color: rgba(90, 61, 235, 0.3);
}
.feature-card:hover .feature-icon {
  transform: scale(1.15) rotate(5deg);
  background: linear-gradient(135deg, #5A3DEB, #4f46e5);
}
```

**Icon Micro-animations:**
- Each icon has subtle continuous animation
- Zap: Occasional flash effect
- Globe: Slow rotation
- Shield: Pulsing glow
- Chart: Bars grow/shrink subtly
- Branch: Nodes pulse sequentially
- Award: Sparkle effect

#### Continuous Animations

**Featured Card Glow:**
- Radial gradient glow behind featured card
- Pulses slowly (4s cycle)
- Color: Primary purple with 30% opacity

**Connection Lines (SVG):**
- Dashed lines connect cards in network pattern
- Animate dash-offset for "data flow" effect
- Only visible on desktop

### Advanced Effects

**Glassmorphism Cards:**
- Background: rgba(255, 255, 255, 0.7)
- Backdrop-filter: blur(10px)
- Border: 1px solid rgba(255, 255, 255, 0.5)
- Creates depth through translucency

**Gradient Text on Hover:**
- Card titles shift to gradient on hover
- Gradient: Primary purple → Indigo
- Transition: 300ms

---

## Section 6: Testimonials

### Layout

**3D Carousel with Depth:**
- Active testimonial centered and scaled up
- Previous/next testimonials visible at edges, scaled down and blurred
- Smooth 3D rotation transition between slides

**Spatial Composition:**
```css
.testimonial-carousel {
  perspective: 1500px;
  height: 500px;
  position: relative;
}
.testimonial-slide {
  position: absolute;
  transform-style: preserve-3d;
  transition: all 0.8s var(--ease-out-expo);
}
.testimonial-slide.active {
  transform: translateZ(100px) scale(1);
  opacity: 1;
}
.testimonial-slide.prev,
.testimonial-slide.next {
  transform: translateX(-60%) translateZ(-100px) scale(0.8) rotateY(25deg);
  opacity: 0.5;
  filter: blur(2px);
}
```

### Content

**Section Header:**
- Eyebrow: "Success Stories"
- Headline: "Loved by Teams Worldwide"

**Testimonials:**

| Name | Role | Quote | Avatar |
|------|------|-------|--------|
| Sarah Chen | VP of Engineering, TechCorp | "Mindova reduced our time-to-solution by 70%. The quality of experts and the AI matching is simply incredible." | SC |
| Marcus Johnson | CTO, InnovateLabs | "We've solved challenges that were stalled for months. The collaborative environment drives real innovation." | MJ |
| Elena Rodriguez | Head of R&D, FutureSystems | "The security features give us confidence to share sensitive challenges. It's enterprise-ready from day one." | ER |
| David Park | Founder, StartupX | "As a startup, we get access to talent we could never afford otherwise. Mindova levels the playing field." | DP |

### Motion Choreography

#### Entrance Sequence
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Section Header | Rise + Fade | y: 40→0, opacity 0→1 | 600ms | 0ms | ease-out-expo |
| Carousel Container | Fade In | opacity 0→1 | 800ms | 300ms | ease-smooth |
| Active Slide | Scale + Rotate In | scale: 0.7→1, rotateY: 30°→0 | 700ms | 500ms | ease-out-expo |
| Side Slides | Fade In | opacity 0→0.5 | 600ms | 700ms | ease-smooth |

#### Slide Transition
| Element | Animation | Values | Duration | Easing |
|---------|-----------|--------|----------|--------|
| Exiting Slide | Rotate Out + Blur | translateZ: 100→-100, rotateY: 0→-25°, blur: 0→2px | 800ms | ease-out-expo |
| Entering Slide | Rotate In + Focus | translateZ: -100→100, rotateY: 25°→0, blur: 2px→0 | 800ms | ease-out-expo |
| Quote Text | Fade + Rise | opacity: 0→1, y: 20→0 | 500ms | ease-out-expo |

#### Continuous Animations

**Auto-advance:**
- Slides advance every 6 seconds
- Progress indicator bar fills over 6s
- Pause on hover

**Avatar Pulse:**
- Active testimonial avatar has subtle glow pulse
- Creates "speaking" emphasis

#### Interaction Effects

**Navigation Arrows:**
- Hover: Scale 1.1, background fills with primary color
- Click: Quick scale 0.9→1.1→1 bounce

**Quote Marks:**
- Large decorative quote marks in background
- Parallax shift on mouse move (subtle)
- Opacity: 0.1

**Swipe Gestures:**
- Touch-enabled swipe between slides
- Physics-based momentum

### Advanced Effects

**3D Perspective Transforms:**
- Cards use preserve-3d for realistic depth
- Rotation creates "cover flow" style
- Shadows adjust based on Z-position

**Star Rating Animation:**
- Stars fill sequentially on slide enter
- Each star has slight bounce
- Golden gradient fill

---

## Section 7: Global Impact Stats

### Layout

**Parallax Masonry Grid:**
- 4 stat cards in asymmetric arrangement
- Cards have varying heights for visual interest
- Parallax scroll speeds vary per card (creates depth)

**Spatial Composition:**
```css
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: auto;
  gap: 30px;
}
.stat-card:nth-child(1) { transform: translateY(0); }
.stat-card:nth-child(2) { transform: translateY(40px); }
.stat-card:nth-child(3) { transform: translateY(-20px); }
.stat-card:nth-child(4) { transform: translateY(60px); }
```

### Content

**Section Header:**
- Eyebrow: "Our Impact"
- Headline: "Numbers That Speak"

**Statistics:**

| Value | Label | Description |
|-------|-------|-------------|
| 50K+ | Challenges Solved | Complex problems transformed into solutions |
| 98% | Success Rate | Challenges completed to client satisfaction |
| $2.4B | Value Generated | Economic impact through innovation |
| 12K+ | Active Experts | Verified professionals in our network |

### Motion Choreography

#### Entrance Sequence
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Section Header | Rise + Fade | y: 40→0, opacity 0→1 | 600ms | 0ms | ease-out-expo |
| Stat Cards | Stagger Rise | y: 60→0, opacity 0→1 | 700ms | 150ms stagger | ease-out-expo |

#### Number Counter Animation
| Stat | Start Value | End Value | Duration | Delay |
|------|-------------|-----------|----------|-------|
| 50K+ | 0 | 50 | 2000ms | 300ms |
| 98% | 0 | 98 | 1500ms | 450ms |
| $2.4B | 0 | 2.4 | 2000ms | 600ms |
| 12K+ | 0 | 12 | 1800ms | 750ms |

**Counter Details:**
- Numbers use easing for realistic count feel
- Suffixes (K, %, B, +) fade in after count completes
- Dollar sign animates in from left

#### Scroll Effects
| Trigger | Element | Effect | Start | End | Values |
|---------|---------|--------|-------|-----|--------|
| Section scroll | Card 1 | Parallax | 0% | 100% | y: 0→-30px |
| Section scroll | Card 2 | Parallax | 0% | 100% | y: 40→-10px |
| Section scroll | Card 3 | Parallax | 0% | 100% | y: -20→-60px |
| Section scroll | Card 4 | Parallax | 0% | 100% | y: 60→20px |

#### Card Hover Effects

**Lift + Glow:**
```css
.stat-card:hover {
  transform: translateY(-15px) scale(1.02);
  box-shadow: 0 25px 50px rgba(90, 61, 235, 0.15);
}
```

**Number Emphasis:**
- Number scales to 1.1 on hover
- Color shifts to primary purple
- Subtle glow effect

### Advanced Effects

**Animated Background Gradients:**
- Each card has subtle gradient background
- Gradients slowly shift position (20s loop)
- Creates "living" card effect

**Particle Connectors:**
- Faint lines connect the stat cards
- Lines pulse with data flow animation
- Representing the network effect

---

## Section 8: Final CTA

### Layout

**Immersive Full-Screen:**
- 100vh height with dramatic entrance
- Content centered with massive scale
- Background features animated gradient mesh
- Particles float upward like rising innovation

**Spatial Composition:**
```css
.cta-section {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}
.cta-content {
  text-align: center;
  max-width: 800px;
  z-index: 10;
}
```

### Content

**Headline:** "Ready to Transform How You Solve Problems?"

**Subtext:** "Join thousands of companies and experts building the future together. Start your journey today."

**CTA Buttons:**
- Primary: "Get Started Free" → /register
- Secondary: "Talk to Sales" → /contact

**Trust Indicators:**
- "No credit card required"
- "Free 14-day trial"
- "Setup in under 5 minutes"

### Advanced Effects

#### Background Shader (Mesh Gradient)

**Animated Gradient Mesh:**
- 5 control points with primary purple, indigo, and pink
- Points move in slow circular patterns
- 25s animation loop
- Noise texture overlay for depth

**Rising Particles:**
- 30-50 small particles (dots/circles)
- Rise from bottom to top
- Varying speeds and sizes
- Fade out near top
- Creates "energy rising" feeling

### Motion Choreography

#### Entrance Sequence (Pinned)
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Shader BG | Fade In | opacity 0→1 | 1000ms | 0ms | ease-smooth |
| Headline | Split Character Reveal | Each character rises with 20ms stagger | 800ms | 300ms | ease-out-expo |
| Subtext | Fade + Rise | y: 40→0, opacity 0→1 | 600ms | 800ms | ease-out-expo |
| Primary CTA | Scale Pop | scale: 0.5→1.1→1 | 600ms | 1000ms | ease-elastic |
| Secondary CTA | Fade Slide | x: -30→0, opacity 0→1 | 500ms | 1100ms | ease-out-expo |
| Trust Indicators | Stagger Fade | opacity 0→1 | 400ms | 100ms stagger | ease-smooth |
| Particles | Begin Rising | opacity 0→1, animation starts | 1000ms | 500ms | ease-smooth |

#### Scroll Effects (Pinned Section)
| Trigger | Element | Effect | Start | End | Values |
|---------|---------|--------|-------|-----|--------|
| 0-50vh | Headline | Scale + Rise | 0vh | 50vh | scale: 1→1.1, y: 0→-30px |
| 0-50vh | CTAs | Rise | 0vh | 50vh | y: 0→-50px |
| 0-50vh | Shader | Intensify | 0vh | 50vh | saturation +20% |
| 25-75vh | Particles | Accelerate | 25vh | 75vh | animation-duration decreases |

#### Interaction Effects

**Button Magnetic Effect (CSS :hover):**
```css
.cta-button {
  transition: transform 0.3s var(--ease-elastic);
}
.cta-button:hover {
  transform: scale(1.05) translateY(-3px);
  box-shadow: 0 20px 40px rgba(90, 61, 235, 0.3);
}
```

**Ripple on Click:**
- Radial ripple effect from click point
- Primary color, fading outward
- 600ms duration

---

## Section 9: Footer

### Layout

**Layered Footer with Depth:**
- Dark background (#0f0f1a) with subtle grid pattern
- Multiple content zones with clear hierarchy
- Newsletter section with animated input
- Social icons with hover transformations

**Spatial Composition:**
```css
.footer {
  background: linear-gradient(180deg, #1a1a2e 0%, #0f0f1a 100%);
  position: relative;
}
.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 60px;
}
```

### Content

**Brand Column:**
- Mindova logo (white version)
- Tagline: "Where human expertise meets AI innovation"
- Social links: Twitter, LinkedIn, GitHub, Discord

**Link Columns:**

| Product | Company | Resources |
|---------|---------|-----------|
| Features | About | Blog |
| Pricing | Careers | Documentation |
| Security | Press | Help Center |
| Integrations | Partners | API Reference |
| Changelog | Contact | Community |

**Newsletter:**
- Headline: "Stay Updated"
- Subtext: "Get the latest on AI-powered innovation"
- Input placeholder: "Enter your email"
- Button: "Subscribe"

**Bottom Bar:**
- © 2024 Mindova. All rights reserved.
- Links: Privacy Policy, Terms of Service, Cookies

### Motion Choreography

#### Entrance Sequence
| Element | Animation | Values | Duration | Delay | Easing |
|---------|-----------|--------|----------|-------|--------|
| Footer Background | Fade In | opacity 0→1 | 800ms | 0ms | ease-smooth |
| Logo | Fade + Rise | y: 20→0, opacity 0→1 | 500ms | 200ms | ease-out-expo |
| Link Columns | Stagger Rise | y: 30→0, opacity 0→1 | 400ms | 100ms stagger per column | ease-out-expo |
| Newsletter | Slide In | x: 30→0, opacity 0→1 | 500ms | 600ms | ease-out-expo |
| Social Icons | Pop In | scale: 0→1 | 300ms | 50ms stagger | ease-elastic |

#### Link Hover Effects

**Underline Draw:**
- Underline draws from left to right
- 250ms duration
- Color: Primary purple

**Color Shift:**
- Text transitions from gray to white
- 200ms duration

#### Newsletter Interaction

**Input Focus:**
- Border glows with primary color
- Label floats up (if using floating labels)
- Submit button pulses subtly

**Submit Success:**
- Input transforms to checkmark
- Success message fades in
- Confetti burst (subtle, 20 particles)

#### Social Icon Hover

**3D Flip:**
```css
.social-icon:hover {
  transform: rotateY(360deg) scale(1.1);
  color: var(--primary-purple);
}
/* Duration: 600ms */
```

### Advanced Effects

**Grid Pattern Background:**
- Subtle CSS grid pattern overlay
- Very low opacity (0.03)
- Creates tech/AI aesthetic

**Gradient Border on Newsletter:**
- Input has animated gradient border on focus
- Gradient rotates continuously

---

## Technical Implementation Notes

### Required Libraries

**Core Animation:**
- GSAP 3.x with ScrollTrigger plugin
- SplitType for text splitting animations

**3D/Shaders:**
- Three.js (minimal build) for hero shader only
- Custom GLSL shaders for mesh gradients

**Utilities:**
- Intersection Observer (native) for triggering
- Web Animations API for simple transitions
- CSS Custom Properties for dynamic values

### Performance Optimization

**GPU Acceleration:**
```css
.animated-element {
  will-change: transform, opacity;
  transform: translateZ(0);
  backface-visibility: hidden;
}
```

**Scroll Performance:**
- Use passive event listeners: `{ passive: true }`
- Throttle scroll handlers to RAF
- Use CSS scroll-timeline where supported

**Reduced Motion Support:**
```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

**Content Visibility:**
```css
.section {
  content-visibility: auto;
  contain-intrinsic-size: 0 500px;
}
```

### Critical Performance Rules

**Avoid:**
- ❌ Blur filters during scroll
- ❌ Animating width/height/top/left
- ❌ Unthrottled mouse events
- ❌ setState in mousemove (React)
- ❌ RAF + setState loops

**Use Instead:**
- ✅ transform and opacity only
- ✅ CSS :hover for mouse effects
- ✅ Throttled events (16ms minimum)
- ✅ CSS custom properties for mouse tracking
- ✅ requestAnimationFrame without setState

### Browser Support

**Feature Detection:**
```javascript
// Check for scroll-timeline support
if (CSS.supports('animation-timeline', 'scroll()')) {
  // Use native scroll animations
} else {
  // Fallback to GSAP ScrollTrigger
}
```

**Fallback Strategy:**
- Modern browsers: Full experience with all effects
- Older browsers: Graceful degradation to simpler animations
- No-JS: Static design still functional and attractive

---

## Responsive Breakpoints

- **Desktop:** > 1280px — Full experience
- **Laptop:** 992px - 1279px — Simplified shaders
- **Tablet:** 768px - 991px — Reduced 3D, simpler animations
- **Mobile:** < 768px — Essential animations only
- **Small Mobile:** < 480px — Minimal motion, focus on content

### Responsive Animation Adjustments

**Tablet:**
- Reduce particle counts by 50%
- Simplify 3D transforms to 2D
- Disable parallax on some elements

**Mobile:**
- Remove shader backgrounds (use gradients)
- Disable continuous ambient animations
- Keep only entrance animations
- Simplify stagger delays

---

## Animation Value Reference

### Movement Magnitudes
- Entrance slides: 30-60px
- Hover lifts: 5-10px
- Parallax range: 20-100px
- Scale effects: 0.95x - 1.05x
- Rotations: 5° - 15° (subtle), 360° (icons)

### Timing Reference
- Micro interactions: 150-200ms
- Button hovers: 200-300ms
- Card transitions: 300-500ms
- Section reveals: 600-800ms
- Hero sequence: 1000-1500ms

### Easing Reference
- Standard transitions: ease-out-expo
- Bouncy effects: ease-elastic
- Dramatic reveals: ease-dramatic
- Smooth continuous: linear or ease-smooth
