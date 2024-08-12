document.addEventListener('DOMContentLoaded', function() {

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
        let url = `/wp-json/dwl/v1/like/${ProdId}`;
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
        .catch((error) => {
            console.error('Error:', error);
        });
    };
});

