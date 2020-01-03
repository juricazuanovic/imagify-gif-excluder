<?php
/**
 * Plugin Name: Imagify GIF Excluder
 * Description: This plugin will automatically exclude Gif from being converted as WebP by Imagify plugin.
 * Version:     1.0.0
 * Author:      Jurica Zuanovic
 * License: 	GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: imagify-gif-excluder
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * @since      1.0.0
 * @copyright  Copyright (c) 2020, Jurica Zuanovic
 * @license    GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


function checkForImagify() {
    if ( !is_plugin_active( 'imagify/imagify.php' ) ) {
    ?>
		<div class="notice notice-error">
		    <p><?php _e( 'Warning! To use plugin <strong>Imagify GIF Excluder</strong> you need to install and activate <strong>Imagify</strong> plugin!', 'imagify-gif-excluder' ); ?></p>
		</div>
	<?php
    } 
}
add_action( 'admin_notices', 'checkForImagify' );


function no_webp_for_gif( $response, $process, $file, $thumb_size, $optimization_level, $webp, $is_disabled ) {
	if ( ! $webp || $is_disabled || is_wp_error( $response ) ) {
		return $response;
	}
	if ( 'image/gif' !== $file->get_mime_type() ) {
		return $response;
	}
	return new \WP_Error( 'no_webp_for_gif', __( 'Webp version of gif is disabled by filter.' ) );
}
add_filter('imagify_before_optimize_size', 'no_webp_for_gif', 10, 7);
