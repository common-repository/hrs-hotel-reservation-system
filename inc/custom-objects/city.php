<?php
if( !class_exists('MHRS_City') ){
	class MHRS_City{
		private static $_instance = null;
		private $post_type = null;
		private $post_rewrite_slug = null;
		
		public function __construct(){
			$this->post_type = MHRS_PREFIX . 'city';
			$this->post_rewrite_slug = 'city';
			add_action( 'init', array( $this, 'register_custom_post_type' ), 0 );
			
			$this->add_meta_boxes();
			$this->save_meta_boxes();
			
			/* custom column, start */
			add_filter('manage_'.$this->post_type.'_posts_columns' , array( $this, 'add_custom_columns' ) );
			add_action( 'manage_'.$this->post_type.'_posts_custom_column' , array( $this, 'custom_column_content' ), 10, 2 );
			add_filter( 'manage_edit-'.$this->post_type.'_sortable_columns', array( $this, 'custom_column_sortable' ) );
			add_action( 'load-edit.php', array( $this, 'edit_custom_post_load' ) );
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
				'name'					=>	_x( 'Cities', 'Post Type General Name', 'mhrs' ),
				'singular_name'			=>	_x( 'City', 'Post Type Singular Name', 'mhrs' ),
				'menu_name'				=>	__( 'Cities', 'mhrs' ),
				'name_admin_bar'		=>	__( 'Cities', 'mhrs' ),
				'parent_item_colon'		=>	__( 'Parent City:', 'mhrs' ),
				'all_items'				=>	__( 'Cities', 'mhrs' ),
				'add_new_item'			=>	__( 'Add New City', 'mhrs' ),
				'add_new'				=>	__( 'Add New City', 'mhrs' ),
				'new_item'				=>	__( 'New City', 'mhrs' ),
				'edit_item'				=>	__( 'Edit City', 'mhrs' ),
				'update_item'			=>	__( 'Update City', 'mhrs' ),
				'view_item'				=>	__( 'View City', 'mhrs' ),
				'search_items'			=>	__( 'Search City', 'mhrs' ),
				'not_found'				=>	__( 'City Not found', 'mhrs' ),
				'not_found_in_trash'	=>	__( 'City Not found in Trash', 'mhrs' ),
			);
			
			$rewrite = array(
				'slug'			=>	$this->post_rewrite_slug,
				'with_front'	=>	true,
				'pages'			=>	true,
				'feeds'			=>	true,
			);
			
			$args = array(
				'label'					=>	__( 'Cities', 'mhrs' ),
				'description'			=>	__( 'A City in a State or Country', 'mhrs' ),
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
				MHRS_PREFIX .'city_details',
				__( 'City Details', 'mhrs' ),
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
			
			/* if required fields is not set or empty, stop tempered front end code, start */
			if( !isset( $_POST[ MHRS_MKP . 'country' ] ) ){ return; }
			
			if( isset( $_POST[ MHRS_MKP . 'country' ] ) ){
				$country = trim( $_POST[ MHRS_MKP . 'country' ] );
				if( empty( $country ) ){
					return;
				}
			}
			/* if required fields is not set or empty, stop tempered front end code, end */
			/* validation checks, start */
			
			$data = array(
				'state_id'				=>	'',
				'state'					=>	'',
				'country_id'			=>	'',
				'country'				=>	'',
			);
			
			$data['state_id']			=	get_post_meta( $post_id, MHRS_MKP . 'state_id', true );
			$data['state']				=	get_post_meta( $post_id, MHRS_MKP . 'state', true );
			$data['country_id']			=	get_post_meta( $post_id, MHRS_MKP . 'country_id', true );
			$data['country']			=	get_post_meta( $post_id, MHRS_MKP . 'country', true );
			
			if ( isset( $_POST[ MHRS_MKP . 'state' ] ) ) {
				$state_id = sanitize_text_field( $_POST[ MHRS_MKP . 'state' ] );
				
				/* state is n/a to some regions
				 * so if it is empty then we will assume user intentionally make it blank
				 * and we will set it to empty value
				 * otherwise update new value
				 */
				if( !empty( $state_id ) ){
					$data['state_id'] = $state_id;
					
					$data['state'] = MHRS_Location::get_state( $state_id );
					$data['state'] = $data['state']['title'];
				} else {
					$data['state_id'] = '';
					$data['state'] = '';
				}
			}
			if ( isset( $_POST[ MHRS_MKP . 'country' ] ) ) {
				$country_id = sanitize_text_field( $_POST[ MHRS_MKP . 'country' ] );
				
				if( !empty( $country_id ) ){
					$data['country_id'] = $country_id;
					
					$data['country'] = MHRS_Location::get_country( $country_id );
					$data['country'] = $data['country']['title'];
				}
			}
			
			update_post_meta( $post_id, MHRS_MKP . 'state_id', $data['state_id'] );
			update_post_meta( $post_id, MHRS_MKP . 'state', $data['state'] );
			update_post_meta( $post_id, MHRS_MKP . 'country_id', $data['country_id'] );
			update_post_meta( $post_id, MHRS_MKP . 'country', $data['country'] );
			
			/* neither we need to get nor to update the short_description and long description
			 * because they are post excerpt and post content respectively
			 * and are automatically saved by WordPress in their corresponding fields
			 */
		}
		
		function render_details(){
			global $post;
			$post_id = $post->ID;
			
			/* core content */
			$data = array(
				'state_id'				=>	'',
				'state'					=>	'',
				'country_id'			=>	'',
				'country'				=>	'',
				'long_description'		=>	'',
			);
			
			$data['state_id']			=	get_post_meta( $post_id, MHRS_MKP . 'state_id', true );
			$data['state']				=	get_post_meta( $post_id, MHRS_MKP . 'state', true );
			$data['country_id']			=	get_post_meta( $post_id, MHRS_MKP . 'country_id', true );
			$data['country']			=	get_post_meta( $post_id, MHRS_MKP . 'country', true );
			
			$long_description			=	$post->post_content;
			$data['long_description']	=	htmlspecialchars_decode( $long_description );
			?>
			
			<div class="mhrs_info">
				<span class="dashicons dashicons-info"></span>
				<?php echo __( 'Fields marked with * are required.', 'mhrs' ); ?>
			</div>
			
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="<?php echo MHRS_MKP . 'state'; ?>"><?php echo __( 'State', 'mhrs' ); ?>: </label>
						</th>
						<td>
							<select id="<?php echo MHRS_MKP . 'state'; ?>" name="<?php echo MHRS_MKP . 'state'; ?>">
								<option value="">— <?php _e( 'Select State or N/A', 'mhrs' ); ?> —</option>
								<?php if( $locs = MHRS_Location::get_states() ) { ?>
								<?php foreach( $locs as $key => $val ) { ?>
								<option value="<?php echo $key; ?>" <?php selected( $key, $data['state_id'] ); ?>><?php echo $val['title']; ?></option>
								<?php } ?>
								<?php } ?>
							</select><span class="spinner"></span>
							<br />
							<a target="_blank" href="<?php echo admin_url( 'post-new.php?post_type=' . MHRS_PREFIX . 'state' ); ?>"><?php _e( 'Add new state', 'mhrs' ); ?></a>
							<p class="description"><?php _e( 'If State is not applicable in a region, one can leave the state option untouched. State is optional.', 'mhrs' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo MHRS_MKP . 'country'; ?>">*<?php echo __( 'Country', 'mhrs' ); ?>: </label>
						</th>
						<td>
							<select id="<?php echo MHRS_MKP . 'country'; ?>" name="<?php echo MHRS_MKP . 'country'; ?>"  required="required">
								<option value="">— <?php _e( 'Select Country', 'mhrs' ); ?> —</option>
								<?php if( $locs = MHRS_Location::get_countries() ) { ?>
								<?php foreach( $locs as $key => $val ) { ?>
								<option value="<?php echo $key; ?>" <?php selected( $key, $data['country_id'] ); ?>><?php echo $val['title']; ?></option>
								<?php } ?>
								<?php } ?>
							</select><span class="spinner"></span>
							<br />
							<a target="_blank" href="<?php echo admin_url( 'post-new.php?post_type=' . MHRS_PREFIX . 'country' ); ?>"><?php _e( 'Add new country', 'mhrs' ); ?></a>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label for=""><b><?php _e( 'About City', 'mhrs' ); ?>:</b></label>
							<?php
							$wpe_longdesc_settings = array(
								'textarea_name'		=>	'content',
								'drag_drop_upload'	=>	false,
								'editor_css'		=>	'<style>#wp-content-editor-container .wp-editor-area{height:300px; width:100%;}</style>'
							);
							
							wp_editor( $data['long_description'], 'content', $wpe_longdesc_settings );
							?>
						</td>
					</tr>
				</tbody>
			</table>
			
			<?php /* nonce fields */ ?>
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
			$columns['title'] = __( 'City', 'mhrs' );
			
			$new_columns = array(
				'state'		=>	__( 'State', 'mhrs' ),
				'country'	=>	__( 'Country', 'mhrs' ),
			);
			
			$updated_columns = array_merge( $columns, $new_columns );
			
			return $updated_columns;
		}
		
		function custom_column_content( $column, $post_id ) {
			switch ( $column ) {
				case 'state' :
					$data = get_post_meta( $post_id , MHRS_MKP . 'state', true );
					$state_id = get_post_meta( $post_id , MHRS_MKP . 'state_id', true );
					$data = trim( $data );
					if( !empty( $data ) ){
						echo '<a href="'.admin_url( 'post.php?post='.$state_id.'&action=edit').'">'.$data.'</a>';
					} else {
						echo '';
					}
					
					break;
				
				case 'country' :
					$data = get_post_meta( $post_id , MHRS_MKP . 'country', true );
					$country_id = get_post_meta( $post_id , MHRS_MKP . 'country_id', true );
					$data = trim( $data );
					if( !empty( $data ) ){
						echo '<a href="'.admin_url( 'post.php?post='.$country_id.'&action=edit').'">'.$data.'</a>';
					} else {
						echo '';
					}
					
					break;
			}
		}
		
		function custom_column_sortable( $columns ) {
			$columns['state'] = 'state';
			$columns['country'] = 'country';
			
			return $columns;
		}
		
		function edit_custom_post_load() {
			add_filter( 'request', array( $this, 'sort_column_state' ) );
			add_filter( 'request', array( $this, 'sort_column_country' ) );
		}
		
		function sort_column_state( $vars ) {
			if ( isset( $vars['post_type'] ) && $this->post_type == $vars['post_type'] ) {
				if ( isset( $vars['orderby'] ) && 'state' == $vars['orderby'] ) {
					$vars = array_merge(
						$vars,
						array(
							'meta_key'	=>	MHRS_MKP . 'state',
							'orderby'	=>	'meta_value',
						)
					);
				}
			}
			return $vars;
		}
		
		function sort_column_country( $vars ) {
			if ( isset( $vars['post_type'] ) && $this->post_type == $vars['post_type'] ) {
				if ( isset( $vars['orderby'] ) && 'country' == $vars['orderby'] ) {
					$vars = array_merge(
						$vars,
						array(
							'meta_key'	=>	MHRS_MKP . 'country',
							'orderby'	=>	'meta_value',
						)
					);
				}
			}
						
			return $vars;
		}
		/* adding custom columns, end */
		
	} /* end of class */
}
MHRS_City::get_instance();