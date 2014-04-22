<?php

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}

if ( function_exists( 'add_image_size' ) ) {
  add_image_size( 'square', 220, 200, true );
}

if ( function_exists( 'register_nav_menus' ) ) {
  register_nav_menus(
    array(
      'main_menu' => 'Main Menu'
    )
  );
}

/*
  General Housekeeping
  **************************************************************/

remove_action('wp_version_check', 'wp_version_check');
remove_action('wp_head', 'wp_generator');
remove_action( 'wp_head', 'wlwmanifest_link');
remove_action( 'wp_head', 'rsd_link');

// Remove rubbish inline styles from header

add_action( 'widgets_init', 'my_remove_recent_comments_style' );
function my_remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'  ) );
}

// Remove default gallery styles

add_filter( 'use_default_gallery_style', '__return_false' );

// Add excerpts to pages

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

/*
  Add Custom Sizes to Media Upload
  **************************************************************/

function my_insert_custom_image_sizes( $sizes ) {
  global $_wp_additional_image_sizes;
  if ( empty($_wp_additional_image_sizes) )
    return $sizes;

  foreach ( $_wp_additional_image_sizes as $id => $data ) {
    if ( !isset($sizes[$id]) )
      $sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
  }

  return $sizes;
}
add_filter( 'image_size_names_choose', 'my_insert_custom_image_sizes' );

/*
  Enqueue Scripts and Styles
  **************************************************************/

if( !is_admin()){
  // JS
  wp_deregister_script('jquery');
  wp_enqueue_script('jquery', get_bloginfo('url')."/wp-includes/js/jquery/jquery.js", false, '1.11.2', true);
  wp_enqueue_script('app', get_bloginfo('template_directory')."/js/app.js", false, '1.0', true);
  wp_enqueue_script('modernizr', get_bloginfo('template_directory')."/js/vendor/modernizr.min.js", true, '2.7.2', false);
  wp_enqueue_script( array('modernizr','jquery','underscore','app') );
  // CSS
  wp_enqueue_script( 'styles', get_bloginfo('template_directory')."/css/style.css", false, '1.0', false );
}

add_action('wp_ajax_nopriv_do_ajax', 'our_ajax_function');
add_action('wp_ajax_do_ajax', 'our_ajax_function');

function our_ajax_function(){
  switch($_REQUEST['fn']){
    case 'get_the_posts':
      $output = get_the_posts();
    break;
    default:
      $output = 'No function specified, check your ajax call';
    break;

  }
  $output=json_encode($output);
  if(is_array($output)){
    print_r($output);   
  }
  else{
    echo $output;
    // this a comment
  }
  die;
}

// ajax functions go here