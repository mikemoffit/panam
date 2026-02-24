<?php
if (!defined('ABSPATH')) exit;

add_action('wp_footer', function () {
  if (is_admin()) return;
  $script = trim((string) panam_get_theme_setting('callrail_script', ''));
  if ($script !== '') echo "\n" . $script . "\n";
}, 100);