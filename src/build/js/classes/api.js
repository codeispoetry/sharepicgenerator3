/* eslint-disable no-undef, no-unused-vars */

class API {
  constructor () {
    this.api = config.url + '/index.php?c=sharepic'
    this.ai = config.url + '/index.php?c=openai'
  }

  delete (saving) {
    const payload = {
      saving
    }

    fetch(this.api + '&m=delete', {
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
      .then(data => {
        logger.log('deleted sharepic ' + saving)
      })
      .catch((error) => console.error('Error:', error))
  }

  load (path = 'templates/mint/start.html') {
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
    fetch(this.api + '&m=load', options)
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

        cockpit.setup_sharepic()
        rte.init()
        sg.init()

        // Execute script tags
        const parser = new DOMParser()
        const doc = parser.parseFromString(data, 'text/html')
        const script = doc.querySelector('script').innerText
        // eslint-disable-next-line no-eval
        eval(script)
        logger.prepare_log_data({})

        logger.log('loads template ' + path)
      })
      .catch(error => console.error('Error:', error))
  }

  prepare () {
    component.unselect()
    const canvas = document.getElementById('canvas')

    const clonedCanvas = canvas.cloneNode(true)
    clonedCanvas.querySelector('.ql-hidden')?.remove()
    clonedCanvas.querySelector('.ql-toolbar')?.remove()
    clonedCanvas.querySelector('.ql-tooltip')?.remove()
    clonedCanvas.querySelector('.ql-clipboard')?.remove()
    clonedCanvas.querySelector('#patterns')?.remove()
    clonedCanvas.querySelector('#greentextContextMenu')?.remove()

    clonedCanvas.insertAdjacentHTML('afterbegin', '<link rel="stylesheet" href="assets/styles.css">\n')
    clonedCanvas.insertAdjacentHTML('afterbegin', '<link rel="stylesheet" href="node_modules/quill/dist/quill.bubble.css">\n')

    clonedCanvas.insertAdjacentHTML('afterbegin', '<base href="../../../">\n')

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

  save (mode = 'save') {
    const name = prompt('Name des Sharepics', 'Sharepic')

    const data = this.prepare()
    data.name = name
    data.mode = mode

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }

    fetch(this.api + '&m=save', options)
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {
        data = JSON.parse(data)

        const mySharepics = document.querySelector('#my-sharepics')

        try {
          const clonedEntry = mySharepics.lastElementChild.cloneNode(true)

          const buttons = clonedEntry.querySelectorAll('button')
          const firstButton = buttons[0]
          const secondButton = buttons[1]

          firstButton.innerHTML = name
          firstButton.dataset.load = data.full_path
          secondButton.dataset.delete = data.id

          mySharepics.appendChild(clonedEntry)
        } catch (e) {
          console.error(e)
        }

        logger.log('saved sharepic ' + name)
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

    this.showWaiting()

    fetch(this.api + '&m=create', options)
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {
        data = JSON.parse(data)

        const a = document.createElement('a')
        a.href = config.url + '/' + data.path
        a.download = 'sharepic.png'
        document.body.appendChild(a)
        a.click()
        document.body.removeChild(a)

        document.querySelector('.create').disabled = false
        document.querySelector('.create').classList.remove('waiting')
        this.closeWaiting()

        logger.log('created sharepic')
      })
      .catch(error => console.error('Error:', error))
  }

  dalle () {
    const data = {
      prompt: document.getElementById('dalle_prompt').value
    }

    if (data.prompt === '') {
      alert(lang['Enter prompt for image'])
      return
    }

    document.getElementById('dalle_result_waiting').style.display = 'block'
    document.getElementById('dalle_result_response').style.display = 'none'

    const createButton = document.querySelector('[onClick="api.dalle()"')
    const createButtonLabel = createButton.innerHTML
    createButton.innerHTML = '...'
    createButton.disabled = true

    const startGeneration = Math.floor(Date.now() / 1000)
    document.getElementById('dalle_result_waiting_progress').innerHTML = 0

    const dalleWaiting = window.setInterval(function () {
      const seconds = Math.floor(Date.now() / 1000) - startGeneration
      document.getElementById('dalle_result_waiting_progress').innerHTML = seconds
    }, 1000)

    logger.log('used dalle with prompt: ' + data.prompt)

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }

