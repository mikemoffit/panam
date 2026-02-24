<?php
if (!defined('ABSPATH')) exit;

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