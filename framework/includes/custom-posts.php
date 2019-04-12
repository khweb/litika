<?php

// Register Custom Post Type =============================================================================
add_action( 'init', 'services_init', 0 );

function services_init() {
	$post_type_one = wp_kses_data(animo_get_opt('post-type-one'));

	$labels = array(
		'name' => esc_html__( $post_type_one, 'lpthemes' ),
		'singular_name' => esc_html__( $post_type_one, 'lpthemes' ),
		'add_new' => esc_html__( 'Добавить', 'lpthemes' ),
		'add_new_item' => esc_html__( 'Добавить', 'vlthemes' ),
		'edit_item' => esc_html__( 'Редактировать', 'lpthemes' ),
		'new_item' => esc_html__( 'Добавить новую', 'lpthemes' ),
		'view_item' => esc_html__( 'Смотреть', 'lpthemes' ),
		'search_items' => esc_html__( 'Поиск', 'lpthemes' ),
		'not_found' => esc_html__( $post_type_one, 'lpthemes' ),
		'not_found_in_trash' => esc_html__( 'Корзина пуста', 'l;pthemes' )
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'author', 'revisions' ),
		'capability_type' => 'post',
		'menu_position' => 4,
		'has_archive' => false,
		'hierarchical'  => true,
		'menu_icon' => 'dashicons-layout',
	);

	$args = apply_filters('vlthemes_args', $args);
	register_post_type('uslugi', $args);
	flush_rewrite_rules();
}

// Register Custom Post Type =============================================================================
add_action( 'init', 'types_init', 2 );

function types_init() {
	$post_type_two = wp_kses_data(animo_get_opt('post-type-two'));

	$labels = array(
		'name' => esc_html__( $post_type_two, 'lpthemes' ),
		'singular_name' => esc_html__( $post_type_two, 'lpthemes' ),
		'add_new' => esc_html__( 'Добавить', 'lpthemes' ),
		'add_new_item' => esc_html__( 'Добавить', 'vlthemes' ),
		'edit_item' => esc_html__( 'Редактировать', 'lpthemes' ),
		'new_item' => esc_html__( 'Добавить', 'lpthemes' ),
		'view_item' => esc_html__( 'Смотреть', 'lpthemes' ),
		'search_items' => esc_html__( 'Поиск', 'lpthemes' ),
		'not_found' => esc_html__( $post_type_two, 'lpthemes' ),
		'not_found_in_trash' => esc_html__( 'Корзина пуста', 'l;pthemes' )
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'author', 'revisions' ),
		'capability_type' => 'post',
		'menu_position' => 4,
		'has_archive' => false,
		'hierarchical'  => true,
		'menu_icon' => 'dashicons-align-left',
	);

	$args = apply_filters('vlthemes_args', $args);

	register_post_type('marka', $args);
	flush_rewrite_rules();
}