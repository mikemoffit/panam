<?php
if (!defined('ABSPATH')) exit;

add_filter('upload_mimes', function ($mimes) {
  if (!current_user_can('manage_options')) return $mimes;
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
  if (!current_user_can('manage_options')) return $data;
  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  if ($ext === 'svg') {
    $data['ext']  = 'svg';
    $data['type'] = 'image/svg+xml';
  }
  return $data;
}, 10, 4);