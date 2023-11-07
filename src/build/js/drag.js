var dragging = false;
var drag_items = new Array();

function register_draggable_items(){
    let draggableElements = document.querySelectorAll('.draggable');
    draggableElements.forEach((element, index) => {
        drag_items[index] = new Drag(element.id, index);
    });
}

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
