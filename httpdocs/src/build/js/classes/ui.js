/* eslint-disable no-undef, no-unused-vars */

const mouseDownEvent = new MouseEvent('mousedown', {
  bubbles: true,
  cancelable: true,
  button: 0
})

class UI {
  /*
    * @param {string} tab The tab to show.
    * @param {HTMLElement} element The element that was clicked.
    */
  showTab (tab, element = null) {
    // Sets up the cockpit for the selected element.
    if (element) {
      cockpit.target = element
      const cockpitEelement = 'setup_' + element.dataset.cockpit
      if (typeof cockpit[cockpitEelement] === 'function') {
        cockpit[cockpitEelement](element)
      }
    } else {
      // Click came from sidebar.
      // Search, if there is an appropriate element and select it.
      component.unselect()
      const firstElement = document.querySelector(`#sharepic > [data-cockpit="${tab}"]`)
      if (firstElement !== null) {
        document.querySelector('.selected')?.classList.remove('selected')
        firstElement.classList.add('selected')
        this.showTab(tab, firstElement)
        return
      }
    }

    // Show elements that are only visible when an element is selected.
    document.querySelectorAll('.selected_only').forEach(element => {
      element.style.display = (cockpit.target === null) ? 'none' : 'block'
    })

    document.querySelector('#cockpit .show')?.classList.remove('show')
    document.querySelector('#cockpit .active')?.classList.remove('active')

    document.getElementById('cockpit_' + tab)?.classList.add('show')
    document.getElementById('tab_btn_' + tab)?.classList.add('active')

    document.getElementById('drag_background').checked = false
  }

  showImageTab () {
    // show image tab, depending on wheter an background image is set or not
    if (document.getElementById('background').style.backgroundImage === '') {
      config.imageTarget = 'background'
      this.showSearchImageTab()
      return
    }

    this.showTab('background')
  }

  showSearchImageTab (target = 'background') {
    config.imageTarget = target

    document.querySelectorAll('[data-target').forEach((element) => {
      element.style.display = 'none'
    })

    document.querySelectorAll('[data-target="' + target + '"]').forEach((element) => {
      element.style.display = 'block'
    })
    this.showTab('search')
  }

  setLang (language) {
    if (confirm(lang['All changes lost']) === false) {
      return false
    }
    document.cookie = 'lang=' + language + '; path=/'
    window.document.location.reload()
  }

  reload () {
    if (confirm(lang['All changes lost']) === false) {
      return false
    }
    window.document.location.reload()
  }

  deleteSavedSharepic (origin, target) {
    if (!window.confirm(lang['Are you sure to delete?'])) {
      return false
    }

    api.delete(target)
    origin.parentElement.remove()
  }

  close (target) {
    document.getElementById('cockpit').style.display = 'flex'
    document.querySelector(target).classList.remove('show')
  }

  rgbToHex (rgb) {
    const sep = rgb.indexOf(',') > -1 ? ',' : ' '
    rgb = rgb.substr(4).split(')')[0].split(sep)

    let r = (+rgb[0]).toString(16)
    let g = (+rgb[1]).toString(16)
    let b = (+rgb[2]).toString(16)

    if (r.length === 1) { r = '0' + r }
    if (g.length === 1) { g = '0' + g }
    if (b.length === 1) { b = '0' + b }

    return '#' + r + g + b
  }

  addColorButtons () {
    if (!config.palette) return

    // empty all palettes
    const palettes = document.querySelectorAll('.palette')
    palettes.forEach((palette) => {
      const buttons = palette.querySelectorAll('button')
      buttons.forEach((button) => {
        if (button.dataset.blueprint !== 'true') {
          button.remove()
        }
      })
    })

    config.palette.forEach((color) => {
      this.addColorButton(color)
    })
  }

  addColorButton (color) {
    const palettes = document.querySelectorAll('.palette')
    palettes.forEach((palette) => {
      const button = palette.querySelector('button').cloneNode(true)
      button.style.backgroundColor = color
      button.dataset.blueprint = ''
      palette.appendChild(button)
    })
    this.setPaletteInHtml()
    rte.addColor(color)
  }

  removeColorButton (color) {
    const palettes = document.querySelectorAll('.palette')
    palettes.forEach((palette) => {
      const buttons = palette.querySelectorAll('button')
      buttons.forEach((button) => {
        if (button.style.backgroundColor === color) {
          button.remove()
        }
      })
    })
    this.setPaletteInHtml()
  }

  // Sets the palette in the HTML, so that it can be saved.
  setPaletteInHtml () {
    const scriptElem = document.getElementById('canvas').querySelector('script')
    let scriptContent = scriptElem.textContent

    const palette = config.palette.map((color) => `'${color}'`).join(',')

    scriptContent = scriptContent.replace(/(config\.palette\s*=\s*).*/, `$1[${palette}]`)

    scriptElem.textContent = scriptContent
  }

  unfold (button, target) {
    document.getElementById(target).classList.toggle('folded')
    button.classList.toggle('active')
  }

  downloadButton(){
    if( document.getElementById('format').value === 'spg' ) {
      api.save('save', 'editable', 2)
    }

    api.create();
  }
}
