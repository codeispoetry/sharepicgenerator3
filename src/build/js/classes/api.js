
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
                drag_items[1] = new Drag('text1', 1);
                drag_items[2] = new Drag('eyecatcher', 2);
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
            .then(response => {
                if(response.status === 403) {
                    throw new Error('Access is not allowed');
                }
                return response.json();
            })
            .then(data => {      
                console.log(data);         
                var img = document.getElementById('output');
                img.src = '/' + data.path + '?rand=' + Math.random();
        
            })
           .catch(error => console.error('Error:', error));
    }
}
