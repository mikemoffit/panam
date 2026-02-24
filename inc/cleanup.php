<?php
if (!defined('ABSPATH')) exit;

/**
 * =========================
 * Cleanup: remove WP head/css garbage
 * =========================
 */

/**
 * Disable Emoji scripts/styles (front + admin)
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
 * Stop Global Styles from being generated/rendered
 * (this is what produces <style id="global-styles-inline-css"> and SVG filters)
 */
add_action('after_setup_theme', function () {
  if (is_admin()) return;

  // Stop the enqueue and SVG filter output early
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
  remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

  // Also tell WP not to output "classic theme styles" inline
  remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');
}, 20);

/**
 * Extra hard-stop: prevent global styles inline CSS from printing
 */
add_filter('wp_should_load_separate_core_block_assets', '__return_true');

/**
 * Dequeue block CSS files completely (frontend)
 */
add_action('wp_enqueue_scripts', function () {
  if (is_admin()) return;

  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('wp-block-navigation');

  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
}, 100);

/**
 * Last-resort: if anything still tries to print global styles inline, empty it.
 * (Safe because you explicitly want it gone.)
 */
add_filter('wp_get_custom_css', function ($css) {
  return $css;
});

add_filter('render_block', function ($block_content, $block) {
  return $block_content;
}, 10, 2);

add_filter('wp_global_styles_get_svg_filters', '__return_empty_string');
add_filter('wp_global_styles_render_svg_filters', '__return_empty_string');

add_filter('wp_get_global_stylesheet', function ($stylesheet) {
  return '';
}, 10, 1);