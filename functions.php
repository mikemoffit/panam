<?php
if (!defined('ABSPATH')) exit;

define('PANAM_THEME_VERSION', '1.0.0');
define('PANAM_THEME_DIR', get_template_directory());
define('PANAM_THEME_URI', get_template_directory_uri());

require_once PANAM_THEME_DIR . '/inc/setup.php';
require_once PANAM_THEME_DIR . '/inc/assets.php';
require_once PANAM_THEME_DIR . '/inc/template-tags.php';

require_once PANAM_THEME_DIR . '/inc/options/register.php';
require_once PANAM_THEME_DIR . '/inc/options/fields.php';
require_once PANAM_THEME_DIR . '/inc/options/sanitize.php';
require_once PANAM_THEME_DIR . '/inc/options/render.php';
require_once PANAM_THEME_DIR . '/inc/options/media.php';

require_once PANAM_THEME_DIR . '/inc/integrations/gtm.php';
require_once PANAM_THEME_DIR . '/inc/integrations/callrail.php';