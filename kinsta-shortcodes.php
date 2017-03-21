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
				'href'		=> '#',
				'id'		=> '',
				'class'		=> 'green',
				'target'	=> '',
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

	$output = '<p><a href="' . esc_url( $href ) . '" id="' . $id . '" class="button ' . $class . '"' . $link_target . '">' . $label . '</a></p>';
	return $output;
}

function kinsta_button_adv( $atts, $content = null, $tag = '' ){

	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array)$atts, CASE_LOWER );

	extract( shortcode_atts(
			array(
				'href'		=> '#',
				'id'		=> '',
				'class'		=> 'green',
				'target'	=> ''
			),
			$atts,
			'kinsta_btn'
		) );

	if( in_array( $target, array( '_blank', '_self', '_parent', '_top' ) ) ){
		$link_target = ' target="' . $target . '"';
	}else{
		$link_target = '';
	}

	$output = '<p><a href="' . esc_url( $href ) . '" id="' . $id . '" class="button ' . $class . '"' . $link_target . '">' . $content . '</a></p>';
	return $output;
}

function kinsta_editor_button(){
	if ( wp_script_is( 'quicktags' ) ) {
		?>
		<script type="text/javascript">

		QTags.addButton( 'kbtn', 'k-button', kinsta_print_btn, '', '', 'Kinsta Button', 999 );

		function kinsta_print_btn(){
			var label = prompt( 'Button label:', '' );

			if ( label && label !== '' ) {
				QTags.insertContent('[kinsta_btn_adv href="" id="" class="" target=""]' + label + '[/kinsta_btn_adv]' );
			}
		}
		</script>
		<?php
	}
}
add_action( 'admin_print_footer_scripts', 'kinsta_editor_button' );


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

// Add buttons to TinyMCE toolbar
function kinsta_register_mce_buttons( $buttons ) {
	$buttons[] = 'kinsta';
	return $buttons;
}
add_filter( 'mce_buttons', 'kinsta_register_mce_buttons' );

// Add a custom TinyMCE plugin
function kinsta_register_mce_plugin( $plugin_array ) {
   $plugin_array['kinsta'] = plugins_url( '/mce/kinsta/plugin.js', __FILE__ );
   return $plugin_array;
}
add_filter( 'mce_external_plugins', 'kinsta_register_mce_plugin' );
