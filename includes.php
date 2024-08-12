<?php

namespace DoWooLike;


class includes
{

    public static string $logged_status = "logged_out";
    public static int $user_id = 0;

    public function __construct()
    {

		add_action('init', [self::class, 'checkUser'], 10);
        add_action('init', [self::class, 'createCookie'], 20);
    
        add_action('wp_enqueue_scripts', [self::class, 'enqueueScripts']);  
      	add_action('woocommerce_before_shop_loop_item', [self::class, 'HeartHtml'], 60 );
      
    }

    // Either set user ID and "logged in" else create a cookie and set "logged out".
    // Logged in or out is a polymorphic method to evaluate the liked status of our displayed products.
    public static function checkUser()
    {
        $id = get_current_user_id();
        if (0 !== $id) {
            self::$logged_status = "logged_in";
            self::$user_id = $id;
        } else {
            self::createCookie();
        }
    }

    public static function HeartHtml()
    {
		
        $product_id = get_the_ID();
		$action = self::$logged_status;

		if(self::$action($product_id)) {
			echo '<div class="likes-wrapper liked"	data-product-id="' . $product_id . '">';
		} else {
			echo '<div class="likes-wrapper" data-product-id="' . $product_id . '">';
		}
		
        echo '<div class="heart-icon">';
        echo '<svg aria-hidden="true" role="img" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" viewBox="-40 0 600 600"><path fill="currentColor" d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path></svg>';
        echo '</div>';
        echo '</div>';

    }
	
	
    public static function logged_in($product_id)
    {
        if (!\is_user_logged_in()) {
            return false;
        }
		
        $liked_products = get_user_meta(self::$user_id, 'liked_products', true);
        $liked_products = empty($liked_products) ? [] : $liked_products;

        if (!in_array($product_id, $liked_products)) return true;
        return false;
    }

    public static function logged_out($product_id)
    {
        if (\is_user_logged_in()) {
            return false;
        }

        $liked_products = json_decode($_COOKIE['dwl_liked_products']);
        $liked_products = empty($liked_products) ? [] : $liked_products;
        
        if (!in_array($product_id, $liked_products)) {
            return true;
        }
        return false;
    }
  

    public static function enqueueScripts()
    {
        wp_enqueue_style('do-woo-like-styles', plugin_dir_url(__FILE__) . '/assets/style.css');
      	wp_enqueue_script('do-woo-like-js', plugin_dir_url(__FILE__) . '/assets/scripts.js', [], '1.0.0', true);
        wp_localize_script('do-woo-like-js', 'doWooLike', [
            'security' => wp_create_nonce('wp_rest'),
        ]);
    }


    public static function createCookie()
    {
        if (\is_user_logged_in()) {
            return;
        }
        if (!isset($_COOKIE['liked_products'])) {
            setcookie('dwl_liked_products', json_encode([]), time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
        }
    }


}
