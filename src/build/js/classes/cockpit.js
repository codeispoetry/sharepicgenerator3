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
    document.getElementById('cockpit_background')?.classList.add('show')
  }

  /*
    * Shows the cockpit element for the given compoenent
    * and selects the first component, if it exists.
    */
  show (element) {
    document.querySelectorAll('#cockpit .show').forEach(element => {
      element.classList.remove('show')
    })

    document.querySelectorAll('#componentbuttons .active').forEach(element => {
      element.classList.remove('active')
    })

    this.target = element
    const cockpitEelement = 'setup_' + element.dataset.cockpit
    if (typeof this[cockpitEelement] === 'function') {
      this[cockpitEelement](element)
    }

    // Selects the first input element
    //TODO here geht es weiter
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
    document.getElementById('eyecatcher_size').value = element.style.fontSize.replace('px', '')
    document.getElementById('eyecatcher_color').value = rgbToHex(element.style.color)
    document.getElementById('eyecatcher_bgcolor').value = rgbToHex(element.style.backgroundColor)
    document.getElementById('eyecatcher_rotation').value = element.style.transform.replace('rotate(', '').replace('deg)', '')
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

  setup_addpicture (element) {
    document.getElementById('addpic_color').value = rgbToHex(element.querySelector('.ap_text').style.color)
    document.getElementById('addpicture_size').value = element.querySelector('.ap_image').style.width.replace('px', '')
  }
}
