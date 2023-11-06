
document.getElementById('create').addEventListener('click', function() {
    // Data to send to the server
    // get html from id canvas
    var canvas = document.getElementById('canvas');
    var data = { 
        data: canvas.outerHTML,
        size: {
            width:  document.getElementById('width').value,
            height:  document.getElementById('height').value
        }
     };

    // Options for the fetch function
    var options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };

    // Send the data to the server
    fetch('/index.php/sharepic/create', options)
        .then(response => response.json())
        .then(data => {
            // append img element with id output
            
            var img = document.getElementById('output');
            img.src = 'output.png?rand=' + Math.random();
    
            console.log("Data", data)
        })
       // .catch(error => console.error('Error:', error));
});
