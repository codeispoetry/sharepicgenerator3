/* eslint-disable no-undef, no-unused-vars */

class ImageDB {
  constructor () {
    document.getElementById('imagedb_q').addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        this.search()
      }
    })
  }

  search () {
    //this.search_pixabay()
    this.search_unsplash()
  }

  search_unsplash() {
    const q = document.getElementById('imagedb_q').value

    const url = `unsplash.php?u=https://api.unsplash.com/search/photos?query=${encodeURIComponent(q)}&per_page=30`

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

        document.getElementById('background').style.backgroundImage = img.style.backgroundImage
        document.getElementById('background').style.filter = 'grayscale(100%)'

        // get real image url
        fetch('unsplash.php?u=' + img.dataset.url)
          .then(response => response.json())
          .then(data => {
            api.loadByUrl(data.url)
          })
          .catch(error => console.error('Error:', error)
        )

       

        // is copyright already shown?
        const copyright = document.querySelector('#sharepic [id^=copyright_]')
        if (!copyright) {
          component.add('copyright')
        }
        document.querySelector('#sharepic [id^=copyright_]').innerHTML = `Bild von ${img.dataset.user} auf Unsplash.com`
      }
      results.appendChild(img)
    })
  }

  search_pixabay() {
    const q = document.getElementById('imagedb_q').value

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

        document.getElementById('background').style.backgroundImage = img.style.backgroundImage
        document.getElementById('background').style.filter = 'grayscale(100%)'

        api.loadByUrl(img.dataset.url)

        // is copyright already shown?
        const copyright = document.querySelector('#sharepic [id^=copyright_]')
        if (!copyright) {
          component.add('copyright')
        }
        document.querySelector('#sharepic [id^=copyright_]').innerHTML = `Bild von ${img.dataset.user} auf pixabay.com`
      }
      results.appendChild(img)
    })
  }
}
