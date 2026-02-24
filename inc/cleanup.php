<?php
if (!defined('ABSPATH')) exit;

/**
 * =========================
 * Disable Emojis
 * =========================
 */
add_action('init', function () {

  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');

  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('admin_print_styles', 'print_emoji_styles');

  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

  add_filter('tiny_mce_plugins', function ($plugins) {
    if (!is_array($plugins)) return [];
    return array_diff($plugins, ['wpemoji']);
  });
});


/**
 * =========================
 * Disable Global Styles + Block CSS
 * =========================
 */
add_action('after_setup_theme', function () {

  // Stop global styles system
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
  remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

}, 20);


/**
 * Dequeue block CSS files completely
 */
add_action('wp_enqueue_scripts', function () {

  if (is_admin()) return;

  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
  wp_dequeue_style('wp-block-navigation');

}, 100);