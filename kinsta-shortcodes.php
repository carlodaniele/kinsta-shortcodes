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
add_shortcode( 'kinsta_btn', 'kinsta_button' );	

/**
 * Register a shortcode
 *
 * @param array $atts Array of shortcode attributes
 */
function kinsta_button( $atts ){
	extract( shortcode_atts(
			array(
				'href'		=> '#',
				'id'		=> '',
				'class'		=> 'green',
				'target'	=> '',
				'rel'		=> '',
				'title'		=> '',
				'label'		=> 'Button'
			),
			$atts,
			'kinsta_btn'
		) );

	if( in_array( $target, array( '_blank', '_self', '_parent', '_top' ) ) ){
		$link_target = ' target="' . $target . '"';
	}else{
		$link_target = '';
	}

	$output = '<a href="' . $href . '" id="' . $id . '" class="button ' . $class . '"' . $link_target . '">' . $label . '</a>';
	return $output;
}

/**
 * Enqueue scripts and styles
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 */
function kinsta_enqueue_scripts() {
	global $post;
	// see https://codex.wordpress.org/Function_Reference/has_shortcode
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'kinsta_btn') ) {
		// see https://codex.wordpress.org/Function_Reference/plugin_dir_url
		wp_register_style( 'kinsta-stylesheet',  plugin_dir_url( __FILE__ ) . 'css/style.css' );
		// see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
    	wp_enqueue_style( 'kinsta-stylesheet' );
	}
}
add_action( 'wp_enqueue_scripts', 'kinsta_enqueue_scripts');