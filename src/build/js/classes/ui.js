/* eslint-disable no-undef, no-unused-vars */

const mouseDownEvent = new MouseEvent('mousedown', {
  bubbles: true,
  cancelable: true,
  button: 0
})

class UI {
  constructor () {

    // Shows a tab from the cockpit
    document.querySelectorAll('[data-showtab]').forEach(element => {
      element.addEventListener('click', (event) => {
        document.querySelectorAll('#cockpit .show').forEach(element => {
          element.classList.remove('show')
        })

        document.querySelectorAll('#cockpit .active').forEach(element => {
          element.classList.remove('active')
        })

        const id = 'cockpit_' + element.dataset.showtab
        document.getElementById(id)?.classList.add('show')
        element.classList.add('active')
      })
    })

    // Handles upload
    document.getElementById('upload').addEventListener('change', function () {
      const input = document.getElementById('upload')

      if (!input.files.length) {
        return
      }

      api.upload(input.files[0])
    })

    // Handles upload of addpic
    document.getElementById('upload_addpic').addEventListener('change', function () {
      const input = document.getElementById('upload_addpic')

      if (!input.files.length) {
        return
      }

      api.upload_addpic(input.files[0])
    })


  }

  // Switches languages.
  setLang( language ) {
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
