document.addEventListener("DOMContentLoaded", () => {

    function updateVote() {
        const voteForms = document.querySelectorAll('.vote-form');

        voteForms.forEach(form => {
            const buttons = form.querySelectorAll('.vote-button');

            buttons.forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent the form from submitting

                    const voteType = button.getAttribute('data-value');
                    const id = button.getAttribute('data-id');

                    fetch(`/test-project/public/comment/vote/${id}/${voteType}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ vote: voteType })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const voteCountElement = document.querySelector(`.vote-count[data-id='${id}']`);
                                voteCountElement.textContent = data.newVoteCount;

                                updateArrowColor(id, voteType);
                            } else {
                                console.error(data.message);
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error)); // Handle any errors
                });
            });
        });
    }


    function updateArrowColor(id, voteType) {

        const upvoteButton = document.querySelector(`.vote-button[data-id='${id}'][data-value='1']`);
        const downvoteButton = document.querySelector(`.vote-button[data-id='${id}'][data-value='0']`);


        upvoteButton.classList.remove('voted');
        downvoteButton.classList.remove('voted');


        if (voteType === '1') {
            upvoteButton.classList.add('voted');
        } else if (voteType === '0') {
            downvoteButton.classList.add('voted');
        }
    }

updateVote()




});
