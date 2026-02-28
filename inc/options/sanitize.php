<?php
if (!defined('ABSPATH')) exit;

function panam_sanitize_theme_settings($input): array {
  $input = is_array($input) ? $input : [];
  $clean = [];

  $clean['site_logo_id']  = isset($input['site_logo_id']) ? (int) $input['site_logo_id'] : 0;
  $clean['site_logo_alt'] = isset($input['site_logo_alt']) ? sanitize_text_field($input['site_logo_alt']) : '';

  /* ===== Primary Color ===== */
  $primary = isset($input['primary_color'])
    ? sanitize_hex_color($input['primary_color'])
    : '';

  $clean['primary_color'] = $primary ?: '#111827';

  $clean['contact_email']   = isset($input['contact_email']) ? sanitize_email($input['contact_email']) : '';
  $clean['contact_phone']   = isset($input['contact_phone']) ? sanitize_text_field($input['contact_phone']) : '';
  $clean['contact_address'] = isset($input['contact_address']) ? sanitize_textarea_field($input['contact_address']) : '';
$clean['primary_color']   = sanitize_hex_color($input['primary_color'] ?? '') ?: '#111827';
$clean['secondary_color'] = sanitize_hex_color($input['secondary_color'] ?? '') ?: '#6b7280';
$clean['accent_color']    = sanitize_hex_color($input['accent_color'] ?? '') ?: '#22c55e';

$mode = isset($input['color_mode']) ? sanitize_key($input['color_mode']) : 'auto';
$clean['color_mode'] = in_array($mode, ['auto','light','dark'], true) ? $mode : 'auto';
  foreach (['facebook_url','instagram_url','twitter_url','youtube_url','linkedin_url'] as $k) {
    $clean[$k] = isset($input[$k]) ? esc_url_raw($input[$k]) : '';
  }

  $clean['gtm_id'] = isset($input['gtm_id']) ? sanitize_text_field($input['gtm_id']) : '';
  $clean['gtm_head_snippet']  = isset($input['gtm_head_snippet']) ? (string) $input['gtm_head_snippet'] : '';
  $clean['gtm_body_noscript'] = isset($input['gtm_body_noscript']) ? (string) $input['gtm_body_noscript'] : '';

  $clean['callrail_number'] = isset($input['callrail_number']) ? sanitize_text_field($input['callrail_number']) : '';
  $clean['callrail_script'] = isset($input['callrail_script']) ? (string) $input['callrail_script'] : '';
  $clean['call_button_text'] = isset($input['call_button_text']) ? sanitize_text_field($input['call_button_text']) : '';

  return $clean;
}