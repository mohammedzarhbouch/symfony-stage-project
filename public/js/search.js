
document.addEventListener("DOMContentLoaded", () => {

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
            })


        function renderPosts(posts, userRatings) {
            const postsContainer = document.getElementById('posts-container');
            postsContainer.innerHTML = '';


            posts.forEach(post => {

                const postElement = document.createElement('div');
                postElement.className = 'post';

                console.log(userRatings);

                let userRating = 0;

                for (let i = 0; i < userRatings.length; i++) {
                    let userRatingId = userRatings[i].post;

                    if (post.id == userRatingId) {
                        userRating = userRatings[i].score;
                    }
                }

                postElement.innerHTML = `
            <a class="inspect-link" href="/inspect-post/${post.id}">
                <div class="title-data">${post.title}</div>
            </a>
            <div class="text-data">${post.text}</div>
            
            <div class="inspect-rating">
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
            </div>
            
            <div class="home-post-footer">
                <a class="home-post-date">${post.date}</a>
           
           
                
       
                <div class="homePostedBy">${post.user}</div>
            </div>

        `;



                postsContainer.appendChild(postElement);

            });
        }
    });


});
