# Glow Curated WordPress Theme

A custom WordPress theme that mirrors the Glow Curated static site experience while adding WordPress-native controls for customization, editorial workflows, and Pinterest integrations.

## Features

- **Homepage hero controls**: Manage kicker text, headline, subheadline, and CTA buttons from the Customizer.
- **Brand system settings**: Customize accent, text, and background colors plus heading/body font stacks.
- **Dynamic homepage cards**: Edit the featured categories and value propositions via JSON right in the Customizer (no code edits required).
- **Pinterest tuning**: Toggle manual vs. automatic board hydration and update the username globally.
- **Footer + social panel**: Configure contact email, social URLs, and disclosure text with live previews.
- **Editorial enhancements**: Custom block style for Playfair drop caps, optional “New” badge meta field, and highlight list support via post meta (`_glow_curated_highlights`).
- **Widget-ready footer**: Single sidebar for newsletter embeds or custom content blocks.
- **Newsletter hook**: Filter `glow_curated_newsletter_shortcode` to connect your email provider without touching templates.

## Installation

1. Copy the `glow-curated` folder into `wp-content/themes/`.
2. Activate **Glow Curated** from the WordPress admin Appearance → Themes screen.
3. Visit Appearance → Customize to tailor colors, typography, hero content, social links, and homepage sections.
4. Set a static front page (Pages → Add New) and assign it under Settings → Reading if you want to mirror the current static site homepage.

## Optional Integrations

- **Highlights meta box**: Use a lightweight custom fields plugin (e.g., ACF or Meta Box) to add a repeater stored in `_glow_curated_highlights` for the hero blog card.
- **“New” badge toggle**: Save a boolean meta value `_is_new_badge` (1/0) to flag featured posts.
- **Pinterest hydration**: Set the Customizer hydration mode to `auto` if you want the included `pinterest.js` to run on load.

## Development Notes

- Front-end CSS/JS mirrors the existing static assets located under `assets/` for parity with the original site.
- The theme respects the repository rule of shipping only text assets—no binary screenshots are included.
