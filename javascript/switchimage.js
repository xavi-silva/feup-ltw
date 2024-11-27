var currentIndex = 0;

function changeImage(direction) {
    currentIndex += direction;
    
    if (currentIndex < 0) {
        currentIndex = images.length - 1;
    } else if (currentIndex >= images.length) {
        currentIndex = 0;
    }
    
    document.getElementById("product").src = images[currentIndex];
}