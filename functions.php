<?php

// LOAD THEME'S CORE FUNCTIONS(if you remove this, the theme will break)
require_once( 'library/bones.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
require_once( 'library/admin.php' );

/*********************
 * Let's get everything up and running.
 *********************/

function bones_ahoy() {

	//Allow editor style.
	add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

	// launching operation cleanup
	add_action( 'init', 'bones_head_cleanup' );
	// remove WP version from RSS
	add_filter( 'the_generator', 'bones_rss_version' );
	// remove pesky injected css for recent comments widget
	add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
	// clean up comment styles in the head
	add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );

	// enqueue base scripts and styles
	add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
	// ie conditional wrapper

	// launching this stuff after theme setup
	add_action( 'after_setup_theme', 'bones_theme_support', 2 );

	// adding sidebars to Wordpress (these are created in functions.php)
	add_action( 'widgets_init', 'bones_register_sidebars' );

	// cleaning up random code around images
	add_filter( 'the_content', 'bones_filter_ptags_on_images' );
	// cleaning up excerpt
	add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 683;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );


add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'bones-thumb-600' => __( '600px by 150px', 'bonestheme' ),
		'bones-thumb-300' => __( '300px by 100px', 'bonestheme' ),
	) );
}

/************* THEME CUSTOMIZE *********************/

/*
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722

  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162

  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer( $wp_customize ) {
	// $wp_customize calls go here.
	//
	// Uncomment the below lines to remove the default customize sections

	// $wp_customize->remove_section('title_tagline');
	// $wp_customize->remove_section('colors');
	// $wp_customize->remove_section('background_image');
	// $wp_customize->remove_section('static_front_page');
	// $wp_customize->remove_section('nav');

	// Uncomment the below lines to remove the default controls
	// $wp_customize->remove_control('blogdescription');

	// Uncomment the following to change the default section titles
	// $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
	// $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar( array(
		'id'            => 'sidebar1',
		'name'          => __( 'Sidebar 1', 'bonestheme' ),
		'description'   => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'id'            => 'footer',
		'name'          => __( 'Footer', 'mlptheme' ),
		'description'   => __( 'The footer sidebar.', 'mlptheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'id'            => 'sidebar2',
		'name'          => __( 'Sidebar 2', 'bonestheme' ),
		'description'   => __( 'The second sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );

} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
<div id="comment-<?php comment_ID(); ?>" <?php comment_class( 'cf' ); ?>>
	<article class="cf">
		<header class="comment-author vcard">
			<?php
			/*
			  this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
			  echo get_avatar($comment,$size='32',$default='<path_to_url>' );
			*/
			?>
			<?php // custom gravatar call ?>
			<?php
			// create variable
			$bgauthemail = get_comment_author_email();
			?>
			<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif"/>
			<?php // end custom gravatar call ?>
			<?php printf( __( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link( __( '(Edit)', 'bonestheme' ), '  ', '' ) ) ?>
			<time datetime="<?php echo comment_time( 'Y-m-j' ); ?>">
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time( __( 'F jS, Y', 'bonestheme' ) ); ?> </a>
			</time>

		</header>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<div class="alert alert-info">
				<p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
			</div>
		<?php endif; ?>
		<section class="comment_content cf">
			<?php comment_text() ?>
		</section>
		<?php comment_reply_link( array_merge( $args, array(
			'depth' => $depth,
			'max_depth' => $args['max_depth'],
		) ) ) ?>
	</article>
	<?php // </li> is added by WordPress automatically ?>
	<?php
} // don't remove this bracket!


/********************* FONTS **********************/

function bones_fonts() {
	wp_enqueue_style( 'googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic' );
}

add_action( 'wp_enqueue_scripts', 'bones_fonts' );


/**
 * Custom excerpt length and more link
 * use: kn_excerpt (30, "Read More");
 */
function kn_excerpt( $limit = 55, $more = '' ) {
	$post = get_post();
	if ( $post->post_excerpt ) {
		$kn_excerpt = wpautop( wp_strip_all_tags( get_the_excerpt() ) );
	} else {
		$kn_excerpt = wpautop( wp_trim_words( get_the_content(), $limit ) );
	}
	if ( null !== $more ) {
		$kn_excerpt .= '<a class="read-more" href="' . get_the_permalink() . '" title="Read ' . the_title_attribute( array( 'echo' => false ) ) . '">' . $more . '<span class="screen-reader-text"> of "'.get_the_title( $post->ID ).'</span></a>';
	}

	return $kn_excerpt;
}

	/**
	 * Remove allowed HTML box in comments area
	 *
	 * @param $defaults
	 *
	 * @return
	 */
function remove_comment_form_allowed_tags( $defaults ) {
	$defaults['comment_notes_after'] = '';
	return $defaults;
}
add_filter( 'comment_form_defaults', 'remove_comment_form_allowed_tags' );

// Allow .svg uploads
function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

/* DON'T DELETE THIS CLOSING TAG */ ?>
