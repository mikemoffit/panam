<?php

if (!defined('ABSPATH')) exit;

define('PANAM_THEME_VERSION', '1.0.0');

/**
 * =========================
 * Theme setup
 * =========================
 */
add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
  add_theme_support('automatic-feed-links');

  register_nav_menus([
    'primary' => __('Primary Menu', 'panam'),
    'footer'  => __('Footer Menu', 'panam'),
  ]);
});

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'panam-style',
    get_stylesheet_uri(),
    [],
    PANAM_THEME_VERSION
  );
});

/**
 * =========================
 * Theme Settings (Options)
 * =========================
 */
const PANAM_THEME_OPTIONS_KEY = 'panam_theme_settings';

function panam_get_theme_setting(string $key, $default = '') {
  $opts = get_option(PANAM_THEME_OPTIONS_KEY, []);
  if (!is_array($opts)) $opts = [];
  return array_key_exists($key, $opts) ? $opts[$key] : $default;
}

/**
 * Register admin page
 */
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
  panam_add_field('call_button_text', 'Call Button Text (optional)', 'panam_field_text', 'panam_section_callrail');
  panam_add_field('callrail_number', 'CallRail Tracking Number', 'panam_field_text', 'panam_section_callrail');
  panam_add_field('callrail_script', 'CallRail Script Snippet', 'panam_field_textarea', 'panam_section_callrail');
});

/**
 * Load WP media uploader only on our settings page
 * + bind click handlers for the logo uploader.
 */
add_action('admin_enqueue_scripts', function ($hook) {
  if ($hook !== 'appearance_page_panam-theme-settings') return;

  wp_enqueue_media();

  $js = <<<'JS'
jQuery(function($){

  $(document).on('click', '.panam-media-upload', function(e){
    e.preventDefault();

    var $wrap = $(this).closest('.panam-media-field');
    var $hidden = $wrap.find('.panam-media-id');
    var $remove = $wrap.find('.panam-media-remove');
    var $preview = $wrap.parent().find('.panam-media-preview');

    var frame = wp.media({
      title: 'Select Site Logo',
      button: { text: 'Use this logo' },
      multiple: false
    });

    frame.on('select', function() {
      var attachment = frame.state().get('selection').first().toJSON();
      $hidden.val(attachment.id);
      $remove.prop('disabled', false);

      if (attachment.url) {
        $preview.html(
          '<div class="panam-media-preview-box">' +
            '<img class="panam-media-preview-img" src="' + attachment.url + '" alt="">' +
          '</div>'
        );
      } else {
        $preview.empty();
      }
    });

    frame.open();
  });

  $(document).on('click', '.panam-media-remove', function(e){
    e.preventDefault();

    var $wrap = $(this).closest('.panam-media-field');
    $wrap.find('.panam-media-id').val('');
    $(this).prop('disabled', true);

    $wrap.parent().find('.panam-media-preview').empty();
  });

});
JS;

  wp_add_inline_script('jquery-core', $js);
});

/**
 * Allow SVG uploads for admins only
 */
add_filter('upload_mimes', function ($mimes) {
  if (!current_user_can('manage_options')) return $mimes;
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
  if (!current_user_can('manage_options')) return $data;

  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  if ($ext === 'svg') {
    $data['ext']  = 'svg';
    $data['type'] = 'image/svg+xml';
  }
  return $data;
}, 10, 4);

/**
 * Helper to add field
 */
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

/**
 * Field renderers
 */
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
      $preview = '<div class="panam-media-preview-box">
                    <img class="panam-media-preview-img" src="' . esc_url($url) . '" alt="">
                  </div>';
    }
  }
  ?>
<div class="panam-media-field">
  <input type="hidden" name="<?php echo esc_attr($name_attr); ?>" value="<?php echo esc_attr((string) $id); ?>" class="panam-media-id" />
  <button type="button" class="button panam-media-upload">Select / Upload</button>
  <button type="button" class="button panam-media-remove" <?php echo $id ? '' : 'disabled'; ?>>Remove</button>
</div>
<div class="panam-media-preview"><?php echo $preview; ?></div>
  <?php
}

/**
 * Sanitize
 */
