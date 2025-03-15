/* eslint-disable no-undef, no-unused-vars */

class Public_Sharepics {
  constructor () {
    
  }

  delete (origin, target, publicSarepic = false) {
    if (!window.confirm(lang['Are you sure to delete?'])) {
      return false
    }

    api.delete(target, publicSarepic)
    origin.parentElement.remove()
  }

  filter(query) {
    query = query.toLowerCase()
    if (query.length <= 1) {
        const images = document.getElementById('public_sharepic_results').children
        for (let i = 0; i < images.length; i++) {
            images[i].style.display = 'block'
        }
        return
    }

    const images = document.getElementById('public_sharepic_results').children
    for (let i = 0; i < images.length; i++) {
        const image = images[i]
        const name = image.getAttribute('data-name').toLowerCase()
        const owner = image.getAttribute('data-owner').toLowerCase()
        if (name.includes(query) || owner.includes(query)) {
            image.style.display = 'block'
        } else {
            image.style.display = 'none'
        }
    }
  }

  publish() {
    api.save('publish')
  }

  async show() {

    document.getElementById('public_sharepic_results').innerHTML = ''

    api.loadPublicSharepics().then(images => {
        const results = document.getElementById('public_sharepic_results')
        images.forEach(hit => {
            const container = document.createElement('div')
            container.classList.add('image_container')

            const container_background = document.createElement('div')
            container_background.classList.add('image_container_background')
            container_background.style.backgroundImage = `url('${hit.thumbnail}')`

            const img = document.createElement('div')
            img.style.backgroundImage = `url('${hit.thumbnail}')`
            img.classList.add('image')
            img.setAttribute('data-owner', hit.owner)
            img.setAttribute('data-name', hit.name)

            const description = document.createElement('div')
            description.classList.add('description')
            description.innerHTML = `<b>${hit.name}</b><br>von ${hit.owner}`
            img.appendChild(description)

            img.onclick = () => {
                api.load('public_savings/' + hit.id + '/sharepic.html')
            }

            container.appendChild(container_background)
            container.appendChild(img)

            results.appendChild(container)
        });
    })
    
    document.getElementById('public_sharepics').classList.add('show')
  }

}
