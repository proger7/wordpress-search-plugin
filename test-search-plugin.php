<?php

/**
* Plugin Name: Search Plugin
* Description: Данный плагин является тестовым заданием!
*/


if(!defined('ABSPATH')){
    die;
}


add_filter( 'posts_search', function( $search, \WP_Query $q ) use ( &$wpdb )
{
    // Get the search input
    $s = $q->get( 's' );

    // Check for "slug:" part in the search input
    if( 'slug:' === mb_substr( trim( $s ), 0, 5 ) )
    {
        // Override the search query 
        $search = $wpdb->prepare(
            " AND {$wpdb->posts}.post_name LIKE %s ",
            str_replace( 
                [ '**', '*' ], 
                [ '*',  '%' ],  
                mb_strtolower( 
                    $wpdb->esc_like( 
                        trim( mb_substr( $s, 5 ) ) 
                    ) 
                )
            )
        );

        // Adjust the ordering
        $q->set('orderby', 'post_name' );
        $q->set('order', 'ASC' );
    }
    
    return $search;
}, PHP_INT_MAX, 2 );