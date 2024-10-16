







function addButtonListener() {
    const ratingButtons = document.querySelectorAll('.rating-button');

    ratingButtons.forEach(button => {

        // console.log("Attaching listener to button", button);

        button.addEventListener('click', () => {
            // console.log("click")

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
                    console.log(data)


                });

        });
    });

}



document.addEventListener("DOMContentLoaded", () => {
    addButtonListener();
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


                renderPosts(data.posts, data.userRatings);

                addButtonListener();

            })


        function renderPosts(posts, userRatings) {
            const postsContainer = document.getElementById('posts-container');
            postsContainer.innerHTML = '';


            posts.forEach(post => {

                const postElement = document.createElement('div');
                postElement.className = 'post';

                console.log(userRatings);

                let userRating = 0;

                //FOR LOOP JS VERSION OF TWIG FOR LOOP

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

            });

        }
    });


});
