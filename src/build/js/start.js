window.onload = function() {
    const api = new API();
    api.load();

    document.getElementById('create').addEventListener('click', function() {
        api.create();
    });

    document.getElementById('reset').addEventListener('click', function() {
        api.load();
    });

    document.getElementById('load_latest').addEventListener('click', function() {
        api.load('user/tom/workspace/sharepic.html');
    });
};
