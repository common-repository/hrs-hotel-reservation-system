<?php
add_action( 'wp_enqueue_scripts', 'mhrs_fe_style' );
function mhrs_fe_style(){
	wp_register_style( 'mhrs_fe_style', MHRS_PLUGIN_URL . '/assets/css/style.css', array(), false );
	wp_enqueue_style( 'mhrs_fe_style' );
}
add_action( 'wp_enqueue_scripts', 'mhrs_detect_events' );
function mhrs_detect_events(){
	wp_register_script( 'mhrs_detect_events', MHRS_PLUGIN_URL . '/assets/js/detect-event.js', array(), false );
	wp_enqueue_script( 'mhrs_detect_events' );
}
add_action( 'wp_enqueue_scripts', 'mhrs_jquery_ui_css' );
function mhrs_jquery_ui_css(){
	wp_register_style( 'mhrs_jquery_ui_css', MHRS_PLUGIN_URL . '/assets/css/jquery/jquery-ui/jquery-ui.min.css', array(), false );
	wp_enqueue_style( 'mhrs_jquery_ui_css' );
}

add_action( 'wp_enqueue_scripts', 'mhrs_rooms_gallery_css' );
function mhrs_rooms_gallery_css()
{

	$handle		=	'mhrs_admin_rooms_gallery_end_css';
	$url		=	MHRS_PLUGIN_URL . '/assets/css/rooms-image-gallery.css';
	$deps		=	array();	
	wp_register_style( $handle, $url, $deps );
	wp_enqueue_style( $handle );
}

add_action( 'wp_enqueue_scripts', 'mhrs_hotel_search_form_js' );
function mhrs_hotel_search_form_js(){
	
	$handle		=	'mhrs_hotel_search_form_js';
	$src		=	MHRS_PLUGIN_URL . '/assets/js/hotel-search-form.js';
	$deps		=	array(
						'jquery',
						'jquery-ui-core',
						'jquery-ui-datepicker',
					);
	$ver		=	'll';
	$in_footer	=	false;
	
	wp_register_script( $handle, $src, $deps, $ver, $in_footer );
	
	/* localize js, start */
	$admin_ajax_url = admin_url( 'admin-ajax.php' );
	$advanced_booking_limit_option = get_option( 'mhrs_advanced_booking_limit', "-1" );
	$advanced_booking_limit_option = (int) $advanced_booking_limit_option;
	
	$min_date = date( 'F j, Y' );
	$max_date = null;
	$checkindate = date('F j, Y');
	$checkoutdate = strtotime( '+ 1 day', time() );
	$checkoutdate = date( 'F j, Y', $checkoutdate );
		
	if( $advanced_booking_limit_option === -1 ){
		/* leave as default to null for no limit */
	} else if( $advanced_booking_limit_option === 0 ){
		$max_date = date('Y-m-t');
		$max_date = date( 'F j, Y', strtotime( $max_date) );
	} else if( $advanced_booking_limit_option > 0 ){
		$limit_ts = strtotime( '+'.$advanced_booking_limit_option.' months', time() );
		$max_date = date( 'F j, Y', $limit_ts );
	}
	
	$mhrs_hotel_search_form_props	=	array(
											'admin_ajax_url'			=>	$admin_ajax_url,
											'min_date'					=>	$min_date,
											'max_date'					=>	$max_date,
											'advanced_booking_limit'	=>	$advanced_booking_limit_option,
											'checkindate'				=>	$checkindate,
											'checkoutdate'				=>	$checkoutdate,
										);
	/* localize js, end */
	wp_localize_script( $handle, 'mhrs_hotel_search_form_obj', $mhrs_hotel_search_form_props );
	wp_enqueue_script( $handle );
}

add_action( 'wp_enqueue_scripts', 'mhrs_hotel_rooms_gallery_js' );
function mhrs_hotel_rooms_gallery_js()
{
	/* localize js, start */
	$admin_ajax_url = admin_url( 'admin-ajax.php' );
	$handle		=	'mhrs_hotel_rooms_gallery_js';
	$src		=	MHRS_PLUGIN_URL . '/assets/js/rooms-image-gallery.js';
	$deps		=	array('jquery');
	$ver		=	'll';
	$in_footer	=	false;
	wp_register_script( $handle, $src, $deps, $ver, $in_footer );
	/* localize js, start */
	$mhrs_hotel_rooms_gallery_props	=	array(
											'admin_ajax_url'			=>	$admin_ajax_url,
										);
	/* localize js, end */
	wp_localize_script( $handle, 'mhrs_hotel_rooms_gallery_obj', $mhrs_hotel_rooms_gallery_props );
	wp_enqueue_script( $handle );
}