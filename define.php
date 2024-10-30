<?php
//if( function_exists( 'add_action' ) ){
	if(true){
	
	define(	'MHRS_PLUGIN_NAME',					'MHotel Reservation System'														);
	define(	'MHRS_PLUGIN_URI',					'http://mozakdesign.com/'														);
	define(	'MHRS_PLUGIN_DESCRIPTION',			'A Hotel and Room reservation system'											);
	define(	'MHRS_PLUGIN_VERSION',				'1.0.0'																			);
	define(	'MHRS_PLUGIN_AUTHOR',				'Mozak Design'																	);
	define(	'MHRS_PLUGIN_AUTHOR_URI',			'http://mozakdesign.com/'														);
	define(	'MHRS_TEXT_DOMAIN',					'mhrs'																			);
    
	define(	'MHRS_PREFIX',						'mhrs_'																			);
	define(	'MHRS_MKP',							'_mhrs_'																		); /* meta key prefix */
	define(	'MHRS_PLUGIN_PATH',					untrailingslashit( str_replace('\\', '/', plugin_dir_path( __FILE__ ) ) )		);
	define(	'MHRS_PLUGIN_URL',					untrailingslashit( str_replace('\\', '/', plugin_dir_url( __FILE__ ) ) )		);
	define(	'MHRS_CURRENT_THEME_PATH',			untrailingslashit( str_replace('\\', '/', get_template_directory() ) )			);
	define(	'MHRS_CURRENT_THEME_URL',			untrailingslashit( str_replace('\\', '/', get_stylesheet_directory_uri() ) )	);
	define(	'MHRS_CURRENT_CHILD_THEME_PATH',	untrailingslashit( str_replace('\\', '/', get_stylesheet_directory() ) )		);
	define(	'MHRS_CURRENT_CHILD_THEME_URL',		untrailingslashit( str_replace('\\', '/', get_stylesheet_directory_uri() ) )	);
	define(	'MHRS_TEMPLATEPATH',				untrailingslashit( str_replace('\\', '/', MHRS_PLUGIN_PATH . '/templates' ) ) . '/'	);
	define(	'MHRS_OVERRIDE_TEMPLATEPATH',		'mhrs-tpl/' );
	
	/* wordpress plugins dir path */
	if( '' != MHRS_PLUGIN_PATH ){
		$del = 'wp-content/plugins';
		$str = explode( $del, MHRS_PLUGIN_PATH );
		$str = $str[0] . $del;
		define( 'MHRS_PLUGINS_PATH', $str );
	} else {
		define( 'MHRS_PLUGINS_PATH', '' );
	}
	
	/* wordpress plugins dir url */
	if( '' != MHRS_PLUGIN_URL ){
		$del = 'wp-content/plugins';
		$str = explode( $del, MHRS_PLUGIN_URL );
		$str = $str[0] . $del;
		define( 'MHRS_PLUGINS_URL', $str );
	} else {
		define( 'MHRS_PLUGINS_URL', '' );
	}
	
	/* wordpress themes dir path */
	if( '' != MHRS_CURRENT_THEME_PATH ){
		$del = 'wp-content/themes';
		$str = explode( $del, MHRS_CURRENT_THEME_PATH );
		$str = $str[0] . $del;
		define( 'MHRS_CURRENT_THEMES_PATH', $str );
	} else {
		define( 'MHRS_CURRENT_THEMES_PATH', '' );
	}
	
	/* wordpress themes dir url */
	if( '' != MHRS_CURRENT_THEME_URL ){
		$del = 'wp-content/themes';
		$str = explode( $del, MHRS_CURRENT_THEME_URL );
		$str = $str[0] . $del;
		define( 'MHRS_CURRENT_THEMES_URL', $str );
	} else {
		define( 'MHRS_CURRENT_THEMES_URL', '' );
	}
}
