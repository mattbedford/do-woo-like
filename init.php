<?php
/**
 * Plugin Name: Do Woo Like
 * Description: A Woo Commerce plugin that allows users to like products.
 * Version:     1.0.0
 * Author:      Matt Bedford
 * Text Domain: do-woo-like
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace DoWooLike;

include_once 'includes.php';
include_once 'rest.php';
include_once 'cookie-to-meta-on-login.php';
new includes();
new CookieToMetaOnLogin();
new rest();