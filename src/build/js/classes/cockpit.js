class Cockpit{
    // setup(){
    //     let elements = document.querySelectorAll('.selectable');
    //     elements.forEach(element => {
            
    //         let cockpit_element = 'setup_' + element.dataset.cockpit;
    //         if (typeof this[cockpit_element] === 'function') {
    //             this[cockpit_element]();
    //         }
    //     });
    // }

    clear_all(){
        let elements = document.querySelectorAll('#cockpit .show');
        elements.forEach(element => {
            element.classList.remove("show");
        });
        document.getElementById('cockpit_sharepic')?.classList.add("show");
    }

    show(element){
        let elements = document.querySelectorAll('#cockpit .show');
        elements.forEach(element => {
            element.classList.remove("show");
        });

        let cockpit_element = 'setup_' + element.dataset.cockpit;
        if (typeof this[cockpit_element] === 'function') {
            this[cockpit_element](element);
        }

        const id = "cockpit_" + element.dataset.cockpit;
        document.getElementById(id)?.classList.add("show");

    }

    setup_text(element){
        const slider = document.getElementById('text_size');
        slider.value = element.style.fontSize.replace("px","");
    }

    setup_eyecatcher(element){
        const slider = document.getElementById('eyecatcher_size');
        slider.value = element.style.fontSize.replace("px","");
    }

}