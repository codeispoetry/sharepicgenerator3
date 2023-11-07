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
      .then(data => { this.list(data) })
      .catch(error => console.error('Error:', error))
  }

  list (data) {
    const page = document.getElementById('pixabay_page')
    page.classList.add('show')

    const results = document.getElementById('pixabay_results')
    results.classList.add('show')
    results.innerHTML = ''
    data.hits.forEach(hit => {
      const img = document.createElement('div')
      img.style.backgroundImage = `url('${hit.previewURL}')`
      img.classList.add('image')
      img.setAttribute('data-url', hit.webformatURL)
      img.setAttribute('data-user', hit.user)
      img.setAttribute('data-pageUrl', hit.pageURL)

      img.onclick = () => {
        this.set_imge(img.dataset.url)
      }
      results.appendChild(img)
    })
  }

  set_imge (url) {
    document.getElementById('sharepic').style.backgroundImage = `url('${url}')`
  }
}
