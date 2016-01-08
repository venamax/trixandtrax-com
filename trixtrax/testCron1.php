<?php
require_once('Facebook/Entities/AccessToken.php');
require_once( 'Facebook/FacebookSession.php' );
require_once('Facebook/HttpClients/FacebookHttpable.php');
require_once('Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('Facebook/HttpClients/FacebookCurl.php');
require_once( 'Facebook/FacebookRequest.php' );
require_once('Facebook/FacebookResponse.php');
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;


require( dirname( __FILE__ ) . '/wp-blog-header.php' );

global $wp_query;
global $wpdb;
global $listing_query;

FacebookSession::setDefaultApplication('210412405693448','177fe950d29470dcee451a384636b787');

$session = new FacebookSession('210412405693448|177fe950d29470dcee451a384636b787');

$arg=array(
    'posts_per_page'=>'-1',
    'post_type'=>'post',
    'cat'=>'1,2,178,179'
);

$wp_query = new WP_Query($arg);
//$wp_query->set('posts_per_page',-1);
$index = 0;

while ($wp_query->have_posts()) {
    $wp_query->the_post();
    if(tm_is_post_video()){
    $link = get_permalink();
    $request = new FacebookRequest($session, 'GET', '?id='.$link.'?fields=likes&version=v2.1');
    $response = $request->execute();
    $graphObject = $response->getGraphObject();
    $likes = $graphObject->getProperty('share')->getProperty('share_count');
    $wpdb->query( $wpdb->prepare( 'DELETE FROM wp_wti_like_post where post_id=%d', $post->ID));
    $data = array(
        'post_id' => $post->ID,
        'value' => $likes,
        'date_time' => date('Y-m-d', time()),
        'ip' => '',
        'user_id' => 0
        );
    $wpdb->insert( "{$wpdb->prefix}wti_like_post", $data);
    echo $post->ID.' => '.$link.' tiene '.$likes.' likes<br/>';
    $index++;
    }
}

$con1=$index;
wp_reset_query();

$arg=array(
    'posts_per_page'=>'-1',
    'post_type'=>'post',
    'cat'=>'3,4,5,6,177'
);

$wp_query = new WP_Query($arg);
$index = 0;

while ($wp_query->have_posts()) {
    $wp_query->the_post();
    if(tm_is_post_video()){
    $link = get_permalink();
    $request = new FacebookRequest($session, 'GET', '?id='.$link.'?fields=likes&version=v2.1');
    $response = $request->execute();
    $graphObject = $response->getGraphObject();
    $likes = $graphObject->getProperty('share')->getProperty('share_count');
    $wpdb->query( $wpdb->prepare( 'DELETE FROM wp_wti_like_post where post_id=%d', $post->ID));
    $data = array(
        'post_id' => $post->ID,
        'value' => $likes,
        'date_time' => date('Y-m-d', time()),
        'ip' => '',
        'user_id' => 0
        );
    $wpdb->insert( "{$wpdb->prefix}wti_like_post", $data);
    echo $post->ID.' => '.$link.' tiene '.$likes.' likes<br/>';
    $index++;
    }
}

$con2=$con1+$index;
echo 'Total '.$con2.' Videos';
wp_reset_query();

?>