    fetch(this.ai + '&m=dalle', options)
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {
        data = JSON.parse(data)

        const hint = data.data[0].revised_prompt
        const url = data.local_file

        document.getElementById('dalle_result_waiting').style.display = 'none'
        document.getElementById('dalle_result_response').style.display = 'block'

        document.getElementById('dalle_prompt').value = hint
        document.getElementById('dalle_result_image').innerHTML = '<img src="' + url + '?rand=' + Math.random() + '" />'

        document.getElementById('copyright').innerHTML = 'Bild generiert von DALL-E'

        config.dalle = {
          url
        }

        const endGeneration = Math.floor(Date.now() / 1000)
        logger.log('waited ' + (endGeneration - startGeneration) + ' seconds for dalle result')

        createButton.innerHTML = createButtonLabel
        createButton.disabled = false

        clearInterval(dalleWaiting)
      })
      .catch(error => console.error('Error:', error))
  }

  useDalle () {
    document.getElementById('sharepic').style.backgroundImage = `url('${config.dalle.url}?rand=${Math.random()}')`
    logger.prepare_log_data({
      imagesrc: 'dalle'
    })
  }

  upload (btn) {
    if (!btn.files.length) {
      return
    }

    const file = btn.files[0]

    const formData = new FormData()
    formData.append('file', file)

    const imageUrl = URL.createObjectURL(file)

    document.getElementById('sharepic').style.backgroundImage = `url('${imageUrl}')`

    document.querySelector('.file-upload').disabled = true

    const xhr = new XMLHttpRequest()
    xhr.open('POST', this.api + '&m=upload', true)
    xhr.upload.onprogress = function (e) {
      if (e.lengthComputable) {
        const percentComplete = Math.round((e.loaded / e.total) * 100)
      }
    }
    xhr.onload = function () {
      if (this.status === 200) {
        const resp = JSON.parse(this.response)
        document.getElementById('sharepic').style.backgroundImage = `url('${resp.path}?rand=${Math.random()}')`
        logger.prepare_log_data({
          imagesrc: 'upload'
        })
      } else {
        console.error('Error:', this.status, this.statusText)
      }

      document.querySelector('.file-upload').disabled = false

      document.getElementById('copyright').innerHTML = ''

      logger.log('uploaded file')
    }
    xhr.onerror = function () {
      console.error('Error:', this.status, this.statusText)
    }
    xhr.send(formData)
  }

  uploadAddPic (btn) {
    if (!btn.files.length) {
      return
    }

    const file = btn.files[0]

    const formData = new FormData()
    formData.append('file', file)

    const imageUrl = URL.createObjectURL(file)

    document.querySelector('.file-upload').disabled = true

    const xhr = new XMLHttpRequest()
    xhr.open('POST', this.api + '&m=upload_addpic', true)
    xhr.upload.onprogress = function (e) {
      if (e.lengthComputable) {
        const percentComplete = Math.round((e.loaded / e.total) * 100)
      }
    }
    xhr.onload = function () {
      if (this.status === 200) {
        const resp = JSON.parse(this.response)

        cockpit.target.querySelector('.ap_image').style.backgroundImage = `url('${resp.path}?rand=${Math.random()}')`
        logger.prepare_log_data({
          imagesrc: 'addpic'
        })
      } else {
        console.error('Error:', this.status, this.statusText)
      }

      document.querySelector('.file-upload').disabled = false

      logger.log('uploaded addpic')
    }
    xhr.onerror = function () {
      console.error('Error:', this.status, this.statusText)
    }
    xhr.send(formData)
  }

  loadByUrl (url) {
    const data = {
      url
    }

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }

    fetch(this.api + '&m=load_from_url', options)
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {
        data = JSON.parse(data)

        document.getElementById('sharepic').style.backgroundImage = `url('${data.path}?rand=${Math.random()}')`
      })
      .catch(error => console.error('Error:', error))
  }

  showWaiting () {
    document.getElementsByTagName('body')[0].style.opacity = 0.3
    document.getElementById('waiting').showModal()
  }

  closeWaiting () {
    document.getElementsByTagName('body')[0].style.opacity = 1
    document.getElementById('waiting').close()
  }
}
