document.addEventListener('DOMContentLoaded', function() {


    var likedProducts = null;
    if (doWooLike.user_status === 'logged-out') {
        likedProducts = getCookieObject();
    }
    
    if(likedProducts) {
        likedProducts.forEach(function(likedProduct) {
            var likeButton = document.querySelector('.likes-wrapper[data-product-id="' + likedProduct + '"]');
            likeButton.classList.add('liked');
        });
    }

	var likeButtons = document.querySelectorAll('.likes-wrapper');

	likeButtons.forEach(function(button) {

        button.addEventListener("click", function(event) {
			event.preventDefault();
			let ref = event.target.closest('.likes-wrapper');
			let ProdId = ref.dataset.productId;
			switchLikeStatus(ref);
			doLike(ProdId, ref);
		});
	});
	
	function switchLikeStatus(ref) {
        if(ref.classList.contains('liked')) {
            ref.classList.remove('liked');
        } else {
            ref.classList.add('liked');
        }
    }

    function doLike(ProdId, ref) {
        let url = doWooLike.url + ProdId;
        let data = {
            'product_id': ProdId
        };
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': doWooLike.security
            }
        })
        .then(response => response.json())
        .catch((error) => {
            console.error('Error:', error);
        });
    };
});

function getCookieObject() {
    var cookies = document.cookie.split(';');
    var likedProducts = cookies.find(function(cookie) {
        return cookie.includes('dwl_liked_products');
    });
	if (!likedProducts) {
        return [];
    }
    var likedProductsArray = likedProducts.split('=')[1];
    return JSON.parse(decodeURIComponent(likedProductsArray));
}
