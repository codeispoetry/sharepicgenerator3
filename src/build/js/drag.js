var dragItem;
var active = false;
var currentX;
var currentY;
var initialX;
var initialY;
var xOffset = 0;
var yOffset = 0;

// Add the mousedown event listener
function drag_start(){
    dragItem = document.getElementById('text1');
    active = false;
    xOffset = 0;
    yOffset = 0; // enter value of translate

    dragItem.addEventListener('mousedown', function(e) {
        initialX = e.clientX - xOffset;
        initialY = e.clientY - yOffset;

        if (e.target === dragItem) {
            active = true;
        }
    });
}
drag_start();

// Add the mousemove event listener
document.addEventListener('mousemove', function(e) {
    if (!active) {
        return;
    }

    e.preventDefault();

    currentX = e.clientX - initialX;
    currentY = e.clientY - initialY;

    xOffset = currentX;
    yOffset = currentY;

    dragItem.style.transform = `translate3d(${currentX}px, ${currentY}px, 0)`;
    
});

// Add the mouseup event listener
document.addEventListener('mouseup', function() {
    initialX = currentX;
    initialY = currentY;

    active = false;
});
