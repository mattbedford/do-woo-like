<?php

namespace DoWooLike;

// This is a kinda singleton to contain all the peripheral methods.
class Includes
{
    

    public static function heartHtml()
    {
		
        $product_id = get_the_ID();
        $class = self::productIsLiked($product_id);

		echo '<div class="likes-wrapper' . $class . '" data-product-id="' . $product_id . '">';
        echo '<div class="heart-icon">';
        echo '<svg aria-hidden="true" role="img" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" viewBox="-40 0 600 600"><path fill="currentColor" d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path></svg>';
        echo '</div>';
        echo '</div>';

    }
	

    public static function productIsLiked($product_id) 
    {
        if (is_user_logged_in()) {
            return self::logged_in($product_id) ? ' liked' : '';
        }
        return self::logged_out($product_id) ? ' liked' : '';
    }

	
    private static function logged_in($product_id)
    {

        $liked_products = get_user_meta( get_current_user_id() , 'liked_products', true);
        $liked_products = empty($liked_products) ? [] : $liked_products;

        if (!in_array($product_id, $liked_products)) return true;
        return false;
    }

    private static function logged_out($product_id)
    {

        if (!isset($_COOKIE['dwl_liked_products'])) return false;
        $liked_products = json_decode($_COOKIE['dwl_liked_products']);
        $liked_products = empty($liked_products) ? [] : $liked_products;
        
        if (in_array(strval($product_id), $liked_products)) {
            return true;
        }
        return false;
    }
  

    public static function enqueueScripts()
    {
        wp_enqueue_style('do-woo-like-styles', plugin_dir_url(__FILE__) . '/assets/style.css');
      	wp_enqueue_script('do-woo-like-js', plugin_dir_url(__FILE__) . '/assets/scripts.js', [], '1.0.0', true);
        
        wp_register_script('do-woo-like-rest', false);
        $rest_args = [
            'security' => wp_create_nonce('wp_rest'),
        ];
        $rest_args['url'] = rest_url('dwl/v1/like-logged-out/');
        if(is_user_logged_in()) {
            $rest_args['url'] = rest_url('dwl/v1/like-logged-in/');
        }
        
        wp_localize_script('do-woo-like-rest', 'doWooLike', $rest_args);
      	wp_enqueue_script('do-woo-like-rest');
    }



}
