<?php
if (!defined('ABSPATH')) exit;

add_action('wp_head', function () {
  if (is_admin()) return;

  $custom = trim((string) panam_get_theme_setting('gtm_head_snippet', ''));
  if ($custom !== '') { echo "\n" . $custom . "\n"; return; }

  $gtm_id = trim((string) panam_get_theme_setting('gtm_id', ''));
  if ($gtm_id === '') return;
  ?>
  <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo esc_js($gtm_id); ?>');
  </script>
  <?php
}, 1);

add_action('wp_body_open', function () {
  if (is_admin()) return;

  $custom = trim((string) panam_get_theme_setting('gtm_body_noscript', ''));
  if ($custom !== '') { echo "\n" . $custom . "\n"; return; }

  $gtm_id = trim((string) panam_get_theme_setting('gtm_id', ''));
  if ($gtm_id === '') return;
  ?>
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm_id); ?>"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>
  <?php
}, 1);