<?php
/**
 * Plugin Name:       CPT Glossary
 * Description:       This plugin registers the 'glossary' post type and 'glossarycat' taxonomy.
 * Version:           1.0
 * License:           GPL v2 or later
 * Text Domain:       cpt-glossary
 * Domain Path:       /languages
 */

define( 'CPT_GLOSSARY', plugin_dir_path( __FILE__ ) );
define( 'CPT_GLOSSARY_URL', plugin_dir_url( __FILE__ ) );

function cpt_glossary_css_js() {
	wp_enqueue_style( 'cpt-glossary-style', CPT_GLOSSARY_URL . 'assets/css/style.css');
	// wp_enqueue_script( 'cpt-glossary-script', CPT_GLOSSARY_URL . 'assets/js/script.js', array( 'jquery' ), NULL, true );
}
add_action( 'wp_enqueue_scripts', 'cpt_glossary_css_js' );

/** 
 * Add translating 
 */
add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'cpt-glossary', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
});

/**
 * https://wpmudev.com/blog/creating-content-custom-post-types/
 * https://wpmudev.com/blog/creating-content-taxonomies-and-fields/
 * https://developer.wordpress.org/themes/template-files-section/taxonomy-templates/
 * https://dimox.name/wordpress-breadcrumbs-without-a-plugin/
 * https://wpml.org/documentation/getting-started-guide/translating-custom-posts/ - переклад WPML
 */

function cpt_glossary_register_post_type() {
	
	/* Register taxonomy */
	$taxonomyLabels = array(
		'name'              => esc_html__( 'Glossary Categories', 'cpt-glossary' ),
		'singular_name'     => esc_html__( 'Glossary Category', 'cpt-glossary' ),
		'search_items'      => esc_html__( 'Search Glossary Categories', 'cpt-glossary' ),
		'all_items'         => esc_html__( 'All Glossary Categories', 'cpt-glossary' ),
		'edit_item'         => esc_html__( 'Edit Glossary Category', 'cpt-glossary' ),
		'update_item'       => esc_html__( 'Update Glossary Category', 'cpt-glossary' ),
		'add_new_item'      => esc_html__( 'Add New Glossary Category', 'cpt-glossary' ),
		'new_item_name'     => esc_html__( 'New Glossary Category', 'cpt-glossary' ),
		'menu_name'         => esc_html__( 'Glossary Categories', 'cpt-glossary' )
	);
	
	register_taxonomy( 'glossarycat', 'glossary', array(
		'hierarchical' => true,
		'labels' => $taxonomyLabels,
		'query_var' => true,
		'show_admin_column' => true,


		/** Якщо потрібно в URL вказувати назву категорії таксономії
		 * 
		 * Наприклад:
		 * 
		 * https://site.com/glossary/a/akula
		 */
		// 'rewrite'               => array(
		// 	'slug'					=>	'glossary', 
		// 	'hierarchical'	=>	false, 
		// 	'with_front'		=>	false, 
		// 	'feed'					=>	false 
		// ),
	) );

    /* Register custom post type */
   
	$cptLabels = array(
       'name'               => esc_html__( 'Glossary', 'cpt-glossary' ),
       'singular_name'      => esc_html__( 'Glossary', 'cpt-glossary' ),
       'add_new'            => esc_html__( 'Add New Glossary', 'cpt-glossary' ),
       'add_new_item'       => esc_html__( 'Add New Glossary', 'cpt-glossary' ),
       'edit_item'          => esc_html__( 'Edit Glossary', 'cpt-glossary' ),
       'new_item'           => esc_html__( 'New Glossary', 'cpt-glossary' ),
       'all_items'          => esc_html__( 'All Glossary', 'cpt-glossary' ),
       'view_item'          => esc_html__( 'View Glossary', 'cpt-glossary' ),
       'search_items'       => esc_html__( 'Search Glossary', 'cpt-glossary' ),
       'not_found'          => esc_html__( 'No Glossary Found', 'cpt-glossary' ),
       'not_found_in_trash' => esc_html__( 'No Glossary found in Trash', 'cpt-glossary' ), 
       'parent_item_colon'  => esc_html__( 'Parent Glossary:', 'cpt-glossary' ),
       'menu_name'          => esc_html__( 'Glossary', 'cpt-glossary' ),
   	);
  
   	register_post_type( 'glossary', array(
       'labels'              => $cptLabels,
       'public'              => true,
       'supports'            => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes' ),
       // 'taxonomies'          => array( 'post_tag', 'category' ), // Стандартні таксономії Тег і Категорія
	   'taxonomies'          => array( 'glossarycat' ),	
       'exclude_from_search' => false,
       'capability_type'     => 'post',
       'menu_icon'           => 'dashicons-book-alt',
       'rewrite'             => array( 'slug' => 'glossary' ),
	   'has_archive'         => true,
	   'show_in_rest' 		 => true, // Gutenberg support
      


	/** Якщо потрібно в URL вказувати назву категорії таксономії
	 * 
	 * Наприклад:
	 * 
	 * https://site.com/glossary/a/akula
	 */
	//    'has_archive'         => 'glossary',
	//    'rewrite'             => array( 
	// 	   'slug'		 =>	'glossary/%glossarycat%',
	// 	   'with_front' =>	false,
	// 	   'pages'		 =>	false,
	// 	   'feeds'		 =>	false,
	// 	   'feed'		 =>	false
	//    ),
       )
   	);
} 
add_action( 'init', 'cpt_glossary_register_post_type' );

