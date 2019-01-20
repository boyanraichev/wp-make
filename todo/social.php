<?php 
/* SOCIAL FUNCTIONS */

// facebook_like_button( type, width, faces, urlset ) 
// this function displays a facebook like box/button
// $type = box, button, standard
// $width = number of  pixels
// $faces = true or false to show face pile
// $urlset = url to like (overwrites one in options) 

function facebook_like_button($type = NULL,$width = 300,$faces = 'true',$url = NULL, $share = 'false') {
	if ( empty($url) ) { $url = FACEBOOKURL; }	
	if ( empty($url) ) { return; }

	if ($width == NULL) { $width = '300'; }
	
	if ($type == 'box'  ) {
		echo '<div class="fb-like" data-href="' . $url . '" data-send="false" data-layout="box_count" data-action="like" data-width="' . $width . '" data-show-faces="' . $faces .'"></div>';
	}
	if ($type == 'button') {
		echo '<div class="fb-like" data-href="' . $url . '" data-send="false" data-layout="button_count" data-action="like" data-width="' . $width . '" data-show-faces="' . $faces .'"></div>';
	}
	if ($type == 'standard' OR $type == NULL) {
		echo '<div class="fb-like" data-href="' . $url . '" data-send="false" data-layout="standard" data-action="like" data-width="' . $width . '" data-show-faces="' . $faces .'" data-share="'.$share.'"></div>';
	}
}

function facebook_like_box($height = 200,$width = 300,$url = NULL) {
	if ( empty($url) ) { $url = FACEBOOKURL; }	
	if ( empty($url) ) { return; }
	
	//echo '<div class="fb-like-box" data-href="' . $url . '" data-width="' . $width . '" data-height="' . $height . '" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>';
	echo '<div class="fb-page" data-href="' . $url . '" data-width="' . $width . '" data-height="' . $height . '" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="' . $url . '"><a href="'.$url.'">Facebook</a></blockquote></div></div>';
}
function facebook_share($url =  NULL, $layout='button', $large = false) {
	if (empty($url)) { global $wp; $url = home_url(add_query_arg(array(),$wp->request)); }
	echo '<div class="fb-share-button" data-layout="'.sanitize_text_field($layout).'" data-href="' . $url . '" '.($large ? 'data-size="large"' : '').'></div>';
}
function FBfancount($pageid) {
	if ( empty($pageid) ) { $pageid = FACEBOOKID; }
	$data = file_get_contents('http://api.facebook.com/method/fql.query?format=json&query=select+fan_count+from+page+where+page_id%3D'.$pageid);
	$decode = json_decode($data);
	return $decode[0]->fan_count;
}
function twitter_button($count = 'false', $showtext = 'false', $text = '') {
	$url = TWITTERURL;
	if ( empty($url) ) { return; }
	
	echo '<a href="' . $url . '" class="twitter-follow-button" data-show-count="' .$count.'" data-show-screen-name="'.$showtext.'">'.$text.'</a>';
}

function twitter_share($url =  NULL, $text = 'Hello world!', $large = false) {
	if (($url == NULL) OR ($url == '')) { global $wp; $url = home_url(add_query_arg(array(),$wp->request)); }
	echo '<a class="twitter-share-button" href="https://twitter.com/intent/tweet?text='.urlencode($text).'" '.($large ? 'data-size="large"' : '').'>Tweet</a>';
}

