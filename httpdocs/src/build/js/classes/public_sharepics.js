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

  publish() {
    api.create('true');
    api.save('publish')
  }

  async show() {

    document.getElementById('public_sharepic_results').innerHTML = ''

    api.loadPublicSharepics().then(images => {
        const results = document.getElementById('public_sharepic_results')
        images.forEach(hit => {
            const img = document.createElement('div')
            img.style.backgroundImage = `url('${hit.thumbnail}')`
            img.classList.add('image')

            img.onclick = () => {
                api.load('public_savings/' + hit.id + '/sharepic.html')
            }

            results.appendChild(img)
        });
    })
    
    document.getElementById('public_sharepics').classList.add('show')
  }

}
