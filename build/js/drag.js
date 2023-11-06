
// Get the element that you want to make draggable
var dragItem = document.getElementById('text1');
var active = false;
var currentX;
var currentY;
var initialX;
var initialY;
var xOffset = 0;
var yOffset = 0;

// Add the mousedown event listener
dragItem.addEventListener('mousedown', function(e) {
    initialX = e.clientX - xOffset;
    initialY = e.clientY - yOffset;

    if (e.target === dragItem) {
        active = true;
    }
});

// Add the mousemove event listener
document.addEventListener('mousemove', function(e) {
    if (active) {
        e.preventDefault();

        currentX = e.clientX - initialX;
        currentY = e.clientY - initialY;

        xOffset = currentX;
        yOffset = currentY;

        dragItem.style.transform = `translate3d(${currentX}px, ${currentY}px, 0)`;
    }
});

// Add the mouseup event listener
document.addEventListener('mouseup', function() {
    initialX = currentX;
    initialY = currentY;

    active = false;
});
