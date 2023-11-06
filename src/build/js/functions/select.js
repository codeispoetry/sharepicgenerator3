function set_active(event){
    if( dragging === true ){
        return;
    }
    
    var element = event.target;
    
    element.classList.toggle("active");
}
