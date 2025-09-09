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

  setup_textnrw (element) {
    document.querySelectorAll('.cockpit_textnrw').forEach((div) => {
      div.style.display = 'none'
    })

    document.querySelectorAll('#greentext > div').forEach((div, i) => {
      const cockpitLine = document.querySelectorAll('.cockpit_textnrw')[i]
      if (!cockpitLine) {
        return
      }
      cockpitLine.style.display = 'flex'

      cockpitLine.querySelectorAll('.linesize option').forEach((option) => {
        if (div.classList.contains(option.value)) {
          option.selected = true
        }
      })
    })
  }

  setup_greenaddtext (element) {
    const slider = document.getElementById('greenaddtext_size')
    slider.value = element.style.fontSize.replace('px', '')

    document.getElementById('greenaddtext_color').value = ui.rgbToHex(element.style.color)

    document.querySelectorAll('input[name="greenaddtext_fontface"]').forEach((input) => {
      input.checked = element.style.fontFamily.includes(input.value)
    });

    document.querySelectorAll('input[name="greenaddtext_fontweight"]').forEach((input) => {
      input.checked = (input.value === element.style.fontWeight)
    });
  }

  setup_background (element) {
    const slider = document.getElementById('copyright_size')
    slider.value = element.style.fontSize.replace('px', '')

    const sharepic = document.getElementById('sharepic')
    const targets = sharepic.querySelector('[id^="copyright_"]')
    if (targets && targets.length === 0) {
      document.getElementById('add_copyright_section').style.display = 'block'
      document.querySelectorAll('.with_copyright').forEach((element) => {
        element.classList.add('d-none')
      })
    } else {
      document.getElementById('add_copyright_section').style.display = 'none'

      document.getElementById('copyright_color').value = ui.rgbToHex(element.style.color)
      document.getElementById('copyright_size').value = element.style.fontSize.replace('px', '')
      document.getElementById('copyright_color').value = ui.rgbToHex(element.style.color)

      if(element.style.filter) {  
        document.getElementById('background_brightness').value = element.style.filter.split(' ').find((filter) => filter.startsWith('brightness'))?.replace('brightness(', '')?.replace(')', '') || 1
        document.getElementById('background_saturate').value = element.style.filter.split(' ').find((filter) => filter.startsWith('saturate'))?.replace('saturate(', '').replace(')', '') || 1
        document.getElementById('background_blur').value = element.style.filter.split(' ').find((filter) => filter.startsWith('blur'))?.replace('blur(', '')?.replace('px)', '') || 0

      }

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

    document.getElementById('eyecatcher_size').value = element.style.zoom
  }

  setup_logo (element) {
    document.getElementById('logo_size').value = element.style.width.replace('px', '')
  }

  setup_logomv (element) {
    document.getElementById('logo_size').value = element.style.width.replace('px', '')
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
    document.getElementById('tabs').classList.toggle('prevent_change', element.dataset.prevent_change === 'true')
   
    document.getElementById('addpic_color').value = ui.rgbToHex(element.querySelector('.ap_text').style.color)
    document.getElementById('addpicture_size').value = element.querySelector('.ap_image').style.width.replace('px', '')
  }

  render () {
    const elements = document.querySelectorAll('[id^="tab_btn_"]')
    elements.forEach((element) => {
      const id = element.id.replace('tab_btn_', '')
      element.style.display = (config.cockpit.elements.includes(id)) ? 'flex' : 'none'
    })
  }
}
