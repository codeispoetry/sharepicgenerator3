var dragging = false;
var drag_items = new Array();

// Add the mousemove event listener
document.addEventListener('mousemove', function(e) {
    if( dragging === false ){
        return;
    }

    e.preventDefault();

    const x = e.clientX;
    const y = e.clientY;

    // access object by variable name
    drag_items[dragging].move(x, y);
    
});

// Add the mouseup event listener
document.addEventListener('mouseup', function(e) {
    dragging = false;
});
