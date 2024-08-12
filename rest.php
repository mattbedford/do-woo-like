<?php

namespace DoWooLike;


class rest
{
    public function __construct()
    {
        add_action('rest_api_init', [self::class, 'registerRoute']);
    }

    public static function registerRoute()
    {
        register_rest_route('dwl/v1', '/like/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [self::class, 'likeProduct'],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function likeProduct($data)
    {
        $product_id = $data['id'];
        $likes = get_post_meta($product_id, 'likes', true);
        $likes = empty($likes) ? 1 : $likes + 1;
        update_post_meta($product_id, 'likes', $likes);
		self::LikeForLoggedInUser($product_id);
        return ["status" => true];
    }


    public static function LikeForLoggedInUser($product_id)
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
}