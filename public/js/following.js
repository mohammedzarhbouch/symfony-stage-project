document.addEventListener('DOMContentLoaded', function() {

    const followingText = document.getElementById('following-text');
    let uptime = 5000;

    if (followingText) { // Check if the element exists
        setTimeout(function() {
            followingText.style.opacity = '0';
            followingText.style.width = '0';
        }, uptime);
    } else {

    }
});


