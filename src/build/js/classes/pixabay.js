/* eslint-disable no-undef, no-unused-vars */

class Pixabay {
  constructor () {
    document.getElementById('pixabay_q').addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        this.search()
      }
    })
  }

  search () {
    const q = document.getElementById('pixabay_q').value

    const page = 1
    const perPage = 20
    const url = `https://pixabay.com/api/?key=${config.pixabay.apikey}&q=${encodeURIComponent(q)}&image_type=photo&page=${page}&perPage=${perPage}&lang=de`

    fetch(url)
      .then(response => response.json())
      .then(data => {
        logger.log('searches for ' + q + ' and gets ' + data.hits.length + ' results')
        this.showResults(data)
      })
      .catch(error => console.error('Error:', error))
  }

  showResults (data) {
    const page = document.getElementById('pixabay_page')
    page.classList.add('show')

    const results = document.getElementById('pixabay_results')
    results.classList.add('show')
    results.innerHTML = ''

    if (data.hits === undefined || data.hits.length === 0) {
      const q = document.getElementById('pixabay_q').value
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

      img.onclick = () => {
        const q = document.getElementById('pixabay_q').value

        logger.prepare_log_data({
          imagesrc: 'pixabay',
          q
        })
        logger.log('clicks on image after search for ' + q)

        document.getElementById('sharepic').style.backgroundImage = img.style.backgroundImage
        api.loadByUrl(img.dataset.url)

        // is copyrigth already shown?
        const copyright = document.getElementById('copyright')
        if (!copyright) {
          component.add('copyright')
        }
        document.querySelector('[id^=copyright_]').innerHTML = `Bild von ${img.dataset.user} auf pixabay.com`
      }
      results.appendChild(img)
    })
  }
}
