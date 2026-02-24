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

  function openMenu() {
    btn.setAttribute('aria-expanded', 'true');
    menu.hidden = false;
    document.documentElement.classList.add('panam-menu-open');
  }

  function closeMenu() {
    btn.setAttribute('aria-expanded', 'false');
    document.documentElement.classList.remove('panam-menu-open');

    // Wait for the transition to finish, then hard-hide
    window.setTimeout(function(){
      if (!document.documentElement.classList.contains('panam-menu-open')) {
        menu.hidden = true;
      }
    }, 240);
  }

  btn.addEventListener('click', function(){
    const isOpen = btn.getAttribute('aria-expanded') === 'true';
    if (isOpen) closeMenu();
    else openMenu();
  });

  // Optional niceties:
  // Close menu when tapping a link
  menu.addEventListener('click', function(e){
    const link = e.target.closest('a');
    if (link) closeMenu();
  });

  // Close menu on resize to desktop
  window.addEventListener('resize', function(){
    if (window.innerWidth >= 1025) closeMenu();
  });
});
</script>

<?php wp_footer(); ?>
</body>
</html>