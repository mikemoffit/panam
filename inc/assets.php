<?php
if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', function () {
  // Frontend CSS (your real styles now live here)
  wp_enqueue_style(
    'panam-main',
    get_template_directory_uri() . '/assets/css/main.css',
    [],
    PANAM_THEME_VERSION
  );

$primary = panam_get_theme_setting('primary_color', '#111827');

if ($primary) {
  wp_add_inline_style(
    'panam-main',
    ":root { --panam-primary: {$primary}; }"
  );
}


  // Frontend JS (burger/menu)
  wp_enqueue_script(
    'panam-header',
    get_template_directory_uri() . '/assets/js/header.js',
    [],
    PANAM_THEME_VERSION,
    true
  );
});

add_action('admin_enqueue_scripts', function ($hook) {
  if ($hook !== 'appearance_page_panam-theme-settings') return;

  wp_enqueue_media();

  // Admin CSS (optional)
  $admin_css = get_template_directory() . '/assets/css/admin.css';
  if (file_exists($admin_css)) {
    wp_enqueue_style(
      'panam-admin',
      get_template_directory_uri() . '/assets/css/admin.css',
      [],
      PANAM_THEME_VERSION
    );
  }

  // Admin JS (media uploader)
  wp_enqueue_script(
    'panam-admin-media',
    get_template_directory_uri() . '/assets/js/admin-media.js',
    ['jquery'],
    PANAM_THEME_VERSION,
    true
  );
});

add_action('admin_enqueue_scripts', function ($hook) {
  if ($hook !== 'appearance_page_panam-theme-settings') return;

  wp_enqueue_style('wp-color-picker');
  wp_enqueue_script('wp-color-picker');

  wp_add_inline_script(
    'wp-color-picker',
    "jQuery(function($){ $('.panam-color-field').wpColorPicker(); });"
  );
});