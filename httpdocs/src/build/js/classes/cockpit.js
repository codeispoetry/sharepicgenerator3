/* eslint-disable no-undef, no-unused-vars */

class Cockpit {
  target = null

  /*
    * Setup the cockpit for the given element
    */
  setup_sharepic () {
    document.getElementById('width').value = document.getElementById('sharepic').dataset.width
    document.getElementById('height').value = document.getElementById('sharepic').dataset.height

    const backgroundSize = document.getElementById('background').style.backgroundSize

    document.getElementById('background_size').value = (backgroundSize === 'cover' || backgroundSize === '') ? 100 : backgroundSize.replace('%', '')
    document.getElementById('background_color').value = ui.rgbToHex(document.getElementById('sharepic').style.backgroundColor)
  }

  setup_greentext (element) {
    document.querySelectorAll('.cockpit_greentext').forEach((div) => {
      div.style.display = 'none'
    })

    document.querySelectorAll('#greentext > div').forEach((div, i) => {
      const cockpitLine = document.querySelectorAll('.cockpit_greentext')[i]
      if (!cockpitLine) {
        return
      }
      cockpitLine.style.display = 'flex'

      cockpitLine.querySelectorAll('.linesize option').forEach((option) => {
        if (div.classList.contains(option.value)) {
          option.selected = true
        }
      })

      cockpitLine.querySelectorAll('.linecolor option').forEach((option) => {
        if (div.classList.contains(option.value)) {
          option.selected = true
        }
      })

      cockpitLine.querySelector('.lineindent').value = div.style.marginLeft.replace('px', '')
    })
  }

  setup_greenaddtext (element) {
    const slider = document.getElementById('greenaddtext_size')
    slider.value = element.style.fontSize.replace('px', '')

    document.getElementById('greenaddtext_color').value = ui.rgbToHex(element.style.color)
  }

  setup_background (element) {
    const slider = document.getElementById('copyright_size')
    slider.value = element.style.fontSize.replace('px', '')

    const sharepic = document.getElementById('sharepic')
    const targets = sharepic.querySelector('[id^="copyright_"]');
    if( targets.length === 0 ) {
      document.getElementById('add_copyright_section').style.display = 'block'
      document.querySelectorAll('.with_copyright').forEach((element) => {
        element.classList.add('d-none')
      })
    } else {
      document.getElementById('add_copyright_section').style.display = 'none'

      document.getElementById('copyright_color').value = ui.rgbToHex(element.style.color)
      document.getElementById('copyright_size').value = element.style.fontSize.replace('px', '')
      document.getElementById('copyright_color').value = ui.rgbToHex(element.style.color)
      // loop over all elements having the class with_copyright
      document.querySelectorAll('.with_copyright').forEach((element) => {
        element.classList.remove('d-none')
      })
      document.getElementById('add_copyright_section').style.display = 'none'
    }
  }

  setup_freetext (element) {
    document.getElementById('text_rotation').value = element.style.rotate.replace('deg', '')
  }

  setup_eyecatcher (element) {
    if (config.starttemplate === 'de') {
      return
    }

    document.getElementById('eyecatcher_size').value = element.style.fontSize.replace('px', '')
    document.getElementById('eyecatcher_color').value = ui.rgbToHex(element.style.color)
    document.getElementById('eyecatcher_bgcolor').value = ui.rgbToHex(element.querySelector('#sticker_bg').style.fill)
    document.getElementById('eyecatcher_rotation').value = element.style.transform.replace('rotate(', '').replace('deg)', '')
    document.getElementById('eyecatcher_padding').value = element.style.padding.replace('px', '')
  }

  setup_logo (element) {
    document.getElementById('logo_size').value = element.style.width.replace('px', '')

    const file = document.getElementById('logo_file')
    const url = element.style.backgroundImage.replace(/url\("(\.\.\/)*/, '').replace('")', '')
    file.value = url

    if (config.cockpit.logos && config.cockpit.logos.length > 0) {
      // delete all options in this select
      const select = document.getElementById('logo_file')

      while (select.firstChild) {
        select.removeChild(select.firstChild)
      }

      config.cockpit.logos.forEach((logo) => {
        const option = document.createElement('option')
        option.text = logo.split('/').pop().split('.').shift()
        option.value = logo
        select.add(option)
      })
    }
  }

  setup_elements (element) {
    document.getElementById('elements_size').value = element.style.width.replace('px', '')

    if (config.cockpit.objects && config.cockpit.objects.length > 0) {
      // delete all options in this select
      const select = document.getElementById('elements_file')

      while (select.firstChild) {
        select.removeChild(select.firstChild)
      }

      config.cockpit.objects.forEach((obj) => {
        const option = document.createElement('option')
        option.text = obj.split('/').pop().split('.').shift()
        option.value = obj
        select.add(option)
      })
    }

    const imgSrc = cockpit.target.querySelector('img').src
    const url = new URL(imgSrc)

    document.getElementById('elements_file').value = url.pathname.substring(1)
  }

  setup_addpicture (element) {
    document.getElementById('addpic_color').value = ui.rgbToHex(element.querySelector('.ap_text').style.color)
    document.getElementById('addpicture_size').value = element.querySelector('.ap_image').style.width.replace('px', '')
  }

  render () {
    const elements = document.querySelectorAll('[id^="tab_btn_"]')
    elements.forEach((element) => {
      const id = element.id.replace('tab_btn_', '')
      element.style.display = (config.cockpit.elements.includes(id)) ? 'block' : 'none'
    })
  }
}
