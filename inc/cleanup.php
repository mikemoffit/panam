<?php
if (!defined('ABSPATH')) exit;

/**
 * Panam: Cleanup WordPress front-end head/styles
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
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
  });
});

/**
 * Prevent Global Styles from generating inline CSS + SVG filters on the frontend
 * This targets:
 * - <style id="global-styles-inline-css">
 * - <style id="classic-theme-styles-inline-css">
 * - SVG filters injected in the body
 */
add_action('after_setup_theme', function () {
  if (is_admin()) return;

  // Stop global styles output
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
  remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

  // Stop classic theme inline styles output
  remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');
}, 20);

// Hard stop: return empty global stylesheet (kills global-styles-inline-css output)
add_filter('wp_get_global_stylesheet', function ($stylesheet) {
  return '';
}, 10, 1);

// Hard stop: remove SVG filter output
add_filter('wp_global_styles_get_svg_filters', '__return_empty_string');
add_filter('wp_global_styles_render_svg_filters', '__return_empty_string');

/**
 * Dequeue block library CSS + global styles handles (frontend)
 * This targets wp-block-xxxx css.
 */
add_action('wp_enqueue_scripts', function () {
  if (is_admin()) return;

  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('wp-block-navigation');

  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
}, 100);