/* eslint-disable no-undef, no-unused-vars */

class Cockpit {
  target = 3
  /*
   * Clear all cockpit elements and show the sharepic cockpit
   */
  show_standard () {
    const elements = document.querySelectorAll('#cockpit .show')
    elements.forEach(element => {
      element.classList.remove('show')
    })
    document.getElementById('cockpit_sharepic')?.classList.add('show')
  }

  /*
    * Show the cockpit element for the given element
    */
  show (element) {
    const elements = document.querySelectorAll('#cockpit .show')
    elements.forEach(element => {
      element.classList.remove('show')
    })

    this.target = element;
    const cockpitEelement = 'setup_' + element.dataset.cockpit
    if (typeof this[cockpitEelement] === 'function') {
      this[cockpitEelement](element)
    }
    const id = 'cockpit_' + element.dataset.cockpit
    document.getElementById(id)?.classList.add('show')
  }

  /*
    * Setup the cockpit for the given element
    */
  setup_sharepic () {
    document.getElementById('width').value = document.getElementById('sharepic').dataset.width
    document.getElementById('height').value = document.getElementById('sharepic').dataset.height

    const backgroundSize = document.getElementById('sharepic').style.backgroundSize

    document.getElementById('background_size').value = (backgroundSize === 'cover') ? 100 : backgroundSize.replace('%', '')
    document.getElementById('background_color').value = rgbToHex(document.getElementById('sharepic').style.backgroundColor)
  }

  setup_greentext (element) {

  }

  setup_copyright (element) {
    const slider = document.getElementById('copyright_size')
    slider.value = element.style.fontSize.replace('px', '')

    document.getElementById('copyright_color').value = rgbToHex(element.style.color)

  }

  setup_freetext (element) {
    const slider = document.getElementById('text_size')
    slider.value = element.style.fontSize.replace('px', '')
  }

  setup_eyecatcher (element) {
    const slider = document.getElementById('eyecatcher_size')
    slider.value = element.style.fontSize.replace('px', '')

    document.getElementById('eyecatcher_color').value = rgbToHex(element.style.color)
    document.getElementById('eyecatcher_bgcolor').value = rgbToHex(element.style.backgroundColor)
  }

  setup_logo (element) {
    const slider = document.getElementById('logo_size')
    slider.value = element.style.width.replace('px', '')

    const file = document.getElementById('logo_file')
    let url = element.style.backgroundImage.replace(/url\("(\.\.\/)*/, '').replace('")', '')
    if (!url.startsWith('/')) {
      url = '/' + url
    }
    file.value = url
  }
}
