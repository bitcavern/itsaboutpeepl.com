<?php

// Enable tags, categories, and custom excerpt text box for Pages.
function enable_full_meta_for_pages() {
    add_post_type_support( 'page', 'excerpt' );
    register_taxonomy_for_object_type( 'post_tag', 'page' );
    register_taxonomy_for_object_type( 'category', 'page' );
}

add_action( 'init', 'enable_full_meta_for_pages' );


// Display pages (as well as posts) on category and tag archive pages.
function modify_post_types_shown_on_taxonomy_archive_pages( $query ) {
    if ( $query->is_main_query() )  {
        if ( $query->is_category() || $query->is_tag() ) {
            $query->set( 'post_type',
                array(
                    'post',
                    'page'
                )
            );
        }
    }
    return $query;
}

add_filter( 'pre_get_posts', 'modify_post_types_shown_on_taxonomy_archive_pages' );


// If you add a ?s= query parameter to the URL for a custom post type
// archive page, only matching posts will be displayed. Great! But WordPress
// also changes the document title to "Search results for…" which is a bit
// misleading. We prefer to keep showing the post type name.
function prefer_post_type_name_over_search_term_in_document_title( $title ) {
    if ( is_post_type_archive() && is_search() ) {
        $title['title'] = post_type_archive_title( '', false );
    }
    return $title;
}

add_filter('document_title_parts', 'prefer_post_type_name_over_search_term_in_document_title', 10);
