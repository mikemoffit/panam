<footer class="site-footer">
  <div class="container">
    <small>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></small>

    <nav class="nav nav-footer" aria-label="Footer">
      <?php
        wp_nav_menu([
          'theme_location' => 'footer',
          'container' => false,
          'fallback_cb' => false,
          'depth' => 1,
        ]);
      ?>
    </nav>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const btn = document.querySelector('.panam-burger');
  const menu = document.getElementById('panam-mobile-menu');
  if (!btn || !menu) return;

  btn.addEventListener('click', function(){
    const isOpen = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', String(!isOpen));
    menu.hidden = isOpen;
  });
});
</script>

<?php wp_footer(); ?>
</body>
</html>