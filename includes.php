<?php

namespace DoWooLike;


class includes
{
    public function __construct()
    {
            add_action('wp_enqueue_scripts', [self::class, 'enqueueScripts']);  
      		add_action('woocommerce_before_shop_loop_item', [self::class, 'HeartHtml'], 60 );
      
    }


    public static function HeartHtml()
    {
		
        $product_id = get_the_ID();
		
		if(self::CheckIfUserLiked($product_id)) {
			echo '<div class="likes-wrapper liked"	data-product-id="' . $product_id . '">';
		} else {
			echo '<div class="likes-wrapper" data-product-id="' . $product_id . '">';
		}
		
        echo '<div class="heart-icon">';
        echo '<svg aria-hidden="true" role="img" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" viewBox="-40 0 600 600"><path fill="currentColor" d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path></svg>';
        echo '</div>';
        echo '</div>';

    }
	
	
    public static function CheckIfUserLiked($product_id)
    {

        $user_id = get_current_user_id();
		if(0 === $user_id || empty($user_id)) return false;
		
        $liked_products = get_user_meta($user_id, 'liked_products', true);
        $liked_products = empty($liked_products) ? [] : $liked_products;

        if (!in_array($product_id, $liked_products)) return true;
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

}
