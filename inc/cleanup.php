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
 * Remove Global Styles + Classic Theme Styles output hooks
 * IMPORTANT: remove_action must match the priority used in core.
 */
add_action('init', function () {
  if (is_admin()) return;

  // Global Styles (this produces <style id="global-styles-inline-css">)
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles', 1);
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles', 10);

  // SVG filters printed into the body
  remove_action('wp_body_open', 'wp_global_styles_render_svg_filters', 1);
  remove_action('wp_body_open', 'wp_global_styles_render_svg_filters', 10);

  // Classic theme styles (this produces <style id="classic-theme-styles-inline-css">)
  remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles', 1);
  remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles', 10);
}, 0);

/**
 * Hard stop: even if something still tries to generate it, return empty.
 * (Some setups still output an empty <style> tag, but this removes the contents.)
 */
add_filter('wp_get_global_stylesheet', function ($stylesheet) {
  return '';
}, 10, 1);

add_filter('wp_global_styles_get_svg_filters', '__return_empty_string');
add_filter('wp_global_styles_render_svg_filters', '__return_empty_string');

/**
 * Dequeue style handles just in case something else enqueues them later
 */
add_action('wp_enqueue_scripts', function () {
  if (is_admin()) return;

  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
  wp_dequeue_style( 'global-styles' ); 
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('wp-block-navigation');
}, 100);