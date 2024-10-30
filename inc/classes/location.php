<?php
if( !class_exists( 'MHRS_Location' ) ){
	class MHRS_Location{
		
		public static function get_city( $id ){
			$args = array(
				'p'					=>	$id,
				'post_type'			=>	array( 'mhrs_city' ),
				'post_status'		=>	array( 'publish' ),
				'pagination'		=>	false,
				'posts_per_page'	=>	'-1',
				'order'				=>	'ASC',
				'orderby'			=>	'title',
				'cache_results'		=>	false,
			);
			
			$post = get_posts( $args );
			if( !empty( $post ) && ( 1 == count($post) ) ){
				$p = $post[0];
				$post = array( 'id' => $p->ID, 'slug' => $p->post_name, 'title' => $p->post_title );
				return $post;
			}
			return false;
		}
		
		public static function get_cities(){
			$args = array(
				'post_type'			=>	array( 'mhrs_city' ),
				'post_status'		=>	array( 'publish' ),
				'pagination'		=>	false,
				'posts_per_page'	=>	'-1',
				'order'				=>	'ASC',
				'orderby'			=>	'title',
				'cache_results'		=>	false,
			);
			
			
			$posts = get_posts( $args );
			$locations = array();
			if( is_array( $posts ) && count( $posts) ){
				foreach( $posts as $p ){
					$id = $p->ID;
					$slug = $p->post_name;
					$title = $p->post_title;
					$locations[$id] = array( 'id' => $id, 'slug' => $slug, 'title' => $title );
				}
				
				if( is_array( $locations ) && count( $locations) ){
					return $locations;
				}
			}
			return false;
		}
		
		public static function get_state( $id ){
			$args = array(
				'p'					=>	$id,
				'post_type'			=>	array( 'mhrs_state' ),
				'post_status'		=>	array( 'publish' ),
				'pagination'		=>	false,
				'posts_per_page'	=>	'-1',
				'order'				=>	'ASC',
				'orderby'			=>	'title',
				'cache_results'		=>	false,
			);
			
			$post = get_posts( $args );
			if( !empty( $post ) && ( 1 == count($post) ) ){
				$p = $post[0];
				$post = array( 'id' => $p->ID, 'slug' => $p->post_name, 'title' => $p->post_title );
				return $post;
			}
			return false;
		}
		
		public static function get_states(){
			$args = array (
				'post_type'			=> array( 'mhrs_state' ),
				'post_status'		=>	array( 'publish' ),
				'pagination'		=>	false,
				'posts_per_page'	=>	'-1',
				'order'				=>	'ASC',
				'orderby'			=>	'title',
				'cache_results'		=>	false,
			);
			
			$posts = get_posts( $args );
			$locations = array();
			if( is_array( $posts ) && count( $posts) ){
				foreach( $posts as $p ){
					$id = $p->ID;
					$slug = $p->post_name;
					$title = $p->post_title;
					$locations[$id] = array( 'id' => $id, 'slug' => $slug, 'title' => $title );
				}
				
				if( is_array( $locations ) && count( $locations) ){
					return $locations;
				}
			}
			
			return false;
		}
		
		public static function get_country( $id ){
			$args = array(
				'p'					=>	$id,
				'post_type'			=>	array( 'mhrs_country' ),
				'post_status'		=>	array( 'publish' ),
				'pagination'		=>	false,
				'posts_per_page'	=>	'-1',
				'order'				=>	'ASC',
				'orderby'			=>	'title',
				'cache_results'		=>	false,
			);
			
			$post = get_posts( $args );
			if( !empty( $post ) && ( 1 == count($post) ) ){
				$p = $post[0];
				$post = array( 'id' => $p->ID, 'slug' => $p->post_name, 'title' => $p->post_title );
				return $post;
			}
			return false;
		}
		
		public static function get_countries(){
			$args = array (
				'post_type'			=>	array( 'mhrs_country' ),
				'post_status'		=>	array( 'publish' ),
				'pagination'		=>	false,
				'posts_per_page'	=>	'-1',
				'order'				=>	'ASC',
				'orderby'			=>	'title',
				'cache_results'		=>	false,
			);
			
			$posts = get_posts( $args );
			$locations = array();
			if( is_array( $posts ) && count( $posts) ){
				foreach( $posts as $p ){
					$id = $p->ID;
					$slug = $p->post_name;
					$title = $p->post_title;
					$locations[$id] = array( 'id' => $id, 'slug' => $slug, 'title' => $title );
				}
				
				if( is_array( $locations ) && count( $locations) ){
					return $locations;
				}
			}
			return false;
		}
		
		public static function get_all_locations(){
			$locations = array();
			
			$cities = MHRS_Location::get_cities();
			$states = MHRS_Location::get_states();
			$countries = MHRS_Location::get_countries();
			if( $cities ){
				$locations[] = $cities;
			}
			if( $states ){
				$locations[] = $states;
			}
			if( $countries ){
				$locations[] = $countries;
			}
			
			if( !empty( $locations ) ){
				return $locations;
			}
			
			return false;
		}
	}
}