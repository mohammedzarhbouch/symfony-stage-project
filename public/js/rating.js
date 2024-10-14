document.addEventListener('DOMContentLoaded', () => {
    const ratingButtons = document.querySelectorAll('.rating-button');

    ratingButtons.forEach(button => {
        button.addEventListener('click', () => {
            let ratingValue = button.getAttribute('data-value'); // Get the rating value
            let id = button.getAttribute('data-id'); // Get the post ID

            // Send the AJAX request to rate the post
            fetch(`/test-project/public/rating/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ rating: ratingValue })
            })
                .then(response => response.json())

                .then(data => {

                    if (data.newAverageRating) {

                        let ratingDisplay = document.querySelector(`.rating-total[data-id="${id}"]`);
                        ratingDisplay.textContent = parseFloat(data.newAverageRating).toFixed(2);

                    }



                });

        });
    });
});
