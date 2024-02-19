/* eslint-disable no-undef, no-unused-vars */

class Cockpit {
  target = null

  /*
    * Setup the cockpit for the given element
    */
  setup_sharepic () {
    document.getElementById('width').value = document.getElementById('sharepic').dataset.width
    document.getElementById('height').value = document.getElementById('sharepic').dataset.height

    const backgroundSize = document.getElementById('sharepic').style.backgroundSize

    document.getElementById('background_size').value = (backgroundSize === 'cover') ? 100 : backgroundSize.replace('%', '')
    document.getElementById('background_color').value = ui.rgbToHex(document.getElementById('sharepic').style.backgroundColor)
  }

  setup_greentext (element) {

  }

  setup_background (element) {
    const slider = document.getElementById('copyright_size')
    slider.value = element.style.fontSize.replace('px', '')

    document.getElementById('copyright_color').value = ui.rgbToHex(element.style.color)
    cockpit.target = document.getElementById('copyright')
  }

  setup_freetext (element) {
    document.getElementById('text_size').value = element.style.fontSize.replace('px', '')
  }

  setup_eyecatcher (element) {
    document.getElementById('eyecatcher_size').value = element.style.fontSize.replace('px', '')
    document.getElementById('eyecatcher_color').value = ui.rgbToHex(element.style.color)
    document.getElementById('eyecatcher_bgcolor').value = ui.rgbToHex(element.style.backgroundColor)
    document.getElementById('eyecatcher_rotation').value = element.style.transform.replace('rotate(', '').replace('deg)', '')
  }

  setup_logo (element) {
    document.getElementById('logo_size').value = element.style.width.replace('px', '')

    const file = document.getElementById('logo_file')
    let url = element.style.backgroundImage.replace(/url\("(\.\.\/)*/, '').replace('")', '')
    if (!url.startsWith('/')) {
      url = '/' + url
    }
    file.value = url
  }

  setup_addpicture (element) {
    document.getElementById('addpic_color').value = ui.rgbToHex(element.querySelector('.ap_text').style.color)
    document.getElementById('addpicture_size').value = element.querySelector('.ap_image').style.width.replace('px', '')
  }
}
