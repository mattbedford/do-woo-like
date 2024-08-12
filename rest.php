<?php

namespace DoWooLike;


class rest
{
    public function __construct()
    {
      
        add_action('rest_api_init', [self::class, 'registerLoggedOutRoute']);
        add_action('rest_api_init', [self::class, 'registerLoggedInRoute']);
    }


    public static function registerLoggedInRoute()
    {
        register_rest_route('dwl/v1', '/like-logged-in/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [self::class, 'loggedInLikeProduct'],
            'permission_callback' => is_user_logged_in(),
        ]);
    }


    public static function registerLoggedOutRoute()
    {
        register_rest_route('dwl/v1', '/like-logged-out/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [self::class, 'loggedOutLikeProduct'],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function loggedInLikeProduct($data)
    {
        $product_id = $data['id'];
        $likes = get_post_meta($product_id, 'likes', true);
        $likes = empty($likes) ? 1 : $likes + 1;
        update_post_meta($product_id, 'likes', $likes);
		self::userMetaUpdate($product_id);
        return ["status" => true];
    }


    public static function userMetaUpdate($product_id)
    {
        if (!is_user_logged_in()) {
            return;
        }
        $user_id = get_current_user_id();
        $liked_products = get_user_meta($user_id, 'liked_products', true);
        $liked_products = empty($liked_products) ? [] : $liked_products;
		if(!in_array($product_id, $liked_products))  {
			$liked_products[] = $product_id;
		} else {
			$liked_products = array_diff($liked_products, [$product_id]);
		}
        update_user_meta($user_id, 'liked_products', $liked_products);
    }



    // Methods used when user is NOT logged in. Here we update cookies.
    public static function loggedOutLikeProduct($data)
    {
        $product_id = $data['id'];
        $likes = get_post_meta($product_id, 'likes', true);
        $likes = empty($likes) ? 1 : $likes + 1;
        update_post_meta($product_id, 'likes', $likes);
        self::cookieUpdate($product_id);
        return ["status" => true];
    }


    public static function cookieUpdate($product_id)
    {
        
        $liked_products = json_decode($_COOKIE['dwl_liked_products']);
        $liked_products = empty($liked_products) ? [] : $liked_products;
        if (!in_array($product_id, $liked_products)) {
            $liked_products[] = $product_id;
        } else {
            $liked_products = array_diff($liked_products, [$product_id]);
        }
        setcookie('dwl_liked_products', json_encode($liked_products), time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
    }
}