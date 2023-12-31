/* eslint-disable no-undef, no-unused-vars */

class API {
  constructor () {
    this.api = '/index.php/sharepic/'
  }

  delete (saving) {
    const payload = {
      saving
    }

    fetch('/index.php/sharepic/delete/', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    })
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => { console.log(data) })
      .catch((error) => console.error('Error:', error))
  }

  load (path = 'tenants/mint/start.html') {
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
        document.querySelectorAll('.server-only').forEach(element => {
          element.remove()
        })
        registerDraggableItems()
        select.setup()
        cockpit.setup_sharepic()
        rte.init()
        sg.init()

        document.getElementById('eyecatcher').addEventListener('input', function(event) {
          const eyecatcher = document.getElementById('eyecatcher');
          eyecatcher.style.height = '';
          eyecatcher.style.height=window.getComputedStyle(eyecatcher).getPropertyValue('height')
        })

        logger.prepare_log_data({})
        logger.log('loads template ' + path)
      })
      .catch(error => console.error('Error:', error))
  }

  prepare () {
    select.unselect_all()
    const canvas = document.getElementById('canvas')

    const clonedCanvas = canvas.cloneNode(true)
    clonedCanvas.querySelector('.ql-hidden')?.remove()
    clonedCanvas.querySelector('.ql-toolbar')?.remove()
    clonedCanvas.querySelector('.ql-tooltip')?.remove()
    clonedCanvas.querySelector('.ql-clipboard')?.remove()
    clonedCanvas.querySelector('#patterns')?.remove()

    clonedCanvas.insertAdjacentHTML('afterbegin', '<link rel="stylesheet" href="../../../assets/styles.css">\n')
    clonedCanvas.insertAdjacentHTML('afterbegin', '<link rel="stylesheet" href="../../../node_modules/quill/dist/quill.bubble.css">\n')
    clonedCanvas.insertAdjacentHTML('afterbegin', '<link rel="stylesheet" href="../../../tenants/mint/styles.css?rand=250">\n')

    const data = {
      data: clonedCanvas.innerHTML,
      size: {
        width: document.getElementById('width').value,
        height: document.getElementById('height').value,
        zoom: document.getElementById('sharepic').dataset.zoom
      }
    }

    return data
  }

  save () {
    const name = prompt('Name des Sharepics', 'Sharepic')

    document.querySelector('.save').disabled = true
    document.querySelector('.save').classList.add('waiting')
    const data = this.prepare()
    data.name = name
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }

    fetch(this.api + 'save', options)
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {
        console.log(data)
        data = JSON.parse(data)
      
        const mySharepics = document.querySelector('#my-sharepics');
        const clonedEntry = mySharepics.lastElementChild.cloneNode(true);

        const buttons = clonedEntry.querySelectorAll('button');
        const firstButton = buttons[0];
        const secondButton = buttons[1];

        firstButton.innerHTML =  name;
        firstButton.dataset.load = data.full_path;
        secondButton.dataset.delete = data.id;

        ui.handleClickLoad(firstButton)
        ui.handleClickDelete(secondButton)
        
        mySharepics.appendChild(clonedEntry)

        document.querySelector('.save').disabled = false
        document.querySelector('.save').classList.remove('waiting')
      })
      .catch(error => console.error('Error:', error))
  }

  create () {
    document.querySelector('.create').disabled = true
    document.querySelector('.create').classList.add('waiting')

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(this.prepare())
    }

    fetch(this.api + 'create', options)
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {
        console.log(data)
        data = JSON.parse(data)

        const a = document.createElement('a')
        a.href = '/' + data.path
        a.download = 'sharepic.png'
        document.body.appendChild(a)
        a.click()
        document.body.removeChild(a)

        document.querySelector('.create').disabled = false
        document.querySelector('.create').classList.remove('waiting')

        logger.log('created sharepic')
      })
      .catch(error => console.error('Error:', error))
  }

  upload (file) {
    const formData = new FormData()
    formData.append('file', file)

    const imageUrl = URL.createObjectURL(file)
    document.getElementById('sharepic').style.backgroundImage = `url('${imageUrl}')`

    document.querySelector('.file-upload').disabled = true

    const xhr = new XMLHttpRequest()
    xhr.open('POST', this.api + 'upload', true)
    xhr.upload.onprogress = function (e) {
      if (e.lengthComputable) {
        const percentComplete = Math.round((e.loaded / e.total) * 100)
        console.log(percentComplete + '% uploaded')
      }
    }
    xhr.onload = function () {
      if (this.status === 200) {
        const resp = JSON.parse(this.response)
        document.getElementById('sharepic').style.backgroundImage = `url('/${resp.path}?rand=${Math.random()}')`
        logger.prepare_log_data({
          imagesrc: 'upload'
        })
      } else {
        console.error('Error:', this.status, this.statusText)
      }

      document.querySelector('.file-upload').disabled = false
    }
    xhr.onerror = function () {
      console.error('Error:', this.status, this.statusText)
    }
    xhr.send(formData)
  }
}
