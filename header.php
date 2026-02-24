<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="container panam-header">
    <!-- Mobile/Tablet Left: Hamburger -->
    <button
      class="panam-burger"
      type="button"
      aria-label="<?php echo esc_attr__('Open menu', 'panam'); ?>"
      aria-expanded="false"
      aria-controls="panam-mobile-menu"
    >
      <span></span><span></span><span></span>
    </button>

    <!-- Logo (desktop left / mobile center) -->
    <div class="panam-logo">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="panam-logo-link" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <?php
          $logo = panam_site_logo_html();
          echo $logo !== '' ? $logo : esc_html(get_bloginfo('name'));
        ?>
      </a>
    </div>

    <!-- Desktop Center: Menu -->
    <nav class="panam-nav-desktop" aria-label="<?php echo esc_attr__('Primary', 'panam'); ?>">
      <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'fallback_cb'    => false,
          'depth'          => 2,
          'menu_class'     => 'menu panam-menu-desktop',
        ]);
      ?>
    </nav>

    <!-- Desktop Right: Call Now button | Mobile Right: Phone icon -->
    <div class="panam-cta">
      <?php
        $phone_display = panam_theme_phone();
        $tel = preg_replace('/[^0-9\+]/', '', $phone_display);
        $cta_text = panam_call_button_text();

        if ($tel !== ''):
      ?>
        <a class="panam-call-btn" href="tel:<?php echo esc_attr($tel); ?>">
          <span class="panam-call-btn-text"><?php echo esc_html($cta_text); ?></span>
          <span class="panam-call-icon" aria-hidden="true">☎</span>
          <span class="screen-reader-text"><?php echo esc_html__('Call now', 'panam'); ?></span>
        </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Mobile/Tablet Menu Panel -->
  <nav id="panam-mobile-menu" class="panam-nav-mobile" aria-label="<?php echo esc_attr__('Mobile Primary', 'panam'); ?>" hidden>
    <div class="container">
      <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'fallback_cb'    => false,
          'depth'          => 2,
          'menu_class'     => 'menu panam-menu-mobile',
        ]);
      ?>
    </div>
  </nav>
</header>