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
  }

  setLang (language) {
    if (confirm(lang['All changes lost']) === false) {
      return false
    }
    document.cookie = 'lang=' + language + '; path=/'
    window.document.location.reload()
  }

  deleteSavedSharepic (origin, target) {
    if (!window.confirm(lang['Are you sure?'])) {
      return false
    }

    api.delete(target)
    origin.parentElement.remove()
  }

  close (target) {
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
}
