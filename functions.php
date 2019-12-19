<?php
/**
 * Hemma Child functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Hemma
 */

/**
 * Enqueue the parent theme style.
 *
 * @link https://codex.wordpress.org/Child_Themes
 */
function hemma_parent_style() {
    wp_enqueue_style( 'hemma-style',
    	get_template_directory_uri() . '/style.css',
    	array('hemma-plugins-style'),
    	wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'hemma_parent_style' );

/**
 * Enqueue the child theme style.
 * If your child theme style.css contains actual CSS code, use the code below to enqueue it.
 *
 * @link https://codex.wordpress.org/Child_Themes
 *
 * function hemma_child_style() {
 *    wp_enqueue_style( 'hemma-child-style',
 *        get_stylesheet_directory_uri() . '/style.css',
 *        array( 'hemma-style' ),
 *        wp_get_theme()->get('Version')
 *    );
 *}
 * add_action( 'wp_enqueue_scripts', 'hemma_child_style' );
 *
 */

/**
 * Create Hemma Excursions custom post types
 *
 * @since    1.0.0
 */
function hemma_excursions_cpt() {
	    
	$labels = array(
	 	'name' => _x( 'Excursions', 'post type general name', 'hemma-cpt-excursions' ),
		'singular_name' => _x( 'Excursion', 'post type singular name', 'hemma-cpt-excursions' ),
		'menu_name' => _x( 'Excursions', 'admin menu', 'hemma-cpt-excursions' ),
		'name_admin_bar' => _x( 'Excursion', 'add new on admin bar', 'hemma-cpt-excursions' ),
		'add_new' => _x( 'Add New', 'Excursion', 'hemma-cpt-excursions' ),
		'add_new_item' => __( 'Add New Excursion', 'hemma-cpt-excursions' ),
		'new_item' => __( 'New Excursion', 'hemma-cpt-excursions' ),
		'edit_item' => __( 'Edit Excursion', 'hemma-cpt-excursions' ),
		'view_item' => __( 'View Excursion', 'hemma-cpt-excursions' ),
		'all_items' => __( 'All Excursions', 'hemma-cpt-excursions' ),
		'search_items' =>  __( 'Search Excursions', 'hemma-cpt-excursions' ),
		'parent_item_colon' => __( 'Parent Excursions:', 'hemma-cpt-excursions' ),
		'not_found' => __( 'No Excursions Found', 'hemma-cpt-excursions'),
		'not_found_in_trash' => __( 'No Excursions found in Trash', 'hemma-cpt-excursions' )
	 );
	 
	 $args = array(
			'labels' => $labels,
			'singular_label' => __( 'Excursion', 'hemma-cpt-excursions' ),
			'public' => true,
			'show_ui' => true,
			'hierarchical' => false,
			'has_archive' => false,
			'menu_icon' => 'dashicons-calendar',
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
			'taxonomies' => array( 'excursioncategory', 'post_tag' )
       );  
   
    register_post_type( 'excursion' , $args );  

}
add_action( 'init', 'hemma_excursions_cpt' );

/**
 * Create Hemma Excursions taxonomy
 *
 * @since    1.0.0
 */
function hemma_excursions_taxonomy() {
 
	$labels = array(
	    'name' => _x( 'Excursion Categories', 'taxonomy general name', 'hemma-cpt-excursions' ),
	    'singular_name' => _x( 'Category', 'taxonomy singular name', 'hemma-cpt-excursions' ),
	    'search_items' =>  __( 'Search Categories', 'hemma-cpt-excursions' ),
	    'popular_items' => __( 'Popular Categories', 'hemma-cpt-excursions' ),
	    'all_items' => __( 'All Categories', 'hemma-cpt-excursions' ),
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => __( 'Edit Category', 'hemma-cpt-excursions' ),
	    'update_item' => __( 'Update Category', 'hemma-cpt-excursions' ),
	    'add_new_item' => __( 'Add New Category', 'hemma-cpt-excursions' ),
	    'new_item_name' => __( 'New Category Name', 'hemma-cpt-excursions' ),
	    'separate_items_with_commas' => __( 'Separate categories with commas', 'hemma-cpt-excursions' ),
	    'add_or_remove_items' => __( 'Add or remove categories', 'hemma-cpt-excursions' ),
	    'choose_from_most_used' => __( 'Choose from the most used categories', 'hemma-cpt-excursions' )
	);
	 
	register_taxonomy( 'excursioncategory', 'excursion', array(
	    'label' => __( 'Excursion Category', 'hemma-cpt-excursions' ),
	    'labels' => $labels,
	    'hierarchical' => true,
	    'show_ui' => true,
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'excursion-category' ),
	));

}
add_action( 'init', 'hemma_excursions_taxonomy', 0 );

