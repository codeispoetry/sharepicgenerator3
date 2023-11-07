var api, cockpit, select;

window.onload = function() {
    api = new API();
    api.load();

    cockpit = new Cockpit();
    select = new Select();

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

};
