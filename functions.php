<?php

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}



/*
  Enqueue Scripts and Styles
  **************************************************************/

if( !is_admin()){
  // JS
  wp_deregister_script('jquery');
  wp_enqueue_script('jquery', get_bloginfo('url')."/wp-includes/js/jquery/jquery.js", false, '1.9.1', true);
  wp_enqueue_script('app', get_bloginfo('template_directory')."/js/app.js", false, '1.0', true);
  wp_enqueue_script('modernizr', get_bloginfo('template_directory')."/js/vendor/modernizr-2.6.2.min.js", true, '2.6.2', false);
  wp_enqueue_script( array('modernizr','jquery','backbone','underscore','app') );
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