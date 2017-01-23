<?php
/*
* Load theme setup
* ******************************************************************* */
require_once( get_template_directory() . '/theme/theme-setup.php' );

/*
* Load framework
* ******************************************************************* */
require_once( get_template_directory() . '/framework/init.php' );

/*
* Load theme
* ******************************************************************* */
require_once( get_template_directory() . '/theme/init.php' );



/*** Remove Query String from Static Resources ***/
function remove_cssjs_ver( $src ) {
 if( strpos( $src, '?ver=' ) )
 $src = remove_query_arg( 'ver', $src );
 return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );



