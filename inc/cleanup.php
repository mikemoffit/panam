<?php
if (!defined('ABSPATH')) exit;

/**
 * =========================
 * Cleanup: remove WP "garbage" assets
 * =========================
 */

/**
 * Disable Emoji scripts/styles (front + admin)
 */
add_action('init', function () {
  // Frontend
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');

  // Admin
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('admin_print_styles', 'print_emoji_styles');

  // Feeds + emails
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

  // TinyMCE
  add_filter('tiny_mce_plugins', function ($plugins) {
    if (!is_array($plugins)) return [];
    return array_diff($plugins, ['wpemoji']);
  });
});

/**
 * Remove block library CSS + global styles CSS from the frontend
 * (These are responsible for a lot of wp-block-* and global-styles output)
 */
add_action('wp_enqueue_scripts', function () {
  if (is_admin()) return;

  // Core block CSS files
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('wp-block-navigation'); // sometimes enqueued

  // Theme + global styles (adds <style id="global-styles-inline-css"> etc.)
  wp_dequeue_style('global-styles');

  // Classic theme styles <style id="classic-theme-styles-inline-css">
  wp_dequeue_style('classic-theme-styles');
}, 100);

/**
 * Extra: prevent global styles from being generated at all on the frontend
 * (helps reduce inline global styles + SVG filters output)
 */
add_action('after_setup_theme', function () {
  if (is_admin()) return;

  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
  remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
}, 20);

