



function updateLikeIcon(likeElement) {

const likeButton = likeElement.querySelector('.like');
const likeIcon = likeButton.querySelector('i')
const likeState = likeButton.getAttribute('data-like-state')

    if (likeState === 'true') {
        likeIcon.classList.remove('fa-regular')
        likeIcon.classList.add('fa-solid')
    } else {
        likeIcon.classList.remove('fa-solid')
        likeIcon.classList.add('fa-regular')
    }
}



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

                    const currentState = likeButton.getAttribute('data-like-state') === 'true';
                    likeButton.setAttribute('data-like-state', !currentState);


                    updateLikeIcon(likeElement);

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



// adds a listener to the rating buttons so when clicked they change the rating in the db
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



// return the searched post and render them :)
function renderPosts(posts, userRatings) {
    const postsContainer = document.getElementById('posts-container');
    postsContainer.innerHTML = '';

    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'post';

        let userRating = 0;

        // Find user rating for the current post
        for (let i = 0; i < userRatings.length; i++) {
            let userRatingId = userRatings[i].post.id;

            if (post.id === userRatingId) {
                userRating = userRatings[i].score;
            }
        }

        // Check if the post is liked
        const isLiked = likedPostIds.includes(post.id);

        postElement.innerHTML = `
            <a class="inspect-link" href="inspect-post/${post.id}">
                <div class="title-data">${post.title}</div>
            </a>
            <div class="text-data">${post.text}</div>
            
            <div class="like-container">
                <a class="like" href="{{ path('like-post', {'id': post.id}) }}" data-like-state="${isLiked ? 'true' : 'false'}">
                    <i class="${isLiked ? 'fa-solid fa-heart' : 'fa-regular fa-heart'}"></i>
                </a>
                <div class="total-likes">${post.total_likes}</div>
            </div>
            <a class="rating-text">Rate this post!</a>
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
                
                <div class="homePostedBy">${post.user}</div>
            </div>
        `;

        postsContainer.appendChild(postElement);

        // Update stars for the new post
        const ratingsElement = postElement.querySelector('.ratings');
        updateStars(ratingsElement, userRating);

        const likeElement = postElement.querySelector('.like-container');
        updateLikeButton(likeElement, post.id);


        updateLikeIcon(likeElement);

        addButtonListener();
    });
}





document.addEventListener("DOMContentLoaded", () => {
    addButtonListener();



    // most liked posts filter button
    const mostLikedButton = document.getElementById('most-liked-button')
    mostLikedButton.addEventListener('click', function (event) {
        event.preventDefault();

        fetch('most-liked')
            .then(response => response.json())
            .then(data => {
                renderPosts(data.mostLikedPosts, data.userRatings);
                const likeContainers = document.querySelectorAll('.like-container');


                likeContainers.forEach(likeElement => {
                    if (likeElement) {
                        updateLikeIcon(likeElement)
                    } else {
                        console.warn('likeElement is null');
                    }
                })


            })
            .catch(error => {
                console.error('Error fetching most liked posts:', error);
            });

    });


    //following user posts filter button
    const followingButton = document.getElementById('following-button')
    followingButton.addEventListener('click', function(event) {
        event.preventDefault()

        fetch('following-posts')
            .then(response => response.json())
            .then(data => {
                console.log(data.followedPosts)
                renderPosts(data.followedPosts, data.userRatings)

                const likeContainers = document.querySelectorAll('.like-container');


                likeContainers.forEach(likeElement => {
                    if (likeElement) {
                        updateLikeIcon(likeElement)
                    } else {
                        console.warn('likeElement is null');
                    }
                })



            })

            .catch(error => {
                console.error("TEST", error)
            })
    })





    const likeContainers = document.querySelectorAll('.like-container');
    likeContainers.forEach(likeElement => {
        // console.log('Like containers:', likeContainers);
        const postId = likeElement.querySelector('.like').dataset.id;
        // console.log('Updating like icon for:', likeElement);

        updateLikeButton(likeElement, postId);
        updateLikeIcon(likeElement);
    });


    const searchForm = document.getElementById('search-form');
    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const searchData = new FormData(searchForm);
        const searchQuery = searchData.get('searchInput');

        fetch('search-posts', {
            method: 'POST',
            body: JSON.stringify({searchInput: searchQuery}),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {

                document.querySelector(".total-likes").innerHTML = data.newTotalLikes;
                renderPosts(data.posts, data.userRatings);


            });

    });




});