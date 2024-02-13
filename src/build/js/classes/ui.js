/* eslint-disable no-undef, no-unused-vars */

const mouseDownEvent = new MouseEvent('mousedown', {
  bubbles: true,
  cancelable: true,
  button: 0
})

class UI {
  showTab (tab) {
    document.querySelector('#cockpit .show')?.classList.remove('show')
    document.querySelector('#cockpit .active')?.classList.remove('active')

    document.getElementById('cockpit_' + tab)?.classList.add('show')
    document.getElementById('tab_btn_' + tab)?.classList.add('active')
  }

  select (element) {
    element.classList.add('selected')
    this.showTab(element.dataset.cockpit)

    cockpit.target = element
    const cockpitEelement = 'setup_' + element.dataset.cockpit
    if (typeof cockpit[cockpitEelement] === 'function') {
      cockpit[cockpitEelement](element)
    }
  }

  startDrag (event) {
    if (event.button !== 0 || event.target.classList.contains('draggable') === false) {
      return
    }

    cockpit.target = event.target

    ui.dragInfo = {
      xOffset: event.clientX - cockpit.target.getBoundingClientRect().left + document.getElementById('canvas').getBoundingClientRect().left,
      yOffset: event.clientY - cockpit.target.getBoundingClientRect().top + document.getElementById('canvas').getBoundingClientRect().top
    }

    

    document.addEventListener('mousemove', ui.dragging)
    document.addEventListener('mouseup', ui.stopDrag)
  }

  dragging (e) {
    e.preventDefault()

    const x = e.clientX - ui.dragInfo.xOffset
    const y = e.clientY - ui.dragInfo.yOffset

    cockpit.target.style.top = `${y}px`
    cockpit.target.style.left = `${x}px`
  }

  stopDrag () {
    document.removeEventListener('mousemove', ui.dragging)
    document.removeEventListener('mouseup', ui.stopDrag)
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

  toFront (element) {
    console.log(element)
    const highestZIndex = [...document.querySelectorAll('.draggable')].reduce((maxZIndex, element) => {
      const zIndex = parseInt(getComputedStyle(element).zIndex, 10)
      return isNaN(zIndex) ? maxZIndex : Math.max(maxZIndex, zIndex)
    }, 0)
    cockpit.target.style.zIndex = (highestZIndex + 1).toString()
  }

  toBack (element) {
    const allElements = [...document.querySelectorAll('#sharepic > *')]

    // Filter elements that have a z-index
    const elementsWithZIndex = allElements.filter(element => {
      const zIndex = parseInt(getComputedStyle(element).zIndex, 10)
      return !isNaN(zIndex) && zIndex !== 0
    })

    // Sort elements by z-index
    const sortedElementsByZIndex = elementsWithZIndex.sort((a, b) => {
      const zIndexA = parseInt(getComputedStyle(a).zIndex, 10)
      const zIndexB = parseInt(getComputedStyle(b).zIndex, 10)
      return zIndexA - zIndexB
    })

    // Loop through sorted elements and increase their z-index by one
    sortedElementsByZIndex.forEach(element => {
      const zIndex = parseInt(getComputedStyle(element).zIndex, 10)
      element.style.zIndex = (zIndex + 1).toString()
    })

    cockpit.target.style.zIndex = 1
  }

  close (target) {
    document.querySelector(target).classList.remove('show')
  }
}
