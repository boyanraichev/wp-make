<?php

if (!defined('ABSPATH')) die;

/*
* SEO STUFF
*/ 
$social_options = get_option('tat_social_options');
$seo_options = get_option('tat_seo');

if (is_admin() AND !empty($seo_options['meta'])) {
	add_action( 'admin_init','tat_seo_metabox',20);
}
function tat_seo_metabox() {
	$tat_seo_meta = [
		'all' => [ 
			'seo_metabox' => [ 
				'metabox_title' => __('SEO','tablank'),
				'metabox_intro' => '', 
				'sections' => [
					[
						'type'		=> 'single',
						'meta_name' => 'noindexnofollow',
						'meta_type' => 'checkbox',
						'label'		=> 'No index, no follow',
						'placeholder'	=> 'No index, no follow',								
					],
					[
						'type'		=> 'single',
						'meta_name' => 'tat_seo_description',
						'meta_type' => 'textarea',
						'label'		=> 'Meta description',
						'placeholder'	=> 'Meta description',								
					],				
				],
			],
		],
	];
	$meta = TatMetaBoxes::get_instance();
	$meta->add_settings($tat_seo_meta);
}

// WP HEAD
add_action('wp_head','tat_seo_head');
function tat_seo_head() {
	
	$social_options = get_option('tat_social_options');
	$seo_options = get_option('tat_seo');
	
	$noindex = false;
	
	// set up defaults		
	$meta = [
		'description' => get_bloginfo('description'),
		'site_name' => get_bloginfo('name'),
		'app_name' => get_bloginfo('name'),
		'ogtitle' => get_bloginfo('name'),
		'ogtype' => 'website',
		'ogimg'	=> '',
	];

	
	if (!empty($seo_options['ogimage'])) {
		$meta['ogimg'] = $seo_options['ogimage'];
	}
	
	if (!empty($seo_options['ogsite'])) {
		$meta['site_name'] = $seo_options['ogsite'];
		$meta['app_name'] = $seo_options['ogsite'];
	}
		
	// if single post/page
	if ( is_single() OR is_page() ) {
		global $post;
		
		$noindex = get_post_meta($post->ID,'noindexnofollow',true);
		$description = get_post_meta($post->ID,'tat_seo_description',true);
		
		if (!empty($description)) {
			$meta['description'] = stripslashes($description);
		} else {
			$meta['description'] = strip_tags(get_the_excerpt());			
			// if front page use default site image
			if (!is_front_page()) {
				if ( has_post_thumbnail() ) { 
					$meta['ogimg'] = wp_get_attachment_url( get_post_thumbnail_id() ); 
				}	
			}		
		}
				
		$meta['ogtitle'] = get_the_title();

	// if tag - tag description
	} elseif ( is_tag() ) { 
		$meta['description'] = strip_tags(tag_description());
		$meta['ogtitle'] = single_term_title('', false);	
	// if category page, use category description as meta description
	} elseif ( is_category() ) { 
		$meta['description'] = strip_tags(category_description());
		$meta['ogtitle'] = single_term_title('', false);	
	// if tax page, use tax description as meta description
	} elseif ( is_tax() ) { 
		$meta['description'] = strip_tags(term_description());
		$meta['ogtitle'] = single_term_title('', false);
	// if archive 
	} elseif ( is_archive() ) {
		$meta['description'] = __('Archive','tablank').' - '.get_bloginfo('name'); 
		$noindex = true;
	// if search
	} elseif ( is_search() ) { 
		$noindex = true;
	// if attachment
	} elseif ( is_404() ) {	
		$noindex = true;
	// if 404
	} elseif ( is_attachment() ) {	
		$noindex = true;
	}
	
	if (is_paged()) {
		$noindex = true;
	}
	
	$meta['current_url'] = 'http://'.$_SERVER['HTTP_HOST'];
	$path = explode( '?', $_SERVER['REQUEST_URI'] ); // Blow up URI
	$meta['current_url'] .= $path[0]; // Only use the rest of URL - before any parameters
	
	
	// apply filter
	$meta = apply_filters( 'tab_page_meta', $meta );
	?>
	
	<?php 
	if (!empty($meta['description'])) { 
		?><meta name="Description" content="<?php echo $meta['description']; ?>"><?php 
	} ?>
	
	<meta property="og:url" content="<?php echo $meta['current_url']; ?>" />
	<meta property="og:site_name" content="<?php echo $meta['site_name']; ?>" />	
	<meta property="og:type" content="<?php echo $meta['ogtype']; ?>" />
	<meta property="og:title" content="<?php echo $meta['ogtitle']; ?>" />
	<meta property="og:description" content="<?php echo $meta['description']; ?>" />	
	<?php if (!empty($meta['ogimg'])) { 
		?><meta property="og:image" content="<?php echo $meta['ogimg']; ?>" /><?php 
	} ?>
		
	<meta name="apple-mobile-web-app-title" content="<?php echo $meta['app_name']; ?>">
	
	<?php if ($noindex) { ?>
		<meta name="robots" content="noindex, nofollow">
	<?php } 
	

}

add_filter( 'document_title_parts', 'tab_title', 10, 1 );
function tab_title( $title ) {
	
	if (is_search()) {
    	$title['title'] = __('Search for', 'tablank') . ' &quot;'.get_search_query().'&quot;'; 
    } 
    global $page, $paged;
  	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
  		$title['page'] = ' ('.__('page', 'tablank').' '. $page.')'; 
	}
	
	return $title;
}

// Fix for qtranslate & sitemap plugin (if using)
if (function_exists('qtranxf_convertURL')) {
    function qtrans_getAvailableLanguages() {
      return call_user_func_array("qtranxf_getSortedLanguages", func_get_args());
    }

    function qtrans_convertURL() {
      return call_user_func_array("qtranxf_convertURL", func_get_args());
    }
}

// sitemap?




