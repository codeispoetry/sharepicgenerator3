/* eslint-disable no-undef, no-unused-vars */

class ImageDB {
  constructor () {
    document.querySelectorAll('[id^="imagedb_q"]').forEach(element => {
      element.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
          this.search(event.target.value)
        }
      })
    })

    const dropbox = document.getElementById('cockpit_search')
    dropbox.addEventListener('dragenter', this.noopHandler, false)
    dropbox.addEventListener('dragexit', this.noopHandler, false)
    dropbox.addEventListener('dragover', this.noopHandler, false)
    dropbox.addEventListener('drop', this.drop, false)
  }

  noopHandler (evt) {
    evt.stopPropagation()
    evt.preventDefault()
  }

  drop (evt) {
    evt.stopPropagation()
    evt.preventDefault()

    // Load addpic
    if (config.imageTarget === 'addpic') {
      component.add('addpicture')
      api.uploadAddPic(evt.dataTransfer)
      return
    }

    // Load Backgroundimage
    api.upload(evt.dataTransfer)
  }

  search (q) {
    document.getElementById('imagedb_q').value = q
    document.getElementById('imagedb_q1').value = q
    document.getElementById('imagedb_q1').focus()
    document.getElementById('imagedb_q1').select()

    if (q.length < 2) {
      return
    }

    const imgDBSource = document.getElementById('image_db_source').value
    document.getElementById('image_db_source_name').innerHTML = 'Images from ' + imgDBSource
    switch (imgDBSource) {
      case 'unsplash':
        this.search_unsplash(q)
        break
      case 'mint':
        document.getElementById('image_db_source_name').innerHTML = 'Bilder der MINT-Mediendatenbank'
        this.search_mint(q)
        break
      default:
        document.getElementById('image_db_source_name').innerHTML = 'Bilder von Pixabay'
        this.search_pixabay(q)
    }
  }

  search_mint (q) {
    const url = `https://mediendatenbank.mint-vernetzt.de/wp-json/media-api/v1/media/search?api_key=${config.mintmediadatabase.apikey}&q=${encodeURIComponent(q)}`
    const body = JSON.stringify({ query: q })

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body
    })
      .then(response => response.json())
      .then(data => {
        logger.log('searches mint for ' + q + ' and gets ' + data.length + ' results')
        this.show_results_mint(data)
      })
      .catch(error => console.error('Error:', error)
      )
  }

  show_results_mint (data) {
   
    const page = document.getElementById('imagedb_page')
    page.classList.add('show')
    document.getElementById('cockpit').style.display = 'none'
    document.getElementById('no_results').innerHTML = ''

    const results = document.getElementById('imagedb_results')
    results.classList.add('show')
    results.innerHTML = ''


    const hasRandomAddedMedia = data.some(item => item.random_added_media === true);
    if (data === undefined || data.length === 0 || hasRandomAddedMedia) {
      const q = document.getElementById('imagedb_q').value

      document.getElementById('no_results').innerHTML = `Für den Suchbegriff "${q}" wurden keine Bilder gefunden. Hier siehst Du einige zufällig ausgewählte Bilder aus der MINT-Mediendatenbank:`;

    }

    data.forEach(hit => {
      const container = document.createElement('div')
      container.classList.add('image_container')

      const container_background = document.createElement('div')
      container_background.classList.add('image_container_background')
      container_background.style.backgroundImage = `url('${hit.attachment_thumbnail}')`

      const img = document.createElement('div')
      img.style.backgroundImage = `url('${hit.attachment_thumbnail}')`
      img.classList.add('image')
      img.setAttribute('data-url', hit.attachment_id)
      img.setAttribute('data-user', 'Mint')
      img.setAttribute('data-pageurl', 'Mint')

      img.ondblclick = () => {
        ui.close('#imagedb_page')
      }

      img.onclick = () => {
        const q = document.getElementById('imagedb_q').value

        logger.prepare_log_data({
          imagesrc: 'mint',
          q
        })
        logger.log('clicks on image after search for ' + q)

        document.querySelector('.workbench-below .message').innerHTML = lang['One moment']

        if (config.imageTarget === 'background') {
          document.getElementById('background').style.backgroundImage = img.style.backgroundImage
          document.getElementById('background').style.filter = 'grayscale(100%)'
        }

        // get real image url
        fetch('https://mediendatenbank.mint-vernetzt.de/wp-json/media-api/v1/media/' + img.dataset.url + '?api_key=' + config.mintmediadatabase.apikey)
          .then(response => response.json())
          .then(data => {
            document.querySelector('.workbench-below .message').innerHTML = ''

            if (data.attachment_meta.file === undefined) {
              console.log('Error: No file found in mint database')
              return
            }

            const lIo = data.attachment_meta.file.lastIndexOf('/')
            const folder = data.attachment_meta.file.substring(0, lIo)
            const file = folder + '/' + data.attachment_meta.sizes.large.file

            if (config.imageTarget === 'addpic') {
              api.loadAddPicByUrl('https://mediendatenbank.mint-vernetzt.de/wp-content/uploads/' + file)
            } else {
              api.loadByUrl('https://mediendatenbank.mint-vernetzt.de/wp-content/uploads/' + file)

              // is copyright already shown?
              const copyright = document.querySelector('#sharepic [id^=copyright_]')
              if (!copyright) {
                component.add('copyright')
              }
              document.querySelector('#sharepic [id^=copyright_]').innerHTML = data.attachment_meta?.image_meta?.copyright
            }
          })
          .catch(error => console.error('Error:', error)
          )

        // background.setCredits(`Image by <a href="${img.dataset.pageurl}?utm_source=sharepicgenerator&utm_medium=referral" target="_blank">${img.dataset.user}</a> auf Unsplash.com`)
      }
      
      container.appendChild(container_background)
      container.appendChild(img)

      results.appendChild(container)
    })
  }

  search_unsplash (q) {
    const url = 'unsplash.php?u=' + encodeURIComponent('https://api.unsplash.com/search/photos?lang=de&per_page=30&query=' + q)

    fetch(url)
      .then(response => response.json())
      .then(data => {
        logger.log('searches unsplash for ' + q + ' and gets ' + data.results.length + ' results')
        this.show_results_unsplash(data)
      })
      .catch(error => console.error('Error:', error)
      )
  }

  show_results_unsplash (data) {
    const page = document.getElementById('imagedb_page')
    page.classList.add('show')
    document.getElementById('cockpit').style.display = 'none'

    const results = document.getElementById('imagedb_results')
    results.classList.add('show')
    results.innerHTML = ''

    if (data.results === undefined || data.results.length === 0) {
      const q = document.getElementById('imagedb_q').value
      results.innerHTML = `<div class="no_results">Für den Suchbegriff "${q}" wurden keine Bilder gefunden.</div>`
      return
    }

    data.results.forEach(hit => {
      const container = document.createElement('div')
      container.classList.add('image_container')

      const container_background = document.createElement('div')
      container_background.classList.add('image_container_background')
      container_background.style.backgroundImage = `url('${hit.urls.small}')`

      const img = document.createElement('div')
      img.style.backgroundImage = `url('${hit.urls.small}')`
      img.classList.add('image')
      img.setAttribute('data-url', hit.links.download_location)
      img.setAttribute('data-user', hit.user.name)
      img.setAttribute('data-pageurl', hit.links.html)

      img.ondblclick = () => {
        ui.close('#imagedb_page')
      }

      img.onclick = () => {
        const q = document.getElementById('imagedb_q').value

        logger.prepare_log_data({
          imagesrc: 'unsplash',
          q
        })
        logger.log('clicks on image after search for ' + q)

        if (config.imageTarget === 'background') {
          document.getElementById('background').style.backgroundImage = img.style.backgroundImage
          document.getElementById('background').style.filter = 'grayscale(100%)'
        }
        
        // get real image url
        fetch('unsplash.php?u=' + img.dataset.url)
          .then(response => response.json())
          .then(data => {
            if (config.imageTarget === 'addpic') {
              api.loadAddPicByUrl(data.url)
            } else {
              api.loadByUrl(data.url)
              // is copyright already shown?
              const copyright = document.querySelector('#sharepic [id^=copyright_]')
              if (!copyright) {
                component.add('copyright')
              }
              document.querySelector('#sharepic [id^=copyright_]').innerHTML = `Bild von ${img.dataset.user} auf Unsplash.com`

              background.setCredits(`Image by <a href="${img.dataset.pageurl}?utm_source=sharepicgenerator&utm_medium=referral" target="_blank">${img.dataset.user}</a> auf Unsplash.com`)
            }
          })
          .catch(error => console.error('Error:', error)
          )
      }
      
      container.appendChild(container_background)
      container.appendChild(img)

      results.appendChild(container)
    })
  }

  search_pixabay (q) {
    const page = 1
    const perPage = 80
    const url = `https://pixabay.com/api/?key=${config.pixabay.apikey}&q=${encodeURIComponent(q)}&image_type=photo&page=${page}&per_page=${perPage}&lang=de`

    fetch(url)
      .then(response => response.json())
      .then(data => {
        logger.log('searches pixabay for ' + q + ' and gets ' + data.hits.length + ' results')
        this.show_results_pixabay(data)
      })
      .catch(error => console.error('Error:', error))
  }

  show_results_pixabay (data) {
    const page = document.getElementById('imagedb_page')
    page.classList.add('show')
    document.getElementById('cockpit').style.display = 'none'

    const results = document.getElementById('imagedb_results')
    results.classList.add('show')
    results.innerHTML = ''

    if (data.hits === undefined || data.hits.length === 0) {
      const q = document.getElementById('imagedb_q').value
      results.innerHTML = `<div class="no_results">Für den Suchbegriff "${q}" wurden keine Bilder gefunden.</div>`
      return
    }

    data.hits.forEach(hit => {
      const container = document.createElement('div')
      container.classList.add('image_container')

      const container_background = document.createElement('div')
      container_background.classList.add('image_container_background')
      container_background.style.backgroundImage = `url('${hit.previewURL}')`

      const img = document.createElement('div')
      img.style.backgroundImage = `url('${hit.previewURL}')`
      img.classList.add('image')
      img.setAttribute('data-url', hit.webformatURL)
      img.setAttribute('data-user', hit.user)
      img.setAttribute('data-pageurl', hit.pageURL)

      img.ondblclick = () => {
        ui.close('#imagedb_page')
      }

      img.onclick = () => {
        const q = document.getElementById('imagedb_q').value

        logger.prepare_log_data({
          imagesrc: 'pixabay',
          q
        })
        logger.log('clicks on image after search for ' + q)

        if (config.imageTarget === 'background') {
          document.getElementById('background').style.backgroundImage = img.style.backgroundImage
          document.getElementById('background').style.filter = 'grayscale(100%)'
          api.loadByUrl(img.dataset.url)

          // is copyright already shown?
          const copyright = document.querySelector('#sharepic [id^=copyright_]')
          if (!copyright) {
            component.add('copyright')
          }
          document.querySelector('#sharepic [id^=copyright_]').innerHTML = `Bild von ${img.dataset.user} auf pixabay.com`
        } else {
          api.loadAddPicByUrl(img.dataset.url)
        }
      }

      container.appendChild(container_background)
      container.appendChild(img)

      results.appendChild(container)
    })
  }
}
