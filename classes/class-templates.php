<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class Collaboration_Templates {
	/**
	 * Returns the path to the FES templates directory
	 *
	 * @since 1.2
	 * @return string
	 */
	public function collaboration_get_templates_dir() {
		return fes_plugin_dir . 'templates';
	}
	
	/**
	 * Returns the URL to the FES templates directory
	 *
	 * @since 1.3.2.1
	 * @return string
	 */
	public function collaboration_get_templates_url() {
		return collaboration_plugin_url . 'templates';
	}
	

	public function collaboration_get_template_part( $slug, $name = null, $load = true ) {
		// Execute code for this part
		do_action( 'get_template_part_' . $slug, $slug, $name );
		// Setup possible parts
		$templates = array();
		if ( isset( $name ) )
			$templates[] = $slug . '-' . $name . '.php';
		$templates[] = $slug . '.php';
		// Allow template parst to be filtered
		$templates   = apply_filters( 'collaboration_get_template_part', $templates, $slug, $name );
		// Return the part that is found
		return EDD_Collaboration()->templates->collaboration_locate_template( $templates, $load, false );
	}
	

	public function collaboration_locate_template( $template_names, $load = false, $require_once = true ) {
		// No file found yet
		$located = false;
		// Try to find a template file
		foreach ( (array) $template_names as $template_name ) {
			// Continue if template is empty
			if ( empty( $template_name ) )
				continue;
			// Trim off any slashes from the template name
			$template_name = ltrim( $template_name, '/' );
			// Check child theme first
			if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'collaboration_templates/' . $template_name ) ) {
				$located = trailingslashit( get_stylesheet_directory() ) . 'collaboration_templates/' . $template_name;
				break;
				// Check parent theme next
			} elseif ( file_exists( trailingslashit( get_template_directory() ) . 'collaboration_templates/' . $template_name ) ) {
				$located = trailingslashit( get_template_directory() ) . 'collaboration_templates/' . $template_name;
				break;
				// Check theme compatibility last
			} elseif ( file_exists( trailingslashit( EDD_FES()->templates->collaboration_get_templates_dir() ) . $template_name ) ) {
				$located = trailingslashit( EDD_FES()->templates->collaboration_get_templates_dir() ) . $template_name;
				break;
			}
		}
		if ( ( true == $load ) && !empty( $located ) )
			load_template( $located, $require_once );
		return $located;
	}
	
	/**
	 * Returns the template directory name.
	 *
	 * Themes can filter this by using the FES_templates_dir filter.
	 *
	 * @since 1.6.2
	 * @return string
	 */
	public function collaboration_get_theme_template_dir_name() {
		return trailingslashit( apply_filters( 'collaboration_templates_dir', 'collaboration_templates' ) );
	}
}