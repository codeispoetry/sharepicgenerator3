var api, cockpit, select, undo, pixabay;

window.onload = function() {
    api = new API();
    api.load();

    cockpit = new Cockpit();
    select = new Select();
    undo = new Undo();
    pixabay = new Pixabay();

    document.getElementById('create').addEventListener('click', function() {
        api.create();
    });

    document.getElementById('reset').addEventListener('click', function() {
        api.load();
    });

    document.getElementById('load_latest').addEventListener('click', function() {
        api.load('users/tom/workspace/sharepic.html');
    });

    document.getElementById('canvas').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    document.querySelectorAll('.to-front').forEach(element => {
        element.addEventListener('click', (event) => {

            let highestZIndex = [...document.querySelectorAll('.draggable')].reduce((maxZIndex, element) => {
                let zIndex = parseInt(getComputedStyle(element).zIndex, 10);
                return isNaN(zIndex) ? maxZIndex : Math.max(maxZIndex, zIndex);
            }, 0);

            const target = element.dataset.target;
            document.getElementById(target).style.zIndex = (highestZIndex + 1).toString();
        });
    });

    document.querySelectorAll('.delete').forEach(element => {
        element.addEventListener('click', (event) => {

            const target = element.dataset.target;
            document.getElementById(target).remove();
        });
    });

    document.querySelectorAll('.closer').forEach(element => {
        element.addEventListener('click', (event) => {

            const target = element.dataset.target;
            document.getElementById(target).classList.remove('show');
        });
    });

};
