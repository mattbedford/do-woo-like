<?php

namespace DoWooLike;


class CookieToMetaOnLogin {

    // Add an action to be called ONLY when a user logs in.
    public function __construct()
    {
        add_action('wp_login', [self::class, 'cookieToMeta']);
    }

    public static function cookieToMeta()
    {
        if (!isset($_COOKIE['dwl_liked_products'])) {
            return;
        }
        $liked_products = json_decode($_COOKIE['dwl_liked_products']);
        $user_id = get_current_user_id();
        $user_liked_products = get_user_meta($user_id, 'liked_products', true);
        $user_liked_products = empty($user_liked_products) ? [] : $user_liked_products;
        
        foreach ($liked_products as $product_id) {
            if (!in_array($product_id, $user_liked_products)) {
                $user_liked_products[] = $product_id;
            }
        }
        update_user_meta($user_id, 'liked_products', $user_liked_products);
        setcookie('dwl_liked_products', json_encode([]), time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
    }

}