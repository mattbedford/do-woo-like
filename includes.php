<?php

namespace DoWooLike;


class includes
{
    public function __construct()
    {
        add_action('woocommerce_before_shop_loop_item', [self::class, 'HeartHtml'], 60 );
    }


    public static function HeartHtml()
    {
        $product_id = get_the_ID();
        echo '<div class="likes-wrapper" data-product-id=' . $product_id . '>';
        echo '<div class="heart-icon">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 30 28">
            <path fill="#0093D3" fill-rule="evenodd" d="M21.32 0A8.64 8.64 0 0 0 15 2.74 8.64 8.64 0 0 0 8.68 0a8.7 8.7 0 0 0-6.26 14.75v.01L13.7 26.14l.81.82a.69.69 0 0 0 .98 0l.81-.82 11.05-11.16A8.7 8.7 0 0 0 21.32 0Zm-.24 2.53a6.15 6.15 0 0 0-5.44 3.8.69.69 0 0 1-1.27 0A6.16 6.16 0 0 0 2.52 8.72 6.2 6.2 0 0 0 4.24 13L15 23.87a846884.75 846884.75 0 0 1 10.58-10.68 6.2 6.2 0 0 0-4.26-10.67h-.24Z" clip-rule="evenodd"/>
            </svg>';
        echo '</div>';
        echo '</div>';

    }

}