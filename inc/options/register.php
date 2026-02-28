<?php
if (!defined('ABSPATH')) exit;

const PANAM_THEME_OPTIONS_KEY = 'panam_theme_settings';

function panam_get_theme_setting(string $key, $default = '') {
  $opts = get_option(PANAM_THEME_OPTIONS_KEY, []);
  if (!is_array($opts)) $opts = [];
  return array_key_exists($key, $opts) ? $opts[$key] : $default;
}

add_action('admin_menu', function () {
  add_theme_page(
    __('Panam Theme Settings', 'panam'),
    __('Theme Settings', 'panam'),
    'manage_options',
    'panam-theme-settings',
    'panam_render_theme_settings_page'
  );
});

add_action('admin_init', function () {
  register_setting(
    'panam_theme_settings_group',
    PANAM_THEME_OPTIONS_KEY,
    [
      'type' => 'array',
      'sanitize_callback' => 'panam_sanitize_theme_settings',
      'default' => [],
    ]
  );

  add_settings_section('panam_section_branding', __('Branding', 'panam'), '__return_false', 'panam-theme-settings');
  add_settings_section('panam_section_contact', __('Contact', 'panam'), '__return_false', 'panam-theme-settings');
  add_settings_section('panam_section_social', __('Social', 'panam'), '__return_false', 'panam-theme-settings');
  add_settings_section('panam_section_tracking', __('Tracking', 'panam'), '__return_false', 'panam-theme-settings');
  add_settings_section('panam_section_callrail', __('CallRail', 'panam'), '__return_false', 'panam-theme-settings');

// Branding
panam_add_field('site_logo_id', 'Site Logo (SVG/PNG/JPG)', 'panam_field_media_image', 'panam_section_branding');
panam_add_field('site_logo_alt', 'Logo Alt Text', 'panam_field_text', 'panam_section_branding');

if (function_exists('panam_field_color')) {
  panam_add_field(
    'primary_color',
    'Primary Color',
    'panam_field_color',
    'panam_section_branding',
    ['default' => '#111827']
  );
} else {
  add_action('admin_notices', function () {
    echo '<div class="notice notice-error"><p><strong>Panam:</strong> panam_field_color() not loaded. Check inc/options/fields.php include path.</p></div>';
  });
}
// Colors
panam_add_field('primary_color',   'Primary Color',   'panam_field_color', 'panam_section_branding', ['default' => '#111827']);
panam_add_field('secondary_color', 'Secondary Color', 'panam_field_color', 'panam_section_branding', ['default' => '#6b7280']);
panam_add_field('accent_color',    'Accent Color',    'panam_field_color', 'panam_section_branding', ['default' => '#22c55e']);

// Mode
panam_add_field(
  'color_mode',
  'Color Mode',
  'panam_field_select',
  'panam_section_branding',
  [
    'default' => 'auto',
    'options' => [
      'auto'  => 'Auto (system)',
      'light' => 'Force Light',
      'dark'  => 'Force Dark',
    ],
  ]
);
  // Contact
  panam_add_field('contact_email', 'Email Address', 'panam_field_text', 'panam_section_contact', ['type' => 'email']);
  panam_add_field('contact_phone', 'Phone Number', 'panam_field_text', 'panam_section_contact');
  panam_add_field('contact_address', 'Physical Address', 'panam_field_textarea', 'panam_section_contact');

  // Social
  panam_add_field('facebook_url', 'Facebook URL', 'panam_field_text', 'panam_section_social', ['type' => 'url']);
  panam_add_field('instagram_url', 'Instagram URL', 'panam_field_text', 'panam_section_social', ['type' => 'url']);
  panam_add_field('twitter_url', 'X (Twitter) URL', 'panam_field_text', 'panam_section_social', ['type' => 'url']);
  panam_add_field('youtube_url', 'YouTube URL', 'panam_field_text', 'panam_section_social', ['type' => 'url']);
  panam_add_field('linkedin_url', 'LinkedIn URL', 'panam_field_text', 'panam_section_social', ['type' => 'url']);

  // Tracking
  panam_add_field('gtm_id', 'Google Tag Manager ID (GTM-XXXXXXX)', 'panam_field_text', 'panam_section_tracking');
  panam_add_field('gtm_head_snippet', 'Custom GTM Head Snippet', 'panam_field_textarea', 'panam_section_tracking');
  panam_add_field('gtm_body_noscript', 'Custom GTM Body Noscript', 'panam_field_textarea', 'panam_section_tracking');

  // CallRail
  panam_add_field('callrail_number', 'CallRail Tracking Number', 'panam_field_text', 'panam_section_callrail');
  panam_add_field('callrail_script', 'CallRail Script Snippet', 'panam_field_textarea', 'panam_section_callrail');
  panam_add_field('call_button_text', 'Call Button Text (optional)', 'panam_field_text', 'panam_section_callrail');
});