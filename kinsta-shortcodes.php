<?php
/**
 * @package Kinsta_shortcodes
 * @version 1.0
 */
/*
Plugin Name: Kinsta shortcodes
Plugin URI: http://wordpress.org/extend/plugins/#
Description: This is an example plugin 
Author: Carlo Daniele
Version: 1.0
Author URI: http://carlodaniele.it/en/
*/

/**
 * Adds a hook for a shortcode tag
 *
 * @param string 	$tag 	Shortcode tag to be searched in post content 
 * @param callable 	$func 	Hook to run when shortcode is found 
 * @link https://codex.wordpress.org/Function_Reference/add_shortcode
 */


function kinsta_shortcodes_init(){
	add_shortcode( 'kinsta_btn', 'kinsta_button' );
	add_shortcode( 'kinsta_btn_adv', 'kinsta_button_adv' );
	add_shortcode( 'kinsta_usr', 'kinsta_username' );
}
add_action('init', 'kinsta_shortcodes_init');


/**
 * Register a shortcode
 *
 * @param array $atts Array of shortcode attributes
 */
function kinsta_button( $atts ){

	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array)$atts, CASE_LOWER );
	
	extract( shortcode_atts(
			array(
				'href'		=> '',
				'id'		=> '',
				'class'		=> 'green',
				'target'	=> '',
				'label'		=> 'Button'
			),
			$atts,
			'kinsta_btn'
		) );

	if( in_array( $target, array( '_blank', '_self', '_parent', '_top' ) ) ){
		$link_target = ' target="' . esc_attr( $target ) . '"';
	}else{
		$link_target = '';
	}

	$output = '<p><a href="' . esc_url( $href ) . '" id="' . esc_attr( $id ) . '" class="button ' . esc_attr( $class ) . '"' . $link_target . '>' . esc_attr( $label ) . '</a></p>';
	return $output;
}

function kinsta_button_adv( $atts, $content = null, $tag = '' ){

	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array)$atts, CASE_LOWER );

	extract( shortcode_atts(
			array(
				'href'		=> '',
				'id'		=> '',
				'class'		=> 'green',
				'target'	=> ''
			),
			$atts,
			'kinsta_btn'
		) );

	if( in_array( $target, array( '_blank', '_self', '_parent', '_top' ) ) ){
		$link_target = ' target="' . esc_attr( $target ) . '"';
	}else{
		$link_target = '';
	}


	$output = '<p><a href="' . esc_url( $href ) . '" id="' . esc_attr( $id ) . '" class="button ' . esc_attr( $class ) . '"' . $link_target . '>' . esc_attr( $content ) . '</a></p>';
	return $output;
}

/**
 * Enqueue scripts and styles
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 */
function kinsta_enqueue_scripts() {
	global $post;

	$has_shortcode = has_shortcode( $post->post_content, 'kinsta_btn') || has_shortcode( $post->post_content, 'kinsta_btn_adv');
	
	// see https://codex.wordpress.org/Function_Reference/has_shortcode
	if( is_a( $post, 'WP_Post' ) && $has_shortcode ) {
		// see https://codex.wordpress.org/Function_Reference/plugin_dir_url
		wp_register_style( 'kinsta-stylesheet',  plugin_dir_url( __FILE__ ) . 'css/style.css' );
		// see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
		wp_enqueue_style( 'kinsta-stylesheet' );
	}
}
add_action( 'wp_enqueue_scripts', 'kinsta_enqueue_scripts');

// register TinyMCE buttons
function kinsta_register_mce_buttons( $buttons ) {
	$buttons[] = 'kinsta';
	return $buttons;
}
// add new buttons
add_filter( 'mce_buttons', 'kinsta_register_mce_buttons' );

function kinsta_register_mce_plugin( $plugin_array ) {
   $plugin_array['kinsta'] = plugins_url( '/mce/kinsta/plugin.js', __FILE__ );
   return $plugin_array;
}
// Load the TinyMCE plugin
add_filter( 'mce_external_plugins', 'kinsta_register_mce_plugin' );



/**
 * Register a shortcode
 *
 * @param array $atts Array of shortcode attributes
 */
function kinsta_username( $atts = array() ){

	$id = get_current_user_id();
	
	if ( 0 == $id ) {
		// Not logged in
		return __( 'Guest' );
	} else {
		// Logged in
		$user = get_userdata( $id );
		return $user->user_login;
	}
}

/**
 * Filters all menu item URLs for a #placeholder#. http://stackoverflow.com/questions/11403189/how-to-insert-shortcode-into-wordpress-menu
 *
 * @param WP_Post[] $menu_items All of the nave menu items, sorted for display.
 *
 * @return WP_Post[] The menu items with any placeholders properly filled in.
 */
function kinsta_dynamic_menu_items( $menu_items ) {

	global $shortcode_tags;

	foreach ( $menu_items as $menu_item ) {

		if ( has_shortcode( $menu_item->title, 'kinsta_usr' ) && isset( $shortcode_tags['kinsta_usr'] ) ){

			$menu_item->title = do_shortcode( $menu_item->title, '[kinsta_usr]' );

			if ( 0 == get_current_user_id() ){

				$menu_item->url = wp_login_url();
			
			}
		}
	}
	return $menu_items;
}
add_filter( 'wp_nav_menu_objects', 'kinsta_dynamic_menu_items' );
