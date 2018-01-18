<?php
namespace PMPro_Customizer\inc\classes;

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );


class Post_Types {

	public function init() {

	}

	public function register_panel() {
		$labels = Post_Types::get_label_defaults();
		$labels['name']                  = _x( 'Home Panels', 'Post Type General Name', 'pmpro-customizer' );
		$labels['singular_name']         = _x( 'Home Panel', 'Post Type Singular Name', 'pmpro-customizer' );
		$labels['all_items']             = __( 'All Home Panels', 'pmpro-customizer' );
		$labels['menu_name']             = __( 'Home Panels', 'pmpro-customizer' );
		$labels['name_admin_bar']        = __( 'Home Panels', 'pmpro-customizer' );
		$labels['add_new_item']        = __( 'Add New Home Panel', 'pmpro-customizer' );

		$args = Post_Types::get_args_defaults();
		$args['label']               = __( 'Home Panels', 'pmpro-customizer' );
		$args['description']         = __( 'Home Panels', 'pmpro-customizer' );
		$args['labels']              = $labels;
		$args['menu_icon']           = 'dashicons-id';
		$args['rewrite']             = array(
			'with_front' => false,
			'slug' => 'panel',
		);
		$args['rest_base']           = __( 'panel', 'pmpro-customizer' );

		register_post_type( 'new_relic_panel', $args );
	}

	private function get_label_defaults() {
		return array(
			'name'                  => _x( 'Pages', 'Post Type General Name', 'pmpro-customizer' ),
			'singular_name'         => _x( 'Page', 'Post Type Singular Name', 'pmpro-customizer' ),
			'menu_name'             => __( 'Pages', 'pmpro-customizer' ),
			'name_admin_bar'        => __( 'Page', 'pmpro-customizer' ),
			'archives'              => __( 'Page Archives', 'pmpro-customizer' ),
			'parent_item_colon'     => __( 'Parent Page:', 'pmpro-customizer' ),
			'all_items'             => __( 'All Pages', 'pmpro-customizer' ),
			'add_new_item'          => __( 'Add New Page', 'pmpro-customizer' ),
			'add_new'               => __( 'Add New', 'pmpro-customizer' ),
			'new_item'              => __( 'New Page', 'pmpro-customizer' ),
			'edit_item'             => __( 'Edit Page', 'pmpro-customizer' ),
			'update_item'           => __( 'Update Page', 'pmpro-customizer' ),
			'view_item'             => __( 'View Page', 'pmpro-customizer' ),
			'search_items'          => __( 'Search Page', 'pmpro-customizer' ),
			'not_found'             => __( 'Not found', 'pmpro-customizer' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'pmpro-customizer' ),
			'featured_image'        => __( 'Featured Image', 'pmpro-customizer' ),
			'set_featured_image'    => __( 'Set featured image', 'pmpro-customizer' ),
			'remove_featured_image' => __( 'Remove featured image', 'pmpro-customizer' ),
			'use_featured_image'    => __( 'Use as featured image', 'pmpro-customizer' ),
			'insert_into_item'      => __( 'Insert into page', 'pmpro-customizer' ),
			'uploaded_to_this_item' => __( 'Uploaded to this page', 'pmpro-customizer' ),
			'items_list'            => __( 'Pages list', 'pmpro-customizer' ),
			'items_list_navigation' => __( 'Pages list navigation', 'pmpro-customizer' ),
			'filter_items_list'     => __( 'Filter pages list', 'pmpro-customizer' ),
		);
	}

	private function get_args_defaults() {
		return array(
			'label'                 => __( 'Page', 'pmpro-customizer' ),
			'description'           => __( 'Page Description', 'pmpro-customizer' ),
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
			'taxonomies'            => array( 'category', 'placement' ),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 25,
			'menu_icon'             => 'dashicons-admin-page',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			// 'has_archive'           => true,
			'has_archive'           => false,
			'rewrite'               => array(
				'with_front' => false,
				'slug' => 'page',
			),
			'exclude_from_search'   => false,
			'query_var'             => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rest_base'             => 'pages',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
	}
}
