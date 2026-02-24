<?php
if (!defined('ABSPATH')) exit;

function panam_theme_phone(): string {
  $callrail = trim((string) panam_get_theme_setting('callrail_number', ''));
  $main     = trim((string) panam_get_theme_setting('contact_phone', ''));
  return $callrail !== '' ? $callrail : $main;
}

function panam_call_button_text(): string {
  $custom = trim((string) panam_get_theme_setting('call_button_text', ''));
  if ($custom !== '') return $custom;

  $phone = panam_theme_phone();
  return $phone !== '' ? $phone : 'Call now';
}

function panam_site_logo_html(): string {
  $id = (int) panam_get_theme_setting('site_logo_id', 0);
  if ($id <= 0) return '';

  $alt = trim((string) panam_get_theme_setting('site_logo_alt', ''));
  $url = wp_get_attachment_url($id);
  if (!$url) return '';

  return sprintf(
    '<img class="site-logo" src="%s" alt="%s">',
    esc_url($url),
    esc_attr($alt)
  );
}