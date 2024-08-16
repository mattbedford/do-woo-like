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

	
    public static function cookieToMeta($user_string, $user_obj)
    {
        if (!isset($_COOKIE['dwl_liked_products'])) {
          	return;
        }
        
        $liked_products = json_decode($_COOKIE['dwl_liked_products']);
        $user_id = $user_obj->ID;
        if(0 === $user_id) return;
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

