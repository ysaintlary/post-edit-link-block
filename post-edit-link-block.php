<?php
/**
 * Plugin Name:       Post Edit Link
 * Plugin URI:        https://github.com/ysaintlary/post-edit-link-block
 * Description:       Ajoute un lien « Modifier » à côté du lien « Ouvrir » dans les extraits d'articles, visible uniquement pour les utilisateurs connectés ayant les droits d'édition.
 * Version: 1.1.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Author:            Yves Saint-Lary
 * Author URI:        https://ysaintlary.com
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       post-edit-link-block
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'POST_EDIT_LINK_BLOCK_VERSION', '1.1.0' );

add_action( 'wp_enqueue_scripts', function () {
	if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
		wp_add_inline_style( 'wp-block-post-excerpt', '.pelb-edit-link { margin-left: 1rem; }' );
	}
} );

add_filter( 'render_block_core/post-excerpt', function ( $block_content ) {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
		return $block_content;
	}

	$post_id   = get_the_ID();
	$edit_link = get_edit_post_link( $post_id );
	if ( ! $edit_link ) {
		return $block_content;
	}

	$edit_anchor = sprintf(
		' <a href="%s" class="wp-block-post-excerpt__more-link pelb-edit-link">%s</a>',
		esc_url( $edit_link ),
		esc_html__( 'Modifier', 'post-edit-link-block' )
	);

	$block_content = str_replace( '</p></div>', $edit_anchor . '</p></div>', $block_content );

	return $block_content;
}, 10 );