// function cpt_glossary_rewrite_rules() {
// 	cpt_glossary_register_post_type();
// 	flush_rewrite_rules();
// }
// add_action( 'after_switch_theme','cpt_glossary_rewrite_rules' );
    

/** Якщо потрібно в URL вказувати назву категорії таксономії
 * 
 * Наприклад:
 * 
 * https://site.com/glossary/a/akula
 */
/** https://wisdmlabs.com/blog/add-taxonomy-term-custom-post-permalinks-wordpress/ */
function cpt_glossary_permalink_structure( $post_link, $post, $leavename, $sample ) {
    if ( false !== strpos( $post_link, '%glossarycat%' ) ) {
        $projectscategory_type_term = get_the_terms( $post->ID, 'glossarycat' );
        if ( ! empty( $projectscategory_type_term ) )
            $post_link = str_replace( '%glossarycat%', array_pop( $projectscategory_type_term )->slug, $post_link );
        else
            $post_link = str_replace( '%glossarycat%', 'no-glossarycat', $post_link );
    }
    return $post_link;
}
//add_filter('post_type_link', 'cpt_glossary_permalink_structure', 10, 4);


function cpt_glossary_permalink( $permalink, $post ) {
	if( strpos( $permalink, '%glossarycat%' ) === false )
		return $permalink;
	$terms = get_the_terms( $post, 'glossarycat' );
	if( ! is_wp_error( $terms ) && ! empty( $terms ) && is_object( $terms[0] ) )
		$term_slug = array_pop( $terms )->slug;
	else
		$term_slug = 'no-glossarycat';
	return str_replace( '%glossarycat%', $term_slug, $permalink );
}
//add_filter('post_type_link', 'cpt_glossary_permalink', 1, 2);



/**
 * Breadcrumbs for CPT
 */
/**
 * Breadcrumbs
 * 
 * https://dimox.name/wordpress-breadcrumbs-without-a-plugin/
 * 
 * How to use:
 * 
 * <?php if ( function_exists( 'dimox_breadcrumbs' ) ) dimox_breadcrumbs(); ?>
 */