/**
 * Adds custom excursion classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function hemma_excursion_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	$header_position = get_theme_mod( 'header_position', 'header-position-1'  );

	// Add hero class
	if  ( is_singular( array( 'excursion' ) ) || is_page_template( 'template-excursion.php' ) ) {
		$classes[] = 'is-hero';

		if ( $header_position == 'header-position-2' ) {
			$classes[] = 'is-header-static';
		}
	}

	// Handle body classes based on Customizer options
	$logo_center_class = 'is-logo-centered';
	$logo_image_class = 'is-logo-image';
	$hamburger_left_class = 'is-hamburger-left';
	$frame_layout_class = 'is-block-frame';
	$menu_desktop_class = 'is-menu-desktop';
	$preloader_class = 'is-loader';
	$gallery_class = 'gallery-first-big';
	$lightbox_class = "is-lightbox-enabled";
	$block_animation_class = 'is-block-animation';
	$no_sidebar_class = 'has-no-sidebar';

	$header_layout = get_theme_mod( 'header_layout', 'header-1'  );
	$logo_img_1x_regul = get_theme_mod( 'logo_image_1', '' );
	$logo_img_2x_regul = get_theme_mod( 'logo_image_2', '' );
	$site_layout = get_theme_mod( 'site_layout', '' );
	$accent_color = get_theme_mod( 'accent_color', '' );
	$preloader = get_theme_mod( 'preloader', false );
	$gallery_first_post = get_theme_mod( 'gallery_first_post', false );
	$enable_lightbox = get_theme_mod( 'enable_lightbox', false );
	$enable_block_animation = get_theme_mod( 'enable_block_animation', false );

	switch ( $header_layout ) {
	    case 'header-1' :
	        $classes[] = '';
	        break;
	    case 'header-2' :
	        $classes[] = join( ' ', array( $menu_desktop_class ) );
	        break;
	    case 'header-3' :
	        $classes[] = join( ' ', array( $hamburger_left_class, $logo_center_class ) );
	        break;
	    case 'header-4' :
	        $classes[] = join( ' ', array( $logo_center_class ) );
	        break;
	}

	if ( $logo_img_1x_regul !== '' || $logo_img_2x_regul !== '' ) {
		$classes[] = $logo_image_class;
	}

	if ( $site_layout == 'layout-2' ) {
		$classes[] = $frame_layout_class;
	}

	if ( $accent_color && $accent_color != 'is-default' ) {
		$classes[] = 'accent-' . $accent_color;
	}

	if ( $preloader == true ) {
		$classes[] = $preloader_class;
	}

	if ( $gallery_first_post == true ) {
		$classes[] = $gallery_class;
	}

	if ( $enable_lightbox == true ) {
		$classes[] = $lightbox_class;
	}

	if ( $enable_block_animation == true ) {
		$classes[] = $block_animation_class;
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = $no_sidebar_class;
	}

	return $classes;
}
add_filter( 'body_class', 'hemma_excursion_body_classes' );

/**
 * Adds custom excursion classes to the array of header classes.
 *
 * @param array $classes Classes for the header element.
 * @return array
 */
function hemma_excursion_header_classes( $classes ) {
	global $post;
	$header_position = get_theme_mod( 'header_position', 'header-position-1'  );

	// Add hero class
	if  ( ( is_singular( array( 'excursion' ) ) || is_page_template( 'template-excursion.php' ) ) && ( $header_position != 'header-position-2' ) ) {
		$classes[] = 'is-hero-on';
	}

	return $classes;
}
add_filter( 'post_class', 'hemma_excursion_header_classes' );

