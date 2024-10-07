
// alert("test")


document.addEventListener("DOMContentLoaded", () => {
    const popupText = document.querySelectorAll('.homePostedBy');


    popupText.forEach(text => {

        text.addEventListener('mouseover', () => {

            const popupDisplay = text.previousElementSibling;
                popupDisplay.style.opacity = '1';

        });

        text.addEventListener('mouseout', () => {

            const popupDisplay = text.previousElementSibling;
                popupDisplay.style.opacity = '0';

        });
    });
});
