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

    config.template = path

    document.querySelector('#tabsbuttons button').click()

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'text/html'
      },
      body: JSON.stringify(data)
    }
    fetch(this.api + '&r=' + Math.random() + '&m=load', options)
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
        cockpit.render()
        logger.prepare_log_data({})

        logger.log('loads template ' + path)
      })
      .catch(error => console.error('Error:', error))
  }

  prepare () {
    component.unselect()
    const canvas = document.getElementById('canvas')

    const clonedCanvas = canvas.cloneNode(true)

    clonedCanvas.querySelector('#greentextContextMenu')?.remove()

    clonedCanvas.insertAdjacentHTML('afterbegin', '<link rel="stylesheet" href="assets/styles.css?r=1">\n')
    clonedCanvas.insertAdjacentHTML('afterbegin', '<base href="../../../httpdocs/">\n')

    // Replace background image with local path file
    const bgImage = document.getElementById('sharepic').style.backgroundImage
    if (bgImage !== '') {
      const url = new URL(bgImage, 'http://dummybase.com') // dummy base URL is needed because urlString is a relative URL
      const params = new URLSearchParams(url.search)
      const backgroundImage = params.get('p').replace(/"\)$/g, '')
      clonedCanvas.querySelector('#sharepic').style.backgroundImage = `url(../users/${config.username}/${backgroundImage})`
    }

    // Replace all additonal pictures with local path file
    const addPics = clonedCanvas.querySelectorAll('.addpicture')
    addPics.forEach(addPic => {
      const bgImage = addPic.querySelector('.ap_image').style.backgroundImage
      if (bgImage !== '') {
        const url = new URL(bgImage, 'http://dummybase.com') // dummy base URL is needed because urlString is a relative URL
        const params = new URLSearchParams(url.search)
        const backgroundImage = params.get('p').replace(/"\)$/g, '')
        addPic.querySelector('.ap_image').style.backgroundImage = `url(../users/${config.username}/${backgroundImage})`
      }
    })

    const data = {
      data: clonedCanvas.innerHTML,
      jpg: document.getElementById('jpg').checked,
      size: {
        width: document.getElementById('width').value,
        height: document.getElementById('height').value,
        zoom: document.getElementById('sharepic').dataset.zoom
      },
      body_class: document.getElementsByTagName('body')[0].classList.value
    }

    return data
  }

  // mode=save or mode=publish
  save (mode = 'save') {
    const name = prompt('Name des Sharepics', 'Sharepic')

    const data = {
      data: document.getElementById('canvas').outerHTML,
      name,
      mode
    }

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

        try {
          const html = `<div class="dropdown-item-double">
            <button class="did-1" onclick="api.load('${data.full_path}')">
              ${name}
            </button>
            <button class="did-2" onclick="ui.deleteSavedSharepic(this, '${data.id}')">
              <img src="assets/icons/delete.svg">
            </button>
          </div>`
          document.getElementById('my-sharepics').insertAdjacentHTML('beforeend', html)
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

    document.querySelector('#tabsbuttons button').click()

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

        a.download = 'sharepic.' + data.path.slice(-3)
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

    document.getElementById('dalle_result').style.display = 'block'
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
        document.getElementById('dalle_result_image').innerHTML = '<img src="' + url + '" />'

        // is copyright already shown?
        const copyright = document.querySelector('#sharepic [id^=copyright_]')
        if (!copyright) {
          component.add('copyright')
        }
        document.querySelector('#sharepic [id^=copyright_]').innerHTML = 'Bild generiert von DALL-E'
        ui.showTab('search')
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
    document.getElementById('sharepic').style.backgroundImage = `url('${config.dalle.url}')`
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
    document.getElementById('sharepic').style.filter = 'grayscale(100%)'

    const xhr = new XMLHttpRequest()
    xhr.open('POST', this.api + '&m=upload', true)
    xhr.upload.onprogress = function (e) {
      if (e.lengthComputable) {
        const percentComplete = Math.round((e.loaded / e.total) * 100)

        let message = lang['Uploading image'] + ' ' + percentComplete + '%'
        if (percentComplete > 98) {
          message = lang['Processing image']
        }

        document.querySelector('.workbench-below .message').innerHTML = message
      }
    }
    xhr.onload = function () {
      if (this.status === 200) {
        const resp = JSON.parse(this.response)
        document.getElementById('sharepic').style.backgroundImage = `url('${resp.path}')`
        logger.prepare_log_data({
          imagesrc: 'upload'
        })
      } else {
        console.error('Error:', this.status, this.statusText)
      }

      document.getElementById('sharepic').style.filter = 'none'
      document.querySelector('.workbench-below .message').innerHTML = ''

      const copyright = document.querySelector('#sharepic [id^=copyright_]')
      if (copyright) {
        copyright.innerHTML = ''
      }

      ui.showTab('background')

      logger.log('uploaded file')
    }
    xhr.onerror = function () {
      console.error('Error:', this.status, this.statusText)
    }
    xhr.send(formData)
  }

  uploadAddPic (btn) {
    if (!btn.files.length || cockpit.target === null) {
      return
    }

    const file = btn.files[0]

    const formData = new FormData()
    formData.append('file', file)

    const imageUrl = URL.createObjectURL(file)

    cockpit.target.querySelector('.ap_image').style.backgroundImage = `url('${imageUrl}')`
    cockpit.target.querySelector('.ap_image').style.filter = 'grayscale(100%)'

    const xhr = new XMLHttpRequest()
    xhr.open('POST', this.api + '&m=upload_addpic', true)
    xhr.upload.onprogress = function (e) {
      if (e.lengthComputable) {
        const percentComplete = Math.round((e.loaded / e.total) * 100)

        let message = lang['Uploading image'] + ' ' + percentComplete + '%'
        cockpit.target.querySelector('.ap_image').style.opacity = Math.max(0.3, percentComplete / 100)
        if (percentComplete > 98) {
          message = lang['Processing image']
        }

        document.querySelector('.workbench-below .message').innerHTML = message
      }
    }
    xhr.onload = function () {
      if (this.status === 200) {
        const resp = JSON.parse(this.response)

        cockpit.target.querySelector('.ap_image').style.backgroundImage = `url('${resp.path}')`

        document.querySelector('.workbench-below .message').innerHTML = ''
        cockpit.target.querySelector('.ap_image').style.filter = 'none'
        cockpit.target.querySelector('.ap_image').style.opacity = 1

        logger.prepare_log_data({
          imagesrc: 'addpic'
        })
      } else {
        console.error('Error:', this.status, this.statusText)
      }

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

        document.getElementById('sharepic').style.backgroundImage = `url('${data.path}')`
        document.getElementById('sharepic').style.filter = 'none'
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
