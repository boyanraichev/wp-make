<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	
	<?php
	if ( is_single() OR is_page() ) {
		global $post;
		setup_postdata($post);
	}
	?>
	
	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
	
<?php // template('header-scripts'); ?>