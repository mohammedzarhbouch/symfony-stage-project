//
// document.addEventListener("DOMContentLoaded", () => {
//
//     const searchForm = document.getElementById('search-form');
//     searchForm.addEventListener('submit', function (event) {
//         event.preventDefault();
//         const searchData = new FormData(searchForm);
//         const searchQuery = searchData.get('searchInput');
//
//         fetch('search-posts', {
//             method: 'POST',
//             body: JSON.stringify({ searchInput: searchQuery }),
//             headers: {
//                 'Content-Type': 'application/json'
//             }
//         })
//             .then(response => response.json())
//             .then(data => {
//                 console.log(data.posts);
//
//                 renderPosts(data.posts);
//             })
//
//
//         function renderPosts(posts) {
//             const postsContainer = document.getElementById('posts-container');
//             postsContainer.innerHTML = '';
//
//
//             posts.forEach(post => {
//                 console.log('Current Post:', post);
//                 const postElement = document.createElement('div');
//                 postElement.className = 'post';
//
//                 postElement.innerHTML = `
//             <a class="inspect-link" href="/inspect-post/${post.id}">
//                 <div class="title-data">{{ post.title }}</div>
//             </a>
//             <div class="text-data">{{ post.text }}</div>
//
//         `;
//                 postsContainer.appendChild(postElement);
//             });
//         }
//     });
// });