/*
 * "Хлебные крошки" для WordPress
 * автор: Dimox
 * версия: 2019.03.03
 * лицензия: MIT
*/
function dimox_breadcrumbs() {

	/* === ОПЦИИ === */
	$text['home']     = esc_html__( 'Home', 'cpt-glossary' ); // текст ссылки "Главная"
	$text['category'] = '%s'; // текст для страницы рубрики
	$text['search']   = esc_html__( 'Search results on request "%s"', 'cpt-glossary' ); // текст для страницы с результатами поиска
	$text['tag']      = esc_html__( 'Posts with a Tag "%s"', 'cpt-glossary' ); // текст для страницы тега
	$text['author']   = esc_html__( 'Author Posts %s', 'cpt-glossary' ); // текст для страницы автора
	$text['404']      = esc_html__( 'Error 404', 'cpt-glossary' ); // текст для страницы 404
	$text['page']     = esc_html__( 'Page %s', 'cpt-glossary' ); // текст 'Страница N'
	$text['cpage']    = esc_html__( 'Comments Page %s', 'cpt-glossary' ); // текст 'Страница комментариев N'

	$wrap_before    = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // открывающий тег обертки
	$wrap_after     = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
	$sep            = '<span class="breadcrumbs__separator"> › </span>'; // разделитель между "крошками"
	$before         = '<span class="breadcrumbs__current">'; // тег перед текущей "крошкой"
	$after          = '</span>'; // тег после текущей "крошки"

	$show_on_home   = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
	$show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
	$show_current   = 1; // 1 - показывать название текущей страницы, 0 - не показывать
	$show_last_sep  = 1; // 1 - показывать последний разделитель, когда название текущей страницы не отображается, 0 - не показывать
	/* === КОНЕЦ ОПЦИЙ === */

	global $post;
	$home_url       = home_url( '/' );
	$link           = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
	$link          .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
	$link          .= '<meta itemprop="position" content="%3$s" />';
	$link          .= '</span>';
	$parent_id      = ( $post ) ? $post->post_parent : '';
	$home_link      = sprintf( $link, $home_url, $text['home'], 1 );

	if ( is_home() || is_front_page() ) {

		if ( $show_on_home ) echo $wrap_before . $home_link . $wrap_after;

	} else {

		$position = 0;

		echo $wrap_before;

		if ( $show_home_link ) {
			$position += 1;
			echo $home_link;
		}

		if ( is_category() ) {
			$parents = get_ancestors( get_query_var('cat'), 'category' );
			foreach ( array_reverse( $parents ) as $cat ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
			}
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				$cat = get_query_var('cat');
				echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_current ) {
					if ( $position >= 1 ) echo $sep;
					echo $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
				} elseif ( $show_last_sep ) echo $sep;
			}

		} elseif ( is_search() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				if ( $show_home_link ) echo $sep;
				echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_current ) {
					if ( $position >= 1 ) echo $sep;
					echo $before . sprintf( $text['search'], get_search_query() ) . $after;
				} elseif ( $show_last_sep ) echo $sep;
			}

		} elseif ( is_year() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . get_the_time('Y') . $after;
			elseif ( $show_home_link && $show_last_sep ) echo $sep;

		} elseif ( is_month() ) {
			if ( $show_home_link ) echo $sep;
			$position += 1;
			echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position );
			if ( $show_current ) echo $sep . $before . get_the_time('F') . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_day() ) {
			if ( $show_home_link ) echo $sep;
			$position += 1;
			echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position ) . $sep;
			$position += 1;
			echo sprintf( $link, get_month_link( get_the_time('Y'), get_the_time('m') ), get_the_time('F'), $position );
			if ( $show_current ) echo $sep . $before . get_the_time('d') . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$position += 1;
				$post_type = get_post_type_object( get_post_type() );
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
				if ( $show_current ) echo $sep . $before . get_the_title() . $after;
				elseif ( $show_last_sep ) echo $sep;
			} else {
				$cat = get_the_category(); $catID = $cat[0]->cat_ID;
				$parents = get_ancestors( $catID, 'category' );
				$parents = array_reverse( $parents );
				$parents[] = $catID;
				foreach ( $parents as $cat ) {
					$position += 1;
					if ( $position > 1 ) echo $sep;
					echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				}
				if ( get_query_var( 'cpage' ) ) {
					$position += 1;
					echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );
					echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
				} else {
					if ( $show_current ) echo $sep . $before . get_the_title() . $after;
					elseif ( $show_last_sep ) echo $sep;
				}
			}

		} elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_post_type() );
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . $post_type->label . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_attachment() ) {
			$parent = get_post( $parent_id );
			$cat = get_the_category( $parent->ID ); $catID = $cat[0]->cat_ID;
			$parents = get_ancestors( $catID, 'category' );
			$parents = array_reverse( $parents );
			$parents[] = $catID;
			foreach ( $parents as $cat ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
			}
			$position += 1;
			echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );
			if ( $show_current ) echo $sep . $before . get_the_title() . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_page() && ! $parent_id ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . get_the_title() . $after;
			elseif ( $show_home_link && $show_last_sep ) echo $sep;

		} elseif ( is_page() && $parent_id ) {
			$parents = get_post_ancestors( get_the_ID() );
			foreach ( array_reverse( $parents ) as $pageID ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
			}
			if ( $show_current ) echo $sep . $before . get_the_title() . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_tag() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				$tagID = get_query_var( 'tag_id' );
				echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_author() ) {
			$author = get_userdata( get_query_var( 'author' ) );
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . sprintf( $text['author'], $author->display_name ) . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_404() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . $text['404'] . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( has_post_format() && ! is_singular() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			echo get_post_format_string( get_post_format() );
		}

		echo $wrap_after;

	}
} // end of dimox_breadcrumbs()