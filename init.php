<?php

/**
 * Plugin Name: Do Woo Like
 * Description: A Woo Commerce plugin that allows users to like products.
 * Version:     1.0.0
 * Author:      Dev Team
 * Text Domain: do-woo-like
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace DoWooLike;

include_once 'includes.php';
include_once 'rest.php';
include_once 'cookie-to-meta-on-login.php';

class initDoWooLike
{
    public function __construct()
    {

        if (!class_exists('WooCommerce')) {
            return;
        }

        if (is_admin()) {
            return;
        }

        if (!is_user_logged_in()) {
            add_action('init', [Cookie::class, 'createCookie']);
            add_action('rest_api_init', [new rest(), 'registerLoggedOutRoute']);
            add_action('wp_login', [Cookie::class, 'cookieToMeta']);
        } else {
            add_action('rest_api_init', [new rest(), 'registerLoggedInRoute']);
        }

        add_action('wp_enqueue_scripts', [Includes::class, 'enqueueScripts']);
        add_action('woocommerce_before_shop_loop_item', [Includes::class, 'HeartHtml'], 60);
    }
}
add_action('init', new initDoWooLike());
