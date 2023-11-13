/* eslint-disable no-undef, no-unused-vars */

class API {
  constructor () {
    this.api = '/index.php/sharepic/'
  }

  load (path = 'tenants/de/start.html') {
    const data = {
      template: path
    }

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'text/html'
      },
      body: JSON.stringify(data)
    }

    fetch(this.api + 'load', options)
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {
        document.getElementById('canvas').innerHTML = data
        registerDraggableItems()
        select.setup()
        cockpit.setup_sharepic()
        rte.init()
      })
      .catch(error => console.error('Error:', error))
  }

  create () {
    select.unselect_all()
    const canvas = document.getElementById('canvas')

    const clonedCanvas = canvas.cloneNode(true)
    clonedCanvas.querySelector('.ql-hidden')?.remove();
    clonedCanvas.querySelector('.ql-toolbar')?.remove();
    clonedCanvas.querySelector('.ql-tooltip')?.remove();
    clonedCanvas.querySelector('.ql-clipboard')?.remove();
    clonedCanvas.querySelector('#patterns')?.remove();

    const link = `<link rel="stylesheet" href="../../../assets/styles.css">\n<link rel="stylesheet" href="../../../node_modules/quill/dist/quill.bubble.css">\n\n`;
    clonedCanvas.insertAdjacentHTML('afterbegin',link);

    const data = {
      data: clonedCanvas.innerHTML,
      size: {
        width: document.getElementById('width').value,
        height: document.getElementById('height').value
      }
    }

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }

    fetch(this.api + 'create', options)
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {
        data = JSON.parse(data)
        const img = document.getElementById('output')
        img.src = '/' + data.path + '?rand=' + Math.random()
      })
      .catch(error => console.error('Error:', error))
  }

  upload (file) {
    const formData = new FormData()
    formData.append('file', file)

    fetch(this.api + 'upload', {
      method: 'POST',
      body: formData
    })
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.json()
      })
      .then(data => {
        document.getElementById('sharepic').style.backgroundImage = `url('/${data.path}?rand=${Math.random()}')`
      })
      .catch(error => console.error('Error:', error))
  }
}