function panam_sanitize_theme_settings($input): array {
  $input = is_array($input) ? $input : [];
  $clean = [];

  // Branding
  $clean['site_logo_id']  = isset($input['site_logo_id']) ? (int) $input['site_logo_id'] : 0;
  $clean['site_logo_alt'] = isset($input['site_logo_alt']) ? sanitize_text_field($input['site_logo_alt']) : '';

  // Contact
  $clean['contact_email']   = isset($input['contact_email']) ? sanitize_email($input['contact_email']) : '';
  $clean['contact_phone']   = isset($input['contact_phone']) ? sanitize_text_field($input['contact_phone']) : '';
  $clean['contact_address'] = isset($input['contact_address']) ? sanitize_textarea_field($input['contact_address']) : '';

  // Social URLs
  foreach (['facebook_url','instagram_url','twitter_url','youtube_url','linkedin_url'] as $k) {
    $clean[$k] = isset($input[$k]) ? esc_url_raw($input[$k]) : '';
  }

  // GTM
  $clean['gtm_id'] = isset($input['gtm_id']) ? sanitize_text_field($input['gtm_id']) : '';

  // Allow scripts for admin-defined snippets
  $clean['gtm_head_snippet']  = isset($input['gtm_head_snippet']) ? (string) $input['gtm_head_snippet'] : '';
  $clean['gtm_body_noscript'] = isset($input['gtm_body_noscript']) ? (string) $input['gtm_body_noscript'] : '';

  // CallRail
  $clean['call_button_text'] = isset($input['call_button_text']) ? sanitize_text_field($input['call_button_text']) : '';
  $clean['callrail_number'] = isset($input['callrail_number']) ? sanitize_text_field($input['callrail_number']) : '';
  $clean['callrail_script'] = isset($input['callrail_script']) ? (string) $input['callrail_script'] : '';

  return $clean;
}

/**
 * Admin Page Output
 */
function panam_render_theme_settings_page(): void {
  if (!current_user_can('manage_options')) return;
  ?>
  <div class="wrap">
    <h1>Panam Theme Settings</h1>
    <form method="post" action="options.php">
      <?php
        settings_fields('panam_theme_settings_group');
        do_settings_sections('panam-theme-settings');
        submit_button();
      ?>
    </form>
  </div>
  <?php
}

/**
 * =========================
 * Frontend Output (GTM + CallRail)
 * =========================
 */
add_action('wp_head', function () {
  if (is_admin()) return;

  $custom = trim((string) panam_get_theme_setting('gtm_head_snippet', ''));
  if ($custom !== '') {
    echo "\n<!-- Panam: Custom GTM Head -->\n" . $custom . "\n";
    return;
  }

  $gtm_id = trim((string) panam_get_theme_setting('gtm_id', ''));
  if ($gtm_id === '') return;
  ?>
  <!-- Google Tag Manager -->
  <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo esc_js($gtm_id); ?>');
  </script>
  <!-- End Google Tag Manager -->
  <?php
}, 1);

add_action('wp_body_open', function () {
  if (is_admin()) return;

  $custom = trim((string) panam_get_theme_setting('gtm_body_noscript', ''));
  if ($custom !== '') {
    echo "\n<!-- Panam: Custom GTM Body -->\n" . $custom . "\n";
    return;
  }

  $gtm_id = trim((string) panam_get_theme_setting('gtm_id', ''));
  if ($gtm_id === '') return;
  ?>
  <!-- Google Tag Manager (noscript) -->
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm_id); ?>"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>
  <!-- End Google Tag Manager (noscript) -->
  <?php
}, 1);

add_action('wp_footer', function () {
  if (is_admin()) return;

  $script = trim((string) panam_get_theme_setting('callrail_script', ''));
  if ($script !== '') {
    echo "\n<!-- Panam: CallRail Script -->\n" . $script . "\n";
  }
}, 100);

/**
 * =========================
 * Template Helpers
 * =========================
 */
function panam_call_button_text(): string {
  $custom = trim((string) panam_get_theme_setting('call_button_text', ''));
  if ($custom !== '') return $custom;

  $phone = panam_theme_phone();
  return $phone !== '' ? $phone : 'Call now';
}
 
function panam_theme_phone(): string {
  $callrail = trim((string) panam_get_theme_setting('callrail_number', ''));
  $main     = trim((string) panam_get_theme_setting('contact_phone', ''));
  return $callrail !== '' ? $callrail : $main;
}

function panam_phone_link(): string {
  $phone = panam_theme_phone();
  if ($phone === '') return '';

  $tel = preg_replace('/[^0-9\+]/', '', $phone);
  return sprintf('<a href="tel:%s">%s</a>', esc_attr($tel), esc_html($phone));
}

function panam_email_link(): string {
  $email = trim((string) panam_get_theme_setting('contact_email', ''));
  if ($email === '') return '';
  return sprintf('<a href="mailto:%s">%s</a>', esc_attr($email), esc_html($email));
}

/**
 * Output logo HTML for header/footer
 */
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