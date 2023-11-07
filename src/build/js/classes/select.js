class Select {
    setup(){
        this.clear_all();
        let elements = document.querySelectorAll('.selectable');
        elements.forEach(element => {
            element.addEventListener('click', (event) => {
                event.stopPropagation();
                this.set_active(event.target);

            });
        });

        let element = document.getElementById('canvas');
        element.addEventListener('click', (event) => {
           this.set_active(event.target);
        });
    }

    clear_all(){
        let elements = document.querySelectorAll('.selectable.active');
        elements.forEach(element => {
            element.classList.remove("active");
        });
        cockpit.clear_all();
    }

    set_active(element){
        if( dragging === true ){
            return;
        }
        let elements = document.querySelectorAll('.selectable');
            elements.forEach(element => {
                element.classList.remove("active");
            }  
        );
        
        element.classList.add("active");

        cockpit.show(element);
    }
}