<?php
if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'panam-main',
    PANAM_THEME_URI . '/assets/css/main.css',
    [],
    PANAM_THEME_VERSION
  );

  wp_enqueue_script(
    'panam-header',
    PANAM_THEME_URI . '/assets/js/header.js',
    [],
    PANAM_THEME_VERSION,
    true
  );
});

/**
 * Admin assets only on theme settings page
 */
add_action('admin_enqueue_scripts', function ($hook) {
  if ($hook !== 'appearance_page_panam-theme-settings') return;

  wp_enqueue_media();

  wp_enqueue_style(
    'panam-admin',
    PANAM_THEME_URI . '/assets/css/admin.css',
    [],
    PANAM_THEME_VERSION
  );

  wp_enqueue_script(
    'panam-admin-media',
    PANAM_THEME_URI . '/assets/js/admin-media.js',
    ['jquery'],
    PANAM_THEME_VERSION,
    true
  );
});