<?php
// Focused checks run in an isolated WordPress installation.
$instance = Disable_Feeds::get_instance();
add_action('wp_head', 'feed_links', 2);
add_action('wp_head', 'feed_links_extra', 3);
$instance->remove_links();
if (has_action('wp_head', 'feed_links') !== false || has_action('wp_head', 'feed_links_extra') !== false) throw new RuntimeException('Feed links were not removed');
if ($instance->filter_bbp_feeds(array('post_type' => 'post')) !== array('post_type' => 'post')) throw new RuntimeException('Non-feed request changed');
echo "PASS feed-link removal and non-feed request\n";

$output = array(); $status = 0;
exec(escapeshellarg(PHP_BINARY) . ' /plugin/tests/redirect.php', $output, $status);
if ($status !== 0 || count($output) !== 1 || strpos($output[0], 'http://pilot.invalid/') !== 0) {
    throw new RuntimeException('Untrusted host was used for a feed redirect');
}
echo "PASS feed redirects stay on an allowed host\n";
