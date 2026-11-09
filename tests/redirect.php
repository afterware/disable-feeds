<?php
// Executed only by the isolated WordPress check: the plugin exits after redirecting.
require '/var/www/html/wp-load.php';
global $wp_query, $wp_rewrite;
$wp_query->is_feed = true;
$wp_query->set('feed', 'rss2');
$wp_rewrite->set_permalink_structure('/%postname%/');
$_SERVER['HTTP_HOST'] = 'untrusted.example';
$_SERVER['REQUEST_URI'] = '/feed/rss2';
add_filter('redirect_canonical', '__return_false');
add_filter('wp_redirect', function ($url) { echo $url; return false; });
$method = new ReflectionMethod(Disable_Feeds::class, 'redirect_feed');
$method->setAccessible(true);
$method->invoke(Disable_Feeds::get_instance());
throw new RuntimeException('Expected redirect');
