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
        logger.log('searched for ' + q + ' got ' + data.hits.length + ' results')
        this.list(data) 
      })
      .catch(error => console.error('Error:', error))
  }

  list (data) {
    const page = document.getElementById('pixabay_page')
    page.classList.add('show')

    const results = document.getElementById('pixabay_results')
    results.classList.add('show')
    results.innerHTML = ''

    if ( data.hits === undefined || data.hits.length === 0 ) {
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
      img.setAttribute('data-pageUrl', hit.pageURL)

      img.onclick = () => {
        const q = document.getElementById('pixabay_q').value
  
        logger.prepare_log_data({
          imagesrc: 'pixabay',
          q: q,
        })
        logger.log('clicked on image after search for ' + q )

        this.set_imge(img.dataset.url)
      }
      results.appendChild(img)
    })
  }

  set_imge (url) {
    const sharepic = document.getElementById('sharepic')
    sharepic.style.backgroundImage = `url('${url}')`
  }
}
