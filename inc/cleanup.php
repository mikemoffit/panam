<?php
if (!defined('ABSPATH')) exit;

/** Disable emojis */
add_action('init', function () {
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  add_filter('tiny_mce_plugins', function ($plugins) {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
  });
});

/** Prevent global styles output (kills global-styles-inline-css) */
add_action('after_setup_theme', function () {
  if (is_admin()) return;
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
  remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
  remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');
}, 20);

add_filter('wp_get_global_stylesheet', function () {
  return '';
}, 10, 1);

add_filter('wp_global_styles_get_svg_filters', '__return_empty_string');
add_filter('wp_global_styles_render_svg_filters', '__return_empty_string');

/** Dequeue block CSS */
add_action('wp_enqueue_scripts', function () {
  if (is_admin()) return;
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('wp-block-navigation');
  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
}, 100);