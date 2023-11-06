function font_size(event){
    // element, that is clicked
    var element = event.target;
    
    console.log(element.value)

    const target = document.getElementById('text1');

    target.style.fontSize = element.value + "px";
}
