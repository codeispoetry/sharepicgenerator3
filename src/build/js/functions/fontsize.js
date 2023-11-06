function font_size(event){
    var element = event.target;

    const target = document.getElementById('text1');

    target.style.fontSize = element.value + "px";
}
