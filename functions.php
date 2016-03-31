<?php

// Edit as required
function tnatheme_globals() {
    global $pre_path;
    global $pre_crumbs;
    if (substr($_SERVER['REMOTE_ADDR'], 0, 3) === '10.') {
        $pre_path = '';
        $pre_crumbs = array(
            'BT Archives' => '/btarchives'
        );
    } else {
        $pre_crumbs = array(
            'BT Archives' => '/btarchives/'
        );
        $pre_path = '';
    }
}
// For live environment
tnatheme_globals();




function dequeue_parent_style() {
    wp_dequeue_style('tna-styles');
    wp_deregister_style('tna-styles');
}
add_action( 'wp_enqueue_scripts', 'dequeue_parent_style', 9999 );
add_action( 'wp_head', 'dequeue_parent_style', 9999 );




// Enqueue styles & scripts
function tna_child_styles() {
    wp_register_style( 'tna-parent-styles', get_template_directory_uri() . '/css/base-sass.css.min', array(), EDD_VERSION, 'all' );
    wp_register_style( 'tna-child-styles', get_stylesheet_directory_uri() . '/css/style.css', array(), '0.1', 'all' );
    wp_register_style( 'tna-child-styles', get_stylesheet_directory_uri() . '/css/style.css', array(), '0.1', 'all' );
    wp_deregister_script('jquery');
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js', '1.6.1', true);
    wp_register_script('jquerylazyload', get_stylesheet_directory_uri().'/js/jquery.lazyload.min.js', '', '1.5.0', true);
    wp_register_script('long-form', get_stylesheet_directory_uri(). '/js/long-form.js','','1.1', true);

    wp_enqueue_script( 'jquery' );
    wp_enqueue_style( 'tna-parent-styles' );
    wp_enqueue_style( 'tna-child-styles' );
    wp_enqueue_script( 'jquerylazyload');
    wp_enqueue_script( 'long-form' );
}
add_action( 'wp_enqueue_scripts', 'tna_child_styles' );



function admin_style() {
    wp_enqueue_style( 'admin-tna-child-styles', get_stylesheet_directory_uri() . '/css/admin-css.css', array(), '0.1', 'all' );
}
add_action( 'admin_print_styles', 'admin_style' );




// Hooks your functions into the correct filters
function image_align() {
    // check user permissions
    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
        return;
    }
    // check if WYSIWYG is enabled
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
        add_filter( 'mce_external_plugins', 'my_add_tinymce_plugin' );
        add_filter( 'mce_buttons', 'my_register_mce_button' );
    }
}
add_action('admin_head', 'image_align');

// Declare script for new button
function my_add_tinymce_plugin( $plugin_array ) {
    $plugin_array['image_align'] = make_path_relative().'/wp-content/themes/tna-base-long-form/js/mce-button.js';
    return $plugin_array;
}



// Register new button in the editor
function my_register_mce_button( $buttons ) {
    array_push( $buttons, 'image_align' );
    return $buttons;
}




function align_image($html, $id, $caption, $title, $align, $url, $size, $alt) {
    $src  = wp_get_attachment_image_src( $id, $size, false );
    $html = get_image_tag($id, '', $title, $align, $size);
    if ($size === "medium") {
        $html5 = "<div class='col-md-6'>";
    }
    $html5 .= "<figure>";
    $html5 .= "<img src='$src[0]' data-original='$src[0]' alt='$alt' class='img-responsive full-width lazy' />";
    if ($caption) {
        $html5 .= "<figcaption class='wp-caption-text'>$caption</figcaption>";
    }
    $html5 .= "</figure>";
    if ($size === "medium") {
        $html5 .= "</div>&nbsp;";
    }
    return $html5;
}
add_filter( 'image_send_to_editor', 'align_image', 10, 9 );

/*to be worked on later



/* Change the name of posts */
function post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Long Form Sections';
    $submenu['edit.php'][5][0] = 'Sections';
    $submenu['edit.php'][10][0] = 'Add Sections';
    $submenu['edit.php'][16][0] = 'Section Tags';
    echo '';
}
add_action( 'admin_menu', 'post_label' );

