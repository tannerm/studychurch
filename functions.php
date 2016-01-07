<?php
/**
 * StudyChurch functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package StudyChurch
 * @since 0.1.0
 */

// Useful global constants
define( 'SC_VERSION', '0.1.5' );
define( 'BP_DEFAULT_COMPONENT', 'profile' );

SC_Setup::get_instance();
class SC_Setup {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Setup
	 *
	 * @return SC_Setup
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Setup ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		$this->add_includes();
		$this->sc_includes();
		$this->add_filters();
		$this->add_actions();
	}

	protected function add_includes() {

		/**
		 * Custom functions that act independently of the theme templates.
		 */
		require get_template_directory() . '/inc/helper_functions.php';

		/**
		 * Customizer additions.
		 */
		require get_template_directory() . '/inc/customizer.php';

		/**
		 * Include custom Foundation functionality
		 */
		require get_template_directory() . '/inc/classes.php';

		/**
		 * Create custom post types
		 */
		require get_template_directory() . '/inc/custom-post-types.php';

		/**
		 * Create custom meta fields
		 */
		require get_template_directory() . '/inc/custom-meta-fields.php';

		/**
		 * Functions for template components
		 */
		require get_template_directory() . '/inc/template-helpers.php';

		/**
		 * Functionality for profile
		 */
		require get_template_directory() . '/inc/profile-helpers.php';

		/**
		 * General BP Filters
		 */
		require get_template_directory() . '/inc/bp-filters.php';

		/**
		 * Handle login and registration requests
		 */
		require get_template_directory() . '/inc/ajax-login.php';
		require get_template_directory() . '/inc/ajax-forms.php';

		/**
		 * Study functions
		 */
		require get_template_directory() . '/inc/study.php';
		require get_template_directory() . '/inc/study/loader.php';

		/**
		 * Initialize Assignments Component
		 */
		require get_template_directory() . '/inc/assignments/loader.php';
	}

	protected function sc_includes() {
		if ( is_child_theme() ) {
			return;
		}

		require get_template_directory() . '/inc/sc-only/groups.php';
		require get_template_directory() . '/inc/sc-only/hooks.php';
	}

		/**
		 * Wire up filters
		 */
	protected function add_filters() {
		add_filter( 'wp_title', array( $this, 'wp_title_for_home' ) );
		add_filter( 'show_admin_bar', array( $this, 'show_admin_bar' ) );
		add_filter( 'bp_get_nav_menu_items', array( $this, 'bp_nav_menu_items' ) );
		add_filter( 'bp_template_include',   array( $this, 'bp_default_template' ) );

		add_filter( 'pre_kses', array( $this, 'vimeo_embed_to_shortcode' ), 20 );
		add_filter( 'video_embed_html', array( $this, 'video_embed_html_wrap' ) );
	}

	/**
	 * Custom page header for home page
	 *
	 * @param $title
	 *
	 * @return string
	 */
	public function wp_title_for_home( $title ) {
		if( empty( $title ) && ( is_home() || is_front_page() ) ) {
			return get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' );
		}
		return $title;
	}

	/**
	 * Wire up actions
	 */
	protected function add_actions() {
		add_action( 'after_setup_theme',  array( $this, 'setup'              ) );
		add_action( 'widgets_init',       array( $this, 'add_sidebars'       ) );
		add_action( 'widgets_init',       array( $this, 'unregister_widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue'            ) );
		add_action( 'wp_head',            array( $this, 'js_globals'         ) );
		add_action( 'wp_head',            array( $this, 'branding_styles'    ) );
		add_action( 'template_redirect',  array( $this, 'maybe_force_login'  ), 5 );
		add_action( 'template_redirect',  array( $this, 'redirect_logged_in_user' ) );
	}

	/**
	 * Theme setup
	 */
	public function setup() {
		add_editor_style();

		$this->add_image_sizes();

		$this->add_menus();

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on sc, use a find and replace
		 * to change 'sc' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'sc', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails on posts and pages
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		add_theme_support( 'title-tag' );

		/**
		 * Setup the WordPress core custom background feature.
		 */
		add_theme_support( 'custom-background', apply_filters( 'sc_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

	}

	/**
	 * Register theme sidebars
	 */
	public function add_sidebars() {

		$defaults = array(
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		);

		$sidebars = array(
			array(
				'id'          => 'blog-sidebar',
				'name'        => 'Blog Sidebar',
				'description' => 'Blog sidebar display',
			),
			array(
				'id'          => 'landing-social',
				'name'        => 'Landing Page Social',
				'description' => 'Social widget for landing page',
			)
		);

		foreach( $sidebars as $sidebar ) {
			register_sidebar( array_merge( $sidebar, $defaults ) );
		}

	}

	/**
	 * Unregister widgets
	 */
	public function unregister_widgets() {}

	/**
	 * Enqueue styles and scripts
	 */
	public function enqueue() {
		$this->enqueue_scripts();
		$this->enqueue_styles();
	}

	/**
	 * Enqueue Styles
	 */
	protected function enqueue_styles() {
		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_style( 'sc', get_template_directory_uri() . "/assets/css/studychurch{$postfix}.css", array(), SC_VERSION );
//		wp_enqueue_style( 'open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,600italic,300,600' );
//		wp_enqueue_style( 'railway', 'https://fonts.googleapis.com/css?family=Raleway' );
	}

	/**
	 * Enqueue scripts
	 */
	protected function enqueue_scripts() {
		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		/**
		 * Libraries and performance scripts
		 */
		wp_enqueue_script( 'datepicker',          get_template_directory_uri() . '/assets/js/lib/foundation-datepicker.min.js',   array(), false, true );
		wp_enqueue_script( 'navigation',          get_template_directory_uri() . '/assets/js/lib/navigation.js',                  array(),           '20120206', true );
		wp_enqueue_script( 'skip-link-focus-fix', get_template_directory_uri() . '/assets/js/lib/skip-link-focus-fix.js',         array(),           '20130115', true );
		wp_enqueue_script( 'foundation',          get_template_directory_uri() . '/assets/js/lib/foundation' . $postfix . '.js', array( 'jquery' ), '01',       true );
		wp_enqueue_script( 'simplePageNav',       get_template_directory_uri() . '/assets/js/lib/jquery.singlePageNav.min.js',    array( 'jquery' ), '01',       true );
		wp_enqueue_script( 'scrollReveal',        get_template_directory_uri() . '/assets/js/lib/scrollReveal.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'scrolltofixed',       get_template_directory_uri() . '/assets/js/lib/scrolltofixed.js', array( 'jquery' ) );

		wp_enqueue_style( 'froala-content', get_template_directory_uri() . '/assets/css/froala/froala_content.css' );
		wp_enqueue_style( 'froala-editor', get_template_directory_uri() . '/assets/css/froala/froala_editor.css' );
		wp_enqueue_style( 'froala-style', get_template_directory_uri() . '/assets/css/froala/froala_style.css' );

		wp_enqueue_script( 'froala-editor', get_template_directory_uri() . '/assets/js/lib/froala/froala_editor.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'froala-video', get_template_directory_uri() . '/assets/js/lib/froala/plugins/video.js', array( 'jquery' ) );
		wp_enqueue_script( 'froala-fullscreen', get_template_directory_uri() . '/assets/js/lib/froala/plugins/fullscreen.min.js', array( 'jquery', 'froala-editor' ) );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( is_singular() && wp_attachment_is_image() ) {
			wp_enqueue_script( 'sc-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
		}

		wp_enqueue_script( 'sc', get_template_directory_uri() . "/assets/js/studychurch{$postfix}.js", array( 'jquery', 'foundation', 'wp-util', 'wp-backbone', 'wp-api', 'jquery-ui-sortable', 'froala-editor', 'datepicker', 'scrollReveal', 'scrolltofixed' ), SC_VERSION, true );
	}

	/**
	 * Is this a development environment?
	 *
	 * @return bool
	 */
	public function is_dev() {
		return ( 'studychurch.dev' == $_SERVER['SERVER_NAME'] );
	}

	/**
	 * Add custom image sizes
	 */
	protected function add_image_sizes() {
		add_image_size( 'post-header', 1500, 500, true );
	}

	/**
	 * Register theme menues
	 */
	protected function add_menus() {
		register_nav_menus( array(
			'members'   => 'Main Members Menu',
			'public'    => 'Main Public Menu',
			'footer'    => 'Main Footer Menu',
		) );
	}

	public function show_admin_bar() {

		if ( is_super_admin() ) {
			return true;
		}

		return false;
	}

	public function bp_nav_menu_items( $items ) {
		// Get the top-level menu parts (Friends, Groups, etc) and sort by their position property
		$top_level_menus = (array) buddypress()->bp_nav;
		usort( $top_level_menus, '_bp_nav_menu_sort' );

		// Iterate through the top-level menus
		foreach ( $top_level_menus as $nav ) {

			// Skip items marked as user-specific if you're not on your own profile
			if ( empty( $nav['show_for_displayed_user'] ) && ! bp_core_can_edit_settings()  ) {
				continue;
			}

			if ( 'activity' == $nav['slug'] ) {
				continue;
			}

			// Get the correct menu link. See http://buddypress.trac.wordpress.org/ticket/4624
			$link = trailingslashit( bp_displayed_user_domain() . $nav['link'] );

			// Add this menu
			$menu         = new stdClass;
			$menu->class  = array( 'menu-parent' );
			$menu->css_id = $nav['css_id'];
			$menu->link   = $link;
			$menu->name   = $nav['name'];
			$menu->parent = 0;

			$menus[] = $menu;
		}

		return $menus;
	}

	public function bp_default_template( $template ) {
		if ( get_template_directory() . '/page.php' != $template ) {
			return $template;
		}

		if ( $new_temp = locate_template( 'templates/full-width.php' ) ) {
			$template = $new_temp;
		}

		return $template;
	}

	/**
	 * Force user login
	 */
	public function maybe_force_login() {
		/** bale if the user is logged in or is on the login page */
		if ( is_user_logged_in() ) {
			return;
		}

		// must be logged in to view buddypress pages
		if ( ! ( is_buddypress() || is_singular( 'sc_study' ) ) ) {
			return;
		}

		// must be logged in to view studies
		if ( is_singular( 'sc_study' ) ) {
			$study_id = get_the_ID();

			if ( apply_filters( 'sc_guest_can_view_study', false, $study_id ) ) {
				return;
			}
		}

		include( get_template_directory() . '/page-login.php' );
		exit();
	}

	/**
	 * Redirect logged in users to their profile
	 */
	public function redirect_logged_in_user() {
		if ( ! is_front_page() ) {
			return;
		}

		if ( current_user_can( 'edit_pages' ) || ! is_user_logged_in() ) {
			return;
		}

		wp_safe_redirect( bp_loggedin_user_domain() );
		die();
	}

	public function js_globals() {

		if ( $id = sc_get( 'study' ) ) {

			// important variables that will be used throughout this example
			$bucket   = 'studychurch';
			$region   = 's3';
			$keyStart = 'studies/' . sanitize_title( get_the_title( $id ) ) . '/';
			$acl      = 'public-read';

			// these can be found on your Account page, under Security Credentials > Access Keys
			$accessKeyId = 'AKIAJNR25AFKFR5VHPJA';
			$secret      = 'jgJT1Qd+LfSa2UKQA/JO16o5dQs3HqE8p2IuBTdY';

			$policy = base64_encode( json_encode( array(
				// ISO 8601 - date('c'); generates uncompatible date, so better do it manually
				'expiration' => date( 'Y-m-d\TH:i:s.000\Z', strtotime( '+1 day' ) ),
				'conditions' => array(
					array( 'bucket' => $bucket ),
					array( 'acl' => $acl ),
					array( 'success_action_status' => '201' ),
					array( 'x-requested-with' => 'xhr' ),
					array( 'starts-with', '$key', $keyStart ),
					array( 'starts-with', '$Content-Type', '' ) // accept all files
				)
			) ) );

			$signature = base64_encode( hash_hmac( 'sha1', $policy, $secret, true ) );

		}

		$key = ( $this->is_dev() ) ? 'ntB-13C-11nroeB-22B-16syB1wqc==' : 'gsuwgH-7fnrzE5ic==';
		$key = apply_filters( 'sc_froala_key', $key ); ?>

		<script>
			jQuery.Editable = jQuery.Editable || {};
			jQuery.Editable.DEFAULTS = jQuery.Editable.DEFAULTS || {};

			jQuery.Editable.DEFAULTS.key = '<?php echo $key; ?>';

			<?php if ( $id ) : ?>

				scFroalaS3 = {
					bucket: '<?php echo $bucket; ?>',
					region: '<?php echo $region; ?>',
					keyStart: '<?php echo $keyStart; ?>',
					callback: function (url, key) {
						// The URL and Key returned from Amazon.
						console.log (url);
						console.log (key);
					},
					params: {
						acl: '<?php echo $acl; ?>',
						AWSAccessKeyId: '<?php echo $accessKeyId; ?>',
						policy: '<?php echo $policy; ?>',
						signature: '<?php echo $signature; ?>'
					}
				};

			<?php endif; ?>

		</script>
		<?php
	}

	public function branding_styles() {
		$primary_color = get_theme_mod( 'primary_color' );
		$success_color = get_theme_mod( 'success_color' );
		$warning_color = get_theme_mod( 'warning_color' );
		$error_color   = get_theme_mod( 'error_color'   );

		if ( ! $primary_color ) {
			return;
		}

		add_filter( 'body_class', function( $classes ) {
			$classes[] = 'branded';
			return $classes;
		} );

		?>

		<style>
			body.branded .site-header,
			body.branded .contain-to-grid,
			body.branded .contain-to-grid .top-bar,
			body.branded .top-bar-section > ul > li:not(.has-form) > a:not(.button),
			body.branded .top-bar-section .dropdown li:not(.has-form):hover > a:not(.button),
			body:not(.logged-in) .site-header .contain-to-grid,
			body:not(.logged-in) .site-header .contain-to-grid .top-bar,
			body:not(.logged-in) .site-header .top-bar-section li:not(.has-form) a:not(.button) {
				background: <?php echo $primary_color; ?>;
				color: white;
			}

			body.branded a,
			body.branded .side-nav li a:not(.button) {
				color: <?php echo $primary_color; ?>
			}

			body.branded a:hover {
				text-decoration: underline;
			}

			body.branded button,
			body.branded .button {
				color: white;
				background-color: <?php echo $primary_color; ?>;
			}

			body.branded #buddypress div.item-list-tabs#subnav {
				background-color: <?php echo $primary_color; ?>;
			}

			body.branded .site-footer {
				border-color: <?php echo $primary_color; ?>
			}

			<?php if ( $success_color ) : ?>
				/* Success Colors */
				body.branded .avatar-container.online:before {
					background-color: <?php echo $success_color; ?>;
				}

				body.branded button.success,
				body.branded .button.success {
					background-color: <?php echo $success_color; ?>;
				}

				#buddypress div#message.success p,
				#buddypress div#message.updated p {
					border-color: <?php echo $success_color; ?>;
				}
			<?php endif; ?>

			<?php if ( $warning_color ) : ?>
				/* Warning Colors */

				#buddypress div#message p,
				#buddypress #sitewide-notice p {
					border-color: <?php echo $warning_color; ?>;
				}

				body.branded .button.secondary {
					background-color: <?php echo $warning_color; ?>;
				}
			<?php endif; ?>

			<?php if ( $error_color ) : ?>
				/* Alert Colors */
				#buddypress div#message.error p {
					border-color: <?php echo $error_color; ?>;
				}

				body.branded .alert-box.alert {
					background-color: <?php echo $error_color; ?>;
					border-color: <?php echo $error_color; ?>;
				}

				body.branded .button.alert {
					background-color: <?php echo $error_color; ?>;
				}
			<?php endif; ?>

		</style>
		<?php
	}

	function youtube_embed_to_short_code( $content ) {
		if ( false === strpos( $content, 'youtube.com' ) )
			return $content;

		//older codes
		$regexp = '!<object width="\d+" height="\d+"><param name="movie" value="https?://www\.youtube\.com/v/([^"]+)"></param>(?:<param name="\w+" value="[^"]*"></param>)*<embed src="https?://www\.youtube\.com/v/(.+)" type="application/x-shockwave-flash"(?: \w+="[^"]*")* width="\d+" height="\d+"></embed></object>!i';
		$regexp_ent = htmlspecialchars( $regexp, ENT_NOQUOTES );
		$old_regexp = '!<embed(?:\s+\w+="[^"]*")*\s+src="https?(?:\:|&#0*58;)//www\.youtube\.com/v/([^"]+)"(?:\s+\w+="[^"]*")*\s*(?:/>|>\s*</embed>)!';
		$old_regexp_ent = str_replace( '&amp;#0*58;', '&amp;#0*58;|&#0*58;', htmlspecialchars( $old_regexp, ENT_NOQUOTES ) );

		//new code
		$ifr_regexp = '!<iframe((?:\s+\w+="[^"]*")*?)\s+src="(https?:)?//(?:www\.)*youtube.com/embed/([^"]+)".*?</iframe>!i';
		$ifr_regexp_ent = str_replace( '&amp;#0*58;', '&amp;#0*58;|&#0*58;', htmlspecialchars( $ifr_regexp, ENT_NOQUOTES ) );

		foreach ( array( 'regexp', 'regexp_ent', 'old_regexp', 'old_regexp_ent', 'ifr_regexp', 'ifr_regexp_ent' ) as $reg ) {
			if ( ! preg_match_all( $$reg, $content, $matches, PREG_SET_ORDER ) )
				continue;

			foreach ( $matches as $match ) {
				// Hack, but '?' should only ever appear once, and
				// it should be for the 1st field-value pair in query string,
				// if it is present
				// YouTube changed their embed code.
				// Example of how it is now:
				//     <object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/aP9AaD4tgBY?fs=1&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/aP9AaD4tgBY?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed></object>
				// As shown at the start of function, previous YouTube didn't '?'
				// the 1st field-value pair.
				if ( in_array ( $reg, array( 'ifr_regexp', 'ifr_regexp_ent' ) ) ) {
					$params = $match[1];

					if ( 'ifr_regexp_ent' == $reg )
						$params = html_entity_decode( $params );

					$params = wp_kses_hair( $params, array( 'http' ) );

					$width = isset( $params['width'] ) ? (int) $params['width']['value'] : 0;
					$height = isset( $params['height'] ) ? (int) $params['height']['value'] : 0;
					$wh = '';

					if ( $width && $height )
						$wh = "&w=$width&h=$height";

					$url = esc_url_raw( "https://www.youtube.com/watch?v={$match[3]}{$wh}" );
				} else {
					$match[1] = str_replace( '?', '&', $match[1] );

					$url = esc_url_raw( "https://www.youtube.com/watch?v=" . html_entity_decode( $match[1] ) );
				}

				$content = str_replace( $match[0], "[youtube $url]", $content );

				/**
				 * Fires before the YouTube embed is transformed into a shortcode.
				 *
				 * @module shortcodes
				 *
				 * @since 1.2.0
				 *
				 * @param string youtube Shortcode name.
				 * @param string $url YouTube video URL.
				 */
				do_action( 'jetpack_embed_to_shortcode', 'youtube', $url );
			}
		}

		return $content;
	}

	function vimeo_embed_to_shortcode( $content ) {
		if ( false === stripos( $content, 'player.vimeo.com/video/' ) )
			return $content;

		$regexp = '!<iframe\s+src=[\'"](https?:)?//player\.vimeo\.com/video/(\d+)[\w=&;?]*[\'"]((?:\s+\w+=[\'"][^\'"]*[\'"])*)((?:[\s\w]*))></iframe>!i';
		$regexp = '!<iframe((?:\s+\w+="[^"]*")*?)\s+src="(https?:)?//player\.vimeo\.com/video/(\d+)".*?</iframe>!i';

		$regexp_ent = str_replace( '&amp;#0*58;', '&amp;#0*58;|&#0*58;', htmlspecialchars( $regexp, ENT_NOQUOTES ) );

		foreach ( array( 'regexp', 'regexp_ent' ) as $reg ) {
			if ( !preg_match_all( $$reg, $content, $matches, PREG_SET_ORDER ) )
				continue;

			foreach ( $matches as $match ) {
				$id = (int) $match[3];

				$params = $match[1];

				if ( 'regexp_ent' == $reg )
					$params = html_entity_decode( $params );

				$params = wp_kses_hair( $params, array( 'http' ) );

				$width = isset( $params['width'] ) ? (int) $params['width']['value'] : 0;
				$height = isset( $params['height'] ) ? (int) $params['height']['value'] : 0;

				$wh = '';
				if ( $width && $height )
					$wh = ' w=' . $width . '&h=' . $height;

				$shortcode = '[vimeo ' . $id . $wh . ']';
				$content = str_replace( $match[0], $shortcode, $content );
			}
		}

		return $content;
	}

	public function video_embed_html_wrap( $html ) {
		$html = str_replace( '<div', '<span', $html );
		$html = str_replace( '</div>', '</span>', $html );

		return $html;
	}


}

