<?php

/* --------------------------------------------
    Walker classes
-------------------------------------------- */

// Using the Walker class to add the dropdown class to sub-menus per Foundation requirements

class SC_walker extends Walker_Nav_Menu {
	// setting the childred to true or false.. if there are child elements then we are going to
	// call the class below and make sure to add class of has-dropdown
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
		$GLOBALS['dd_children'] = ( isset( $children_elements[$element->ID] ) )? 1:0;
		$GLOBALS['dd_depth'] = (int) $depth;
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
	// add the class of dropdown to sub-level ul
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= $indent ."<ul class=\"dropdown\">";
	}
}

class SC_Study_Manage_Walker extends Walker_Page {
	/**
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Page data object.
	 * @param int $depth Depth of page. Used for padding.
	 * @param int $current_page Page ID.
	 * @param array $args
	 */
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {

		if ( $depth ) {
			$indent = str_repeat( "\t", $depth );
		} else {
			$indent = '';
		}

		$css_class = array( 'page_item', 'page-item-' . $page->ID );

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'page_item_has_children';
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current_page_ancestor';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current_page_item';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'current_page_parent';
			}
		} elseif ( $page->ID == get_option( 'page_for_posts' ) ) {
			$css_class[] = 'current_page_parent';
		}

		/**
		 * Filter the list of CSS classes to include with each page item in the list.
		 *
		 * @since 2.8.0
		 *
		 * @see   wp_list_pages()
		 *
		 * @param array   $css_class    An array of CSS classes to be applied
		 *                              to each list item.
		 * @param WP_Post $page         Page data object.
		 * @param int     $depth        Depth of page, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current page.
		 */
		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

		if ( '' === $page->post_title ) {
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		$base_url = add_query_arg( $_GET, get_the_permalink() );

		/** This filter is documented in wp-includes/post-template.php */
		$output .= $indent . sprintf(
				'<li class="%s"><a href="%s">%s</a>',
				$css_classes,
				add_query_arg( 'item', absint( $page->ID ), $base_url ),
				apply_filters( 'the_title', $page->post_title, $page->ID )
			);

	}
}
// Add class to the tile navigation for blocks
class tile_walker extends Walker_Nav_Menu {

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		// These varibles may not need to be changeds after all
		$largeGrid = 'largegrid';
		$smallGrid = 'smallgrid';

		// add 'tight' to variable to add the smaller margin class
		$tight = '';


		$attributes  = '';
		/*
					// the following code is just like writing it this way
					if(!empty($item-<attr_title)){
						$attribues .= 'title="' . esc_attr($item->attr_title) . '"';
					}
					// or even this
					if(!empty($item->attr_title))
						$attributes .= 'title="' . esc_attr($item->attr_title) .'"';
		*/
		!empty( $item->attr_title ) and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
		!empty( $item->target ) and $attributes .= ' target="' . esc_attr( $item->target ) .'"';
		!empty( $item->xfn ) and $attributes .= ' rel="'    . esc_attr( $item->xfn ) .'"';
		!empty( $item->url ) and $attributes .= ' href="'   . esc_attr( $item->url ) .'"';
		$classes = empty($item->classes) ? array () : (array) $item->classes;
		$class_names = join(' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		!empty ( $class_names ) and $class_names = ' class="'. $largeGrid . '  ' . $smallGrid . ' ' . $tight .'"';

		$output .= '<li id="ms-menu-item-'. $item->ID .'" class="'. $class_names .'" data-height-watch>';

		$output .= '<a' . $attributes . ' class="tile-menu-item">';
		$output .= '<div class="panel table" data-height-watch>';

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$item_output = $args->before
			. "<h2>"
			. $args->link_before
			. $title
			. '</h2></div></a></li>'
			. $args->link_after
			. $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
//Add class to parent pages to show they have subpages (only for Automattic wp_nav_menu)

function add_parent_css($classes, $item){
	global $dd_depth, $dd_children;

	$classes[] = 'depth'.$dd_depth;

	if($dd_children) {
		$classes[] = 'has-dropdown';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'add_parent_css', 10, 2 );

function add_parent_class( $css_class, $page, $depth, $args ) {
	if ( ! empty( $args['has_children'] ) ) {
		$css_class[] = 'has-dropdown';
	}

	return $css_class;
}
add_filter( 'page_css_class', 'add_parent_class', 10, 4 );

// allows you to call my_excerpt() with a choice of predefined lengths
// ie my_excerpt('long');
class SC_Excerpt {

	// Default length (by WordPress)
	public static $length = 55;

	// So you can call: my_excerpt('short');
	public static $types = array(
		'short' => 25,
		'med' => 40,
		'regular' => 55,
		'long' => 100
	);

	/**
	 * Sets the length for the excerpt,
	 * then it adds the WP filter
	 * And automatically calls the_excerpt();
	 *
	 * @param string $new_length
	 * @return void
	 * @author Baylor Rae'
	 */
	public static function length($new_length = 55) {
		SC_Excerpt::$length = $new_length;

		add_filter('excerpt_length', 'SC_Excerpt::new_length');

		SC_Excerpt::output();
	}

	// Tells WP the new length
	public static function new_length() {
		if( isset(SC_Excerpt::$types[SC_Excerpt::$length]) )
			return SC_Excerpt::$types[SC_Excerpt::$length];
		else
			return SC_Excerpt::$length;
	}

	// Echoes out the excerpt
	public static function output() {
		the_excerpt();
	}

}

// An alias to the class
function sc_excerpt($length = 55) {
	SC_Excerpt::length($length);
}

function sc_wp_trim_excerpt($text) {
	$raw_excerpt = $text;
	if ( '' == $text ) {
		//Retrieve the post content.
		$text = get_the_content('');

		//Delete all shortcode tags from the content.
		$text = strip_shortcodes( $text );

		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);

		$allowed_tags = '<p>'; /*** MODIFY THIS. Add the allowed HTML tags separated by a comma.***/
		$text = strip_tags($text, $allowed_tags);

		$excerpt_word_count = 55; /*** MODIFY THIS. change the excerpt word count to any integer you like.***/
		$excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);

		$excerpt_end = '[...]'; /*** MODIFY THIS. change the excerpt endind to something else.***/
		$excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);

		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count($words) > $excerpt_length ) {
			array_pop($words);
			$text = implode(' ', $words);
			$text = $text . $excerpt_more;
		} else {
			$text = implode(' ', $words);
		}
	}
	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
add_filter( 'get_the_excerpt', 'sc_wp_trim_excerpt' );