function hemma_customize_child_theme_setup() {
	/**
	 * Add the Enable comments on excursion single posts control
	 */
	hemma_Kirki::add_field( 'hemma_theme', array(
		'type'        => 'switch',
		'settings'    => 'enable_excursion_comments',
		'label'       => esc_html__( 'Comments on excursion posts', 'hemma' ),
		'description' => esc_html__( 'Enable comments on excursion single posts.', 'hemma' ),
		'section'     => 'post_settings',
		'priority'    => 10,
		'default'     => 'off',
		'choices'     => array(
			'on'  => esc_html__( 'On', 'hemma' ),
			'off' => esc_html__( 'Off', 'hemma' ),
		),
	) );

	/**
	 * Add the Excursion pages show at most control
	 */
	hemma_Kirki::add_field( 'hemma_theme', array(
		'type'        => 'number',
		'settings'    => 'excursion_posts_per_page',
		'label'       => esc_html__( 'Excursion posts per page.', 'hemma' ),
		'description' => esc_html__( 'Set the value to -1 if to show all posts.', 'hemma' ),
		'section'     => 'post_settings',
		'priority'    => 10,
		'default'     => 5,
	) );
	}
add_action( 'after_setup_theme', 'hemma_customize_child_theme_setup' );

/**
 * Add Subtitle Meta Box in Excursion pages
 */
add_action( 'cmb2_admin_init', 'opendept_register_excursion_subtitle_metabox' );
function opendept_register_excursion_subtitle_metabox() {
	$prefix = 'opendept_excursion_subtitle_';

	$cmb_subtitle = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Add Subtitle', 'hemma' ),
		'object_types'  => array( 'excursion' ),
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => false,
	) );

	$cmb_subtitle->add_field( array(
		'name' => esc_html__( 'Add Subtitle', 'hemma' ),
		'desc' => esc_html__( 'Leave empty if you don\'t want to show a subtitle', 'hemma' ),
		'id'   => $prefix . 'subtitle',
		'type' => 'text',
	) );

}

/**
 * Add Filter Posts Meta Box in Excursion pages
 */
add_action( 'cmb2_admin_init', 'opendept_register_filter_excursion_metabox' );
function opendept_register_filter_excursion_metabox() {
	$prefix = 'opendept_filter_excursion_';

	$cmb_filter = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Filter Excursions', 'hemma' ),
		'object_types'  => array( 'page' ),
		'show_on'       => array( 'key' => 'cpt-template', 'value' => array( 'template-excursion' ) ),
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => false,
	) );

	$cmb_filter->add_field( array(
	    'name'       => esc_html__( 'Filter posts by Category', 'hemma' ),
	    'desc'       => esc_html__( 'Select the excursion category you want to filter or leave "None". If you can\'t see any option in the list that\'s because you haven\'t any excursion category yet.', 'hemma' ),
	    'id'         => $prefix . 'filter_category',
	    'type'       => 'select',
	    'options_cb' => 'cmb2_get_term_options',
	    'get_terms_args' => array(
	        'taxonomy'   => 'excursioncategory',
	        'hide_empty' => true,
	    ),
	) );
}

/**
 * Add Edit Excursion Meta Box in Excursion posts
 */
add_action( 'cmb2_admin_init', 'opendept_register_excursion_metabox' );
function opendept_register_excursion_metabox() {
	$prefix = 'opendept_excursion_';

	$cmb_excursion = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Edit Excursion', 'hemma' ),
		'object_types'  => array( 'excursion' ),
	) );

	$cmb_excursion->add_field( array(
		'name'       => esc_html__( 'Excursion Date', 'hemma' ),
		'desc'       => esc_html__( 'Leave empty if you don\'t want to show a date', 'hemma' ),
		'id'         => $prefix . 'date',
		'type'       => 'text',
	) );

	$cmb_excursion->add_field( array(
		'name'       => esc_html__( 'Excursion Place', 'hemma' ),
		'desc'       => esc_html__( 'Leave empty if you don\'t want to show a place', 'hemma' ),
		'id'         => $prefix . 'place',
		'type'       => 'text',
	) );

}

/**
 * Add Excursion Sidebar Meta Box in Excursion posts
 */
add_action( 'cmb2_admin_init', 'opendept_register_excursion_sidebar_metabox' );
function opendept_register_excursion_sidebar_metabox() {
	$prefix = 'opendept_excursion_sidebar_';

	$cmb_excursion_sidebar = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Sidebar Settings', 'hemma' ),
		'object_types'  => array( 'excursion' ),
		'context'      => 'normal',
		'priority'     => 'high',
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name' => esc_html__( 'Enable excursion sidebar', 'hemma' ),
	    'desc' => esc_html__( 'Tick this to show the sidebar on this page', 'hemma' ),
	    'id'   => $prefix . 'enable_sidebar',
	    'type' => 'checkbox',
	) );


	$cmb_excursion_sidebar->add_field( array(
	    'name' => esc_html__( 'Show calendar box', 'hemma' ),
	    'desc' => esc_html__( 'That\'s the grey zone where you can set the excursion calendar and the call-to-action button', 'hemma' ),
	    'id'   => $prefix . 'enable_box',
	    'type' => 'checkbox',
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name'    => esc_html__( 'Calendar box title', 'hemma' ),
	    'desc'    => esc_html__( 'The first paragraph in the grey box (e.g. &ldquo;Excursion start&rdquo;)', 'hemma' ),
	    'id'      => $prefix . 'box_title',
	    'type'    => 'text',
	    'default' => esc_html__( 'Excursion start', 'hemma' ),
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name' => esc_html__( 'Excursion day', 'hemma' ),
	    'desc' => esc_html__( 'The day you want to display', 'hemma' ),
	    'id'   => $prefix . 'box_day',
	    'type' => 'text_small',
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name'    => esc_html__( 'Excursion month/year', 'hemma' ),
	    'desc'    => esc_html__( 'The paragraph below the day (you can use it to show the month and the year. E.g. &ldquo;September 2016&rdquo;)', 'hemma' ),
	    'id'      => $prefix . 'box_m_y',
	    'type'    => 'text',
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name'    => esc_html__( 'Button text', 'hemma' ),
	    'desc'    => esc_html__( 'Leave empty if you don\'t want to show the button', 'hemma' ),
	    'id'      => $prefix . 'box_button_text',
	    'type'    => 'text',
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name'    => esc_html__( 'Button link', 'hemma' ),
	    'desc'    => esc_html__( 'The button link (e.g. http://www.website.com)', 'hemma' ),
	    'id'      => $prefix . 'box_button_link',
	    'type'    => 'text_url',
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name' => esc_html__( 'Button color', 'hemma' ),
	    'id'   => $prefix . 'box_button_color',
	    'type' => 'select',
	    'show_option_none' => true,
	    'options' => array(
	    	'is-red'        => esc_html__( 'Red', 'hemma' ),
	    	'is-orange'     => esc_html__( 'Orange', 'hemma' ),
	    	'is-yellow'     => esc_html__( 'Yellow', 'hemma' ),
	    	'is-green'      => esc_html__( 'Green', 'hemma' ),
	    	'is-light-blue' => esc_html__( 'Light Blue', 'hemma' ),
	    	'is-blue'       => esc_html__( 'Blue', 'hemma' ),
	    	'is-purple'     => esc_html__( 'Purple', 'hemma' ),
	    	'is-pink'       => esc_html__( 'Pink', 'hemma' ),
	    	'is-brown'      => esc_html__( 'Brown', 'hemma' ),
	    	'is-dark'       => esc_html__( 'Dark', 'hemma' ),
	    	'is-white'      => esc_html__( 'White', 'hemma' ),
	    ),
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name' => esc_html__( 'Button link opens a new page?', 'hemma' ),
	    'desc' => esc_html__( 'Tick this if you want to open the link in a new page', 'hemma' ),
	    'id'   => $prefix . 'box_button_target',
	    'type' => 'checkbox',
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name'    => esc_html__( 'Extra notes', 'hemma' ),
	    'desc'    => esc_html__( 'Use this field to add extra notes to the grey box', 'hemma' ),
	    'id'      => $prefix . 'box_notes',
	    'type'    => 'textarea',
	) );

	$cmb_excursion_sidebar->add_field( array(
	    'name'    => esc_html__( 'Other sidebar informations', 'hemma' ),
	    'id'      => $prefix . 'content',
	    'type'    => 'wysiwyg',
	    'options' => array(
	    	'media_buttons' => false,
	    	'teeny'         => true,
	    	'wpautop'       => true,
	    ),
	) );

}

/**
 * Add Post Summary Meta Box in Excursion posts
 */
add_action( 'cmb2_admin_init', 'opendept_register_summary_excursion_metabox' );
function opendept_register_summary_excursion_metabox() {
	$prefix = 'opendept_summary_';

	$cmb_summary = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Post Summary', 'hemma' ),
		'object_types'  => array( 'excursion' ),
		'context'      => 'normal',
		'priority'     => 'high',
	) );

	$cmb_summary->add_field( array(
	    'name'    => esc_html__( 'Post Summary Content', 'hemma' ),
	    'show_names'    => false,
	    'desc'    => esc_html__( 'Type here the summary that you want to display on the parent page.', 'hemma' ),
	    'id'      => $prefix . 'content',
	    'type'    => 'wysiwyg',
	    'options' => array(
	    	'media_buttons' => false,
	    	'teeny'         => true,
	    	'wpautop'       => true,
	    ),
	) );

}

/**
 * Add Post Summary Settings Meta Box in Excursion pages
 */
add_action( 'cmb2_admin_init', 'opendept_register_listed_posts_excursion_metabox' );
function opendept_register_listed_posts_excursion_metabox() {
	$prefix = 'opendept_listed_posts_';

	$cmb_listed_posts_excursion = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Post Summary Settings', 'hemma' ),
		'object_types'  => array( 'page' ),
		'show_on'       => array( 'key' => 'cpt-template', 'value' => array( 'template-excursion' ) ),
		'context'       => 'normal',
		'priority'      => 'high',
	) );

	$cmb_listed_posts_excursion->add_field( array(
	    'name'    => esc_html__( 'Show Subtitle', 'hemma' ),
	    'desc'    => esc_html__( 'Tick this to display the subtitle in the post summary', 'hemma' ),
	    'id'      => $prefix . 'enable_subtitle',
	    'type'    => 'checkbox',
	) );

	$cmb_listed_posts_excursion->add_field( array(
	    'name'    => esc_html__( 'Show Meta Informations', 'hemma' ),
	    'desc'    => esc_html__( 'Tick this to display the meta informations (i.e. the icons with labels) in the post summary', 'hemma' ),
	    'id'      => $prefix . 'enable_meta_info',
	    'type'    => 'checkbox',
	) );

	$cmb_listed_posts_excursion->add_field( array(
	    'name'    => esc_html__( 'Show Button', 'hemma' ),
	    'desc'    => esc_html__( 'Tick this to display the button in the post summary', 'hemma' ),
	    'id'      => $prefix . 'enable_button',
	    'type'    => 'checkbox',
	) );

	$cmb_listed_posts_excursion->add_field( array(
	    'name'    => esc_html__( 'Button text', 'hemma' ),
	    'id'      => $prefix . 'button_text',
	    'type'    => 'text',
	    'default' => esc_html__( 'Read More', 'hemma' ),
	) );

	$cmb_listed_posts_excursion->add_field( array(
	    'name' => esc_html__( 'Button color', 'hemma' ),
	    'id'   => $prefix . 'button_color',
	    'type' => 'select',
	    'show_option_none' => true,
	    'options' => array(
	    	'is-red'        => esc_html__( 'Red', 'hemma' ),
	    	'is-orange'     => esc_html__( 'Orange', 'hemma' ),
	    	'is-yellow'     => esc_html__( 'Yellow', 'hemma' ),
	    	'is-green'      => esc_html__( 'Green', 'hemma' ),
	    	'is-light-blue' => esc_html__( 'Light Blue', 'hemma' ),
	    	'is-blue'       => esc_html__( 'Blue', 'hemma' ),
	    	'is-purple'     => esc_html__( 'Purple', 'hemma' ),
	    	'is-pink'       => esc_html__( 'Pink', 'hemma' ),
	    	'is-brown'      => esc_html__( 'Brown', 'hemma' ),
	    	'is-dark'       => esc_html__( 'Dark', 'hemma' ),
	    	'is-white'      => esc_html__( 'White', 'hemma' ),
	    ),
	) );

	$cmb_listed_posts_excursion->add_field( array(
	    'name'    => esc_html__( 'Strip link from the title', 'hemma' ),
	    'desc'    => esc_html__( 'Tick this if you want to strip the link frome the title', 'hemma' ),
	    'id'      => $prefix . 'strip_title_link',
	    'type'    => 'checkbox',
	) );

	$cmb_listed_posts_excursion->add_field( array(
		'name' => esc_html__( 'Blocks Height', 'hemma' ),
		'desc' => esc_html__( 'The minimum height of the blocks', 'hemma' ),
		'id'   => $prefix . 'height',
		'type' => 'select',
		'options' => array(
	        'is-contentheight' => esc_html__( 'Content Height', 'hemma' ),
	        'is-halfheight'    => esc_html__( 'Half browser height', 'hemma' ),
	        'is-fullheight'    => esc_html__( 'Full browser height', 'hemma' ),
	    ),
	    'default' => 'is-contentheight'
	) );

}
