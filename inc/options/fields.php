<?php
if (!defined('ABSPATH')) exit;

function panam_add_field(string $key, string $label, string $render_cb, string $section, array $args = []): void {
  add_settings_field(
    $key,
    esc_html__($label, 'panam'),
    $render_cb,
    'panam-theme-settings',
    $section,
    array_merge(['key' => $key], $args)
  );
}

function panam_field_text(array $args): void {
  $key = $args['key'];
  $type = $args['type'] ?? 'text';
  $value = panam_get_theme_setting($key, '');

  printf(
    '<input type="%s" class="regular-text" name="%s[%s]" value="%s" />',
    esc_attr($type),
    esc_attr(PANAM_THEME_OPTIONS_KEY),
    esc_attr($key),
    esc_attr((string) $value)
  );
}

function panam_field_textarea(array $args): void {
  $key = $args['key'];
  $value = panam_get_theme_setting($key, '');

  printf(
    '<textarea class="large-text" rows="5" name="%s[%s]">%s</textarea>',
    esc_attr(PANAM_THEME_OPTIONS_KEY),
    esc_attr($key),
    esc_textarea((string) $value)
  );
}

function panam_field_media_image(array $args): void {
  $key = $args['key'];
  $id  = (int) panam_get_theme_setting($key, 0);
  $name_attr = PANAM_THEME_OPTIONS_KEY . '[' . $key . ']';

  $preview = '';
  if ($id > 0) {
    $url = wp_get_attachment_url($id);
    if ($url) {
      $preview =
        '<div class="panam-media-preview-box">' .
          '<img class="panam-media-preview-img" src="' . esc_url($url) . '" alt="">' .
        '</div>';
    }
  }

  echo '<div class="panam-media-field">';
  printf(
    '<input type="hidden" class="panam-media-id" name="%s" value="%s" />',
    esc_attr($name_attr),
    esc_attr((string) $id)
  );
  echo '<button type="button" class="button panam-media-upload">Select / Upload</button>';
  printf(
    '<button type="button" class="button panam-media-remove" %s>Remove</button>',
    $id ? '' : 'disabled'
  );
  echo '</div>';

  echo '<div class="panam-media-preview">' . $preview . '</div>';
}

function panam_field_color(array $args): void {
  $key = $args['key'];
  $value = panam_get_theme_setting($key, $args['default'] ?? '#111827');

  printf(
    '<input type="text" class="panam-color-field" name="%s[%s]" value="%s" data-default-color="%s" />',
    esc_attr(PANAM_THEME_OPTIONS_KEY),
    esc_attr($key),
    esc_attr((string) $value),
    esc_attr($args['default'] ?? '#111827')
  );
}

error_log('Panam fields.php loaded from: ' . __FILE__);

