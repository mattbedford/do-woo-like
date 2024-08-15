<?php

namespace DoWooLike;


class Cookie
{


    public static function createCookie()
    {

        if (!isset($_COOKIE['dwl_liked_products'])) {
            setcookie('dwl_liked_products', json_encode([]), time() + (86400 * 30), "/");
        }
    }


    public static function checkCookie() 
    {
		if (!isset($_COOKIE['dwl_liked_products'])) {
            echo "<script>let likedProducts = JSON.parse('".json_encode([])."');</script>";
			return;
        }
        $liked_products = json_decode($_COOKIE['dwl_liked_products']);
		echo "<script>let likedProducts = JSON.parse('".json_encode($liked_products)."');</script>";
    }

	
    public static function cookieToMeta()
    {
        if(!is_user_logged_in()) return;

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
        setcookie('dwl_liked_products', json_encode([]), time() + (86400 * 30), "/");
    }
}
