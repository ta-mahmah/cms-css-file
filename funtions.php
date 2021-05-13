<?php
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles',99);
function child_enqueue_styles() {
    $parent_style = 'parent-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ) );
}
if ( get_stylesheet() !== get_template() ) {
    add_filter( 'pre_update_option_theme_mods_' . get_stylesheet(), function ( $value, $old_value ) {
         update_option( 'theme_mods_' . get_template(), $value );
         return $old_value; // prevent update to child theme mods
    }, 10, 2 );
    add_filter( 'pre_option_theme_mods_' . get_stylesheet(), function ( $default ) {
        return get_option( 'theme_mods_' . get_template(), $default );
    } );
}
//delete a post
function remove_post() {
    global $post;
    ob_start();
    if ( current_user_can('delete_posts', $post->ID ) ) {
        echo '<a class="delete-post" rel="nofollow" href="' . esc_url( get_delete_post_link( $post->ID ) ) . '">Delete this Post</a>';
    }
    return ob_get_clean();
}
add_shortcode( 'remove', 'remove_post' );
	
//load image
function detailing_pic() {
     return '<img src="https:/https://thumbs.dreamstime.com/b/car-detailing-polishing-concept-hands-professional-car-service-male-worker-orbital-polisher-polishing-car-detailing-179172829.jpg" 
    alt="record" width="96" height="96" class="left-align" />';
}

add_shortcode('car', 'detailing_pic');


//show latest post on the home page
function posts($atts, $content = NULL)
{
    $atts = shortcode_atts(
        [
            'orderby' => 'date',
            'posts_per_page' => '3'
        ], $atts, 'recent-posts' );
     
    $query = new WP_Query( $atts );
    $output = '<ul class="recent-posts">';
 
    while($query->have_posts()) : $query->the_post();
 
        $output .= '<li><a href="' . get_permalink() . '">'
            . get_the_title() . '</a> - <small>' . get_the_date() . '</small></li>';
 
    endwhile;
 
    wp_reset_query();
 
    return $output . '</ul>';
}

	add_shortcode('newposts', 'posts');

//display text in post
function text() {

	return 'This post is all about the ins and outs of automotive detailing';
}

	add_shortcode('text' , 'text');
	
//map of malta

function Map($atts, $content = null) {
    extract(shortcode_atts(array("width" => '640',"height" => '480',"src" => ''), $atts));
	return ' <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10869.047693616585!2d14.417900452484469!3d35.913021089563344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x130e4fd3892dd7b9%3A0xc7e94747c3647e86!2sMosta!5e0!3m2!1sen!2smt!4v1620848422609!5m2!1sen!2smt" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
}
add_shortcode("map", "Map");


//redirect to the shop
function redirect( $atts = array(), $content = null ) {
  
    extract(shortcode_atts(array('link' => '#'), $atts));
    
    return '<a href="'. $link .'" target="blank" class="doti-button">' . $content . '</a>';
     
}

add_shortcode('redirect', 'redirect');


// add a hyperlink for user to go to page

function linkHref($atts, $content = null) {
	return '<a href="https://en.wikipedia.org/wiki/Auto_detailing">' . $content . '</a>';
	
}
add_shortcode('link', 'linkHref');