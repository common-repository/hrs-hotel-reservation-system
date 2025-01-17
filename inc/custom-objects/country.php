<?php
if( !class_exists('MHRS_Country') ){
	class MHRS_Country{
		private static $_instance = null;
		private $post_type = null;
		private $post_rewrite_slug = null;
		
		public function __construct(){
			$this->post_type = MHRS_PREFIX . 'country';
			$this->post_rewrite_slug = 'country';
			add_action( 'init', array( $this, 'register_custom_post_type' ), 0 );
			
			$this->add_meta_boxes();
			$this->save_meta_boxes();
			
			/* custom column, start */
			add_filter('manage_'.$this->post_type.'_posts_columns' , array( $this, 'add_custom_columns' ) );
			/* custom column, start */
		}
		
		public static function get_instance(){
			if( is_null( self::$_instance ) ){
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
		
		/* register custom post type function, start */
		function register_custom_post_type() {
			$labels = array(
				'name'					=>	_x( 'Countries', 'Post Type General Name', 'mhrs' ),
				'singular_name'			=>	_x( 'Country', 'Post Type Singular Name', 'mhrs' ),
				'menu_name'				=>	__( 'Countries', 'mhrs' ),
				'name_admin_bar'		=>	__( 'Countries', 'mhrs' ),
				'parent_item_colon'		=>	__( 'Parent Country:', 'mhrs' ),
				'all_items'				=>	__( 'Countries', 'mhrs' ),
				'add_new_item'			=>	__( 'Add New Country', 'mhrs' ),
				'add_new'				=>	__( 'Add New Country', 'mhrs' ),
				'new_item'				=>	__( 'New Country', 'mhrs' ),
				'edit_item'				=>	__( 'Edit Country', 'mhrs' ),
				'update_item'			=>	__( 'Update Country', 'mhrs' ),
				'view_item'				=>	__( 'View Country', 'mhrs' ),
				'search_items'			=>	__( 'Search Country', 'mhrs' ),
				'not_found'				=>	__( 'Country Not found', 'mhrs' ),
				'not_found_in_trash'	=>	__( 'Country Not found in Trash', 'mhrs' ),
			);
			
			$rewrite = array(
				'slug'			=>	$this->post_rewrite_slug,
				'with_front'	=>	true,
				'pages'			=>	true,
				'feeds'			=>	true,
			);
			
			$args = array(
				'label'					=>	__( 'Countries', 'mhrs' ),
				'description'			=>	__( 'A Country', 'mhrs' ),
				'labels'				=>	$labels,
				'supports'				=>	array( 'title', 'thumbnail', ),
				'taxonomies'			=>	array(),
				'hierarchical'			=>	false,
				'public'				=>	true,
				'show_ui'				=>	true,
				'show_in_menu'			=>	'mhrs',
				'menu_position'			=>	5,
				'show_in_admin_bar'		=>	false,
				'show_in_nav_menus'		=>	true,
				'can_export'			=>	true,
				'has_archive'			=>	false,
				'exclude_from_search'	=>	true,
				'publicly_queryable'	=>	false,
				'rewrite'				=>	$rewrite,
				'capability_type'		=>	'post',
			);
			
			register_post_type( $this->post_type, $args );
		}
		/* register custom post type function, end */
		
		
		/* adding meta boxes */
		function add_meta_boxes(){
			add_action( 'add_meta_boxes_' . $this->post_type, array( $this, 'register_details' ) );
			$this->add_gallery_meta_box();
		}
		
		/* saving meta boxes */
		function save_meta_boxes(){
			add_action( 'save_post_' . $this->post_type, array( $this, 'save_details' ) );
		}
		
		function register_details( $post_type ) {
			add_meta_box(
				MHRS_PREFIX .'country_details',
				__( 'About Country', 'mhrs' ),
				array( $this, 'render_details' ),
				$this->post_type,
				'advanced',
				'high'
			);
		}
		
		function save_details( $post_id ){
			
			/* validation checks, start */
			if( !wp_verify_nonce( $_POST['prevent_delete_meta_movetotrash'], MHRS_MKP . $post_id ) ) { return; }
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return;}
			if( defined('DOING_AJAX') && DOING_AJAX ) { return; }
			if( !isset( $_POST[ MHRS_MKP . 'meta_box_nonce' ] ) ) { return; }
			if( !wp_verify_nonce( $_POST[ MHRS_MKP . 'meta_box_nonce' ], MHRS_MKP . 'save_meta_data' ) ) { return; }
			if( !isset( $_POST['post_type'] ) ) { return; }
			if( !( $this->post_type == $_POST['post_type'] ) ) { return; }
			if( !current_user_can( 'edit_post', $post_id ) ) { return; }
			/* validation checks, start */
		}
		
		function render_details(){
			global $post;
			$post_id = $post->ID;
			
			/* core content */
			$data = array(
				'long_description'		=>	'',
			);
			
			$long_description			=	$post->post_content;
			$data['long_description']	=	htmlspecialchars_decode( $long_description );
			
			$wpe_longdesc_settings = array(
				'textarea_name'		=>	'content',
				'drag_drop_upload'	=>	false,
				'editor_css'		=>	'<style>#wp-content-editor-container .wp-editor-area{height:300px; width:100%;}</style>',
			);
			
			wp_editor( $data['long_description'], 'content', $wpe_longdesc_settings );
			?>
			<input type="hidden" name="prevent_delete_meta_movetotrash" id="prevent_delete_meta_movetotrash" value="<?php echo wp_create_nonce( MHRS_MKP . $post_id ); ?>" />
			<?php
			wp_nonce_field( MHRS_MKP . 'save_meta_data', MHRS_MKP . 'meta_box_nonce' );
		}
		
		function add_gallery_meta_box(){
			$css = MHRS_PLUGIN_URL .'/assets/css/gallery-meta-box.css';
			$js = MHRS_PLUGIN_URL .'/assets/js/gallery-meta-box.js';
			new GIMBGallery( array( $this->post_type ), $css, $js );
		}
		
		/* adding custom columns, start */
		function add_custom_columns( $columns ) {
			unset( $columns['date'] );
			$columns['title'] = __( 'Country', 'mhrs' );
			
			return $columns;
		}
		/* adding custom columns, end */
		
	} /* class end */
}
MHRS_Country::get_instance();