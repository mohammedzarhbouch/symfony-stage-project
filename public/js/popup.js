document.addEventListener("DOMContentLoaded", () => {
    const popupText = document.querySelectorAll('.homePostedBy');

    popupText.forEach(text => {
        text.addEventListener('mouseover', () => {
            const popupDisplay = text.previousElementSibling;

            if (popupDisplay) {
                popupDisplay.style.opacity = '1';
                popupDisplay.style.zIndex = "200";
            }
        });

        text.addEventListener('mouseout', () => {
            const popupDisplay = text.previousElementSibling;
            if (popupDisplay) {
                popupDisplay.style.opacity = '0';
                popupDisplay.style.zIndex = "-100";
            }
        });
    });
});
