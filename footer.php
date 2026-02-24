<footer class="site-footer">
  <div class="container">
    <small>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></small>

    <nav class="nav nav-footer" aria-label="<?php echo esc_attr__('Footer', 'panam'); ?>">
      <?php
        wp_nav_menu([
          'theme_location' => 'footer',
          'container'      => false,
          'fallback_cb'    => false,
          'depth'          => 1,
          'menu_class'     => 'menu panam-menu-footer',
        ]);
      ?>
    </nav>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>