<?php
/**
 * _s functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _s
 */

// Load Composer dependencies
require 'vendor/autoload.php';

// Add data globally (currently not working)
$templates = new League\Plates\Engine(get_template_directory() . '/views');
$templates->addFolder('partials', get_template_directory() . '/views/partials');

$data = [
	'id'      => $object_id,
	'title'   => get_the_title(),
	'content' => get_post_field( 'post_content', $post_id )
];

$templates->addData($data);

if ( ! function_exists( '_s_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function _s_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on _s, use a find and replace
		 * to change '_s' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( '_s', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', '_s' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( '_s_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', '_s_setup' );

/**
 * Enqueue scripts and styles.
 */
function _s_scripts() {
	wp_enqueue_style( '_s-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', '_s_scripts' );

function theme_get_context() {
	$context = '';
	$object    = get_queried_object();
	$object_id = get_queried_object_id();

	// Singular views.
	if ( is_singular() ) {
		if ( is_front_page() ) {
			$context[] = 'front-page';
		}
		$context[] = "singular-{$object->post_type}-{$object_id}";
		$context[] = "singular-{$object->post_type}";
		$context[] = 'singular';
	}
	// Archive views.
	elseif ( is_archive() ) {
		if ( is_home() ) {
			$context[] = 'blog';
		}
		// Post type archives.
		if ( is_post_type_archive() ) {
			$post_type = get_query_var( 'post_type' );
			if ( is_array( $post_type ) )
				reset( $post_type );
			$context[] = "archive-{$post_type}";
		}
		// Taxonomy archives.
		if ( is_tax() || is_category() || is_tag() ) {
			$slug = 'post_format' == $object->taxonomy ? str_replace( 'post-format-', '', $object->slug ) : $object->slug;
			$context[] = "taxonomy-{$object->taxonomy}-" . sanitize_html_class( $slug, $object->term_id );
			$context[] = "taxonomy-{$object->taxonomy}";
			$context[] = 'taxonomy';
		}
		// User/author archives.
		if ( is_author() ) {
			$user_id = get_query_var( 'author' );
			$context[] = 'user-' . sanitize_html_class( get_the_author_meta( 'user_nicename', $user_id ), $user_id );
			$context[] = 'user';
		}
		// Date archives.
		if ( is_date() ) {
			if ( is_year() )
				$context[] = 'year';
			if ( is_month() )
				$context[] = 'month';
			if ( get_query_var( 'w' ) )
				$context[] = 'week';
			if ( is_day() )
				$context[] = 'day';
			
			$context[] = 'date';
		}
		// Time archives.
		if ( is_time() ) {
			$context[] = 'time';
			if ( get_query_var( 'hour' ) )
				$context[] = 'hour';
			if ( get_query_var( 'minute' ) )
				$context[] = 'minute';
		}

		$context[] = 'archive';
	}
	// Search results.
	elseif ( is_search() ) {
		$context[] = 'search';
		$context[] = 'archive';
	}
	// Error 404 pages.
	elseif ( is_404() ) {
		$context[] = '404';
	}
	return $context;
}
