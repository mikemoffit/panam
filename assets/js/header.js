document.addEventListener('DOMContentLoaded', function () {
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

    window.setTimeout(function () {
      if (!document.documentElement.classList.contains('panam-menu-open')) {
        menu.hidden = true;
      }
    }, 240);
  }

  btn.addEventListener('click', function () {
    const isOpen = btn.getAttribute('aria-expanded') === 'true';
    if (isOpen) closeMenu();
    else openMenu();
  });

  // Close when tapping a link
  menu.addEventListener('click', function (e) {
    const link = e.target.closest('a');
    if (link) closeMenu();
  });

  // Close on resize to desktop
  window.addEventListener('resize', function () {
    if (window.innerWidth >= 1025) closeMenu();
  });

  // Close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMenu();
  });
});