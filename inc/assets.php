<?php
if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', function () {

  wp_enqueue_style(
    'panam-main',
    get_template_directory_uri() . '/assets/css/main.css',
    [],
    PANAM_THEME_VERSION
  );

  // Primary color -> CSS variable (safe)
  $primary = panam_get_theme_setting('primary_color', '#111827');
  $primary = sanitize_hex_color($primary) ?: '#111827';

  wp_add_inline_style(
    'panam-main',
    ':root{--panam-primary:' . $primary . ';}'
  );

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

  wp_enqueue_style('wp-color-picker');
  wp_enqueue_script('wp-color-picker');

  // Optional admin css
  $admin_css = get_template_directory() . '/assets/css/admin.css';
  if (file_exists($admin_css)) {
    wp_enqueue_style(
      'panam-admin',
      get_template_directory_uri() . '/assets/css/admin.css',
      [],
      PANAM_THEME_VERSION
    );
  }

  // Media uploader + color picker init
  wp_enqueue_script(
    'panam-admin-media',
    get_template_directory_uri() . '/assets/js/admin-media.js',
    ['jquery', 'wp-color-picker'],
    PANAM_THEME_VERSION,
    true
  );
});