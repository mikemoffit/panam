<?php
if (!defined('ABSPATH')) exit;

/**
 * =========================
 * Frontend Assets
 * =========================
 */

add_action('wp_enqueue_scripts', function () {

  // Main theme CSS
  wp_enqueue_style(
    'panam-main',
    PANAM_THEME_URI . '/assets/css/main.css',
    [],
    PANAM_THEME_VERSION
  );

  // Runtime CSS (auto-generated design tokens)
  wp_enqueue_style(
    'panam-runtime',
    PANAM_THEME_URI . '/assets/css/runtime.css',
    ['panam-main'], // loads after main
    PANAM_THEME_VERSION
  );

  // Header / burger JS
  wp_enqueue_script(
    'panam-header',
    PANAM_THEME_URI . '/assets/js/header.js',
    [],
    PANAM_THEME_VERSION,
    true
  );
});


/**
 * =========================
 * Admin Assets
 * =========================
 */

add_action('admin_enqueue_scripts', function ($hook) {

  if ($hook !== 'appearance_page_panam-theme-settings') {
    return;
  }

  // Media uploader
  wp_enqueue_media();

  // WordPress color picker
  wp_enqueue_style('wp-color-picker');
  wp_enqueue_script('wp-color-picker');

  // Optional admin CSS
  $admin_css = PANAM_THEME_DIR . '/assets/css/admin.css';
  if (file_exists($admin_css)) {
    wp_enqueue_style(
      'panam-admin',
      PANAM_THEME_URI . '/assets/css/admin.css',
      [],
      PANAM_THEME_VERSION
    );
  }

  // Admin JS (media + color picker init)
  wp_enqueue_script(
    'panam-admin-media',
    PANAM_THEME_URI . '/assets/js/admin-media.js',
    ['jquery', 'wp-color-picker'],
    PANAM_THEME_VERSION,
    true
  );
});