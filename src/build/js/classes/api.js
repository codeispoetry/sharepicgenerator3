
class API{
    constructor(){
        this.api = '/index.php/sharepic/';
    }

    load(path = 'tenants/de/start.html'){
        var data = { 
            template: path
         };
    
        var options = {
            method: 'POST',
            headers: {
                'Content-Type': 'text/html'
            },
            body: JSON.stringify(data)
        };
    
        fetch(this.api + 'load', options)
            .then(response => response.text())
            .then(data => {
                document.getElementById('canvas').innerHTML = data;
                drag_start();
            })
            .catch(error => console.error('Error:', error));
    }

    create(){
        var canvas = document.getElementById('canvas');
        var data = { 
            data: canvas.outerHTML,
            size: {
                width:  document.getElementById('width').value,
                height:  document.getElementById('height').value
            }
         };
    
        var options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        };
    
        fetch(this.api + 'create', options)
            .then(response => response.json())
            .then(data => {
                // append img element with id output
                
                var img = document.getElementById('output');
                img.src = 'output.png?rand=' + Math.random();
        
                console.log("Data", data)
            })
           // .catch(error => console.error('Error:', error));
    }
}
