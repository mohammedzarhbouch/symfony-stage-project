document.addEventListener('DOMContentLoaded', function() {

    let ratings = document.querySelectorAll('.ratings');

    ratings.forEach(rating => {
        let userRating = parseInt(rating.getAttribute('data-user-rating'));
        let ratingButtons = rating.querySelectorAll('.rating-button');


        function updateStars(newRating) {
            ratingButtons.forEach(button => {
                const buttonValue = parseInt(button.getAttribute('data-value'));

                if (buttonValue <= newRating) {  // Use newRating here
                    button.querySelector('i').classList.remove('fa-regular');
                    button.querySelector('i').classList.add('fa-solid');


                } else {
                    button.querySelector('i').classList.remove('fa-solid');
                    button.querySelector('i').classList.add('fa-regular');
                }

                console.log(newRating);



            });
        }


        updateStars(userRating);


        ratingButtons.forEach(button => {
            button.addEventListener('click', function() {
                let newRating = parseInt(button.getAttribute('data-value'));


                updateStars(newRating);


            });
        });
    });
});
