document.addEventListener('DOMContentLoaded', function() {

	var likeButtons = document.querySelectorAll('.likes-wrapper');

	likeButtons.forEach(function(button) {
		button.addEventListener("click", function(event) {
			event.preventDefault();
			let ref = event.target.closest('.likes-wrapper');
			let ProdId = ref.dataset.productId;
			doLike(ProdId, ref);
		});
	});

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
        .then(response => response.json())
        .then(data => {
			if(data.status === true) {
				ref.classList.add('liked');
			}
            console.log(data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    };
});

