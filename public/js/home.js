


function updateLikeButton(likeElement, postId) {
    const likeButton = likeElement.querySelector('.like');
    const totalLikesElement = likeElement.querySelector('.total-likes');

    likeButton.addEventListener('click', (event) => {
        event.preventDefault();

        fetch(`/test-project/public/like-post/${postId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the total likes count
                    totalLikesElement.textContent = data.newTotalLikes;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
}

function updateStars(ratingsElement, newRating) {
    const ratingButtons = ratingsElement.querySelectorAll('.rating-button');
    ratingButtons.forEach(button => {
        const buttonValue = parseInt(button.getAttribute('data-value'));
        const icon = button.querySelector('i');

        if (buttonValue <= newRating) {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
        } else {
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
        }
    });
}




function addButtonListener() {
    const ratingButtons = document.querySelectorAll('.rating-button');

    ratingButtons.forEach(button => {
        button.addEventListener('click', () => {
            let ratingValue = button.getAttribute('data-value');
            let id = button.getAttribute('data-id');


            fetch(`/test-project/public/rating/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ rating: ratingValue })
            })
                .then(response => response.json())


                    const ratingsElement = button.closest('.ratings');
                    updateStars(ratingsElement, ratingValue);




        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    addButtonListener();


    const likeContainers = document.querySelectorAll('.like-container');
    likeContainers.forEach(likeElement => {
        const postId = likeElement.querySelector('.like').dataset.id;
        updateLikeButton(likeElement, postId);
    });



    const searchForm = document.getElementById('search-form');
    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const searchData = new FormData(searchForm);
        const searchQuery = searchData.get('searchInput');

        fetch('search-posts', {
            method: 'POST',
            body: JSON.stringify({ searchInput: searchQuery }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {

                document.querySelector(".total-likes").innerHTML = data.newTotalLikes;
                renderPosts(data.posts, data.userRatings);

                console.log(data.posts)
                addButtonListener();
            });


        // return the searched post and render them :)
        function renderPosts(posts, userRatings) {
            const postsContainer = document.getElementById('posts-container');
            postsContainer.innerHTML = '';

            posts.forEach(post => {
                const postElement = document.createElement('div');
                postElement.className = 'post';

                let userRating = 0;

                // FOR LOOP JS VERSION OF TWIG FOR LOOP
                for (let i = 0; i < userRatings.length; i++) {
                    let userRatingId = userRatings[i].post;

                    if (post.id === userRatingId) {
                        userRating = userRatings[i].score;
                    }
                }

                postElement.innerHTML = `
                    <a class="inspect-link" href="inspect-post/${post.id}">
                        <div class="title-data">${post.title}</div>
                    </a>
                    <div class="text-data">${post.text}</div>
                    
                      <div class="like-container">
                            <a class="like" href="{{ path('like-post', {'id': post.id}) }}">
                                <i class="fa-regular fa-heart"></i>
                            </a>
                            <div class="total-likes">${post.total_likes}</div>
                        </div>
                    
                    
                    
                    <div class="ratings" data-user-rating="${userRating}">
                        <div class="rating-button-container">
                            <a class="rating-button" data-value="1" data-id="${post.id}">
                                <i class="${userRating >= 1 ? 'fa-solid fa-star' : 'fa-regular fa-star'}"></i>
                            </a>
                            <a class="rating-button" data-value="2" data-id="${post.id}">
                                <i class="${userRating >= 2 ? 'fa-solid fa-star' : 'fa-regular fa-star'}"></i>
                            </a>
                            <a class="rating-button" data-value="3" data-id="${post.id}">
                                <i class="${userRating >= 3 ? 'fa-solid fa-star' : 'fa-regular fa-star'}"></i>
                            </a>
                            <a class="rating-button" data-value="4" data-id="${post.id}">
                                <i class="${userRating >= 4 ? 'fa-solid fa-star' : 'fa-regular fa-star'}"></i>
                            </a>
                            <a class="rating-button" data-value="5" data-id="${post.id}">
                                <i class="${userRating >= 5 ? 'fa-solid fa-star' : 'fa-regular fa-star'}"></i>
                            </a>
                        </div>
                    </div>
                    <div class="home-post-footer">
                        <a class="home-post-date">${post.date}</a>
                        <div class="popup">
                            <a>Posts: ${post.user.postCount}</a>
                            <a>Comments: ${post.user.commentCount}</a>
                        </div>
                        <div class="homePostedBy">${post.user}</div>
                    </div>
                `;

                postsContainer.appendChild(postElement);

                // Update stars for the new post
                const ratingsElement = postElement.querySelector('.ratings');
                updateStars(ratingsElement, userRating);

                const likeElement = postElement.querySelector('.like-container');
                updateLikeButton(likeElement, post.id);
            });
        }




    });


});
