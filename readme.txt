=== Post Edit Link ===
Contributors: ysaintlary
Tags: edit, link, excerpt, admin, editor
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds an "Edit" link next to the "Read more" link in post excerpts, visible only to logged-in users with editing capabilities.

== Description ==

Post Edit Link injects a convenient "Modifier" (Edit) link alongside the excerpt's "Read more" link on archive pages, category listings, and the home page.

The link is **only visible to logged-in users** who have the `edit_posts` capability. Visitors never see it.

= Features =

* Automatic injection into `core/post-excerpt` block output
* Respects WordPress capabilities — only editors and above see the link
* Inherits the same CSS class as the "Read more" link for consistent styling
* Lightweight — no JavaScript, no database queries beyond the standard `get_edit_post_link()`
* Translation-ready

== Installation ==

1. Upload the `post-edit-link-block` folder to `/wp-content/plugins/`
2. Activate the plugin through the "Plugins" menu in WordPress
3. Visit your site while logged in — the "Modifier" link appears next to "Read more" in excerpts

== Changelog ==

= 1.1.0 =
* Switched from standalone block to excerpt filter for inline rendering
* Added padding-left for visual separation from the "Read more" link
* Made link text translatable

= 1.0.0 =
* Initial release as a custom Gutenberg block
