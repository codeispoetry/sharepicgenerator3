/* eslint-disable no-undef, no-unused-vars */

const mouseDownEvent = new MouseEvent('mousedown', {
  bubbles: true,
  cancelable: true,
  button: 0
})

class UI {
  constructor () {
    document.querySelectorAll('[data-load]').forEach(element => {
      this.handleClickLoad(element)
    })

    document.querySelectorAll('[data-delete]').forEach(element => {
      this.handleClickDelete(element)
    })

    document.querySelectorAll('[data-pseudoselect]').forEach(element => {
      element.addEventListener('click', (event) => {
        let target = element.dataset.pseudoselect
        if (target === 'freetext') {
          target = 'text'
        }
        if (target === 'background') {
          target = 'sharepic'
        }

        document.querySelector('[data-id="' + target + '"]')?.dispatchEvent(mouseDownEvent)
        document.getElementById(target)?.dispatchEvent(mouseDownEvent)

        if (!element.classList.contains('active')) {
          alert(lang['No component'])
        }
      })
    })

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

    document.getElementById('upload').addEventListener('change', function () {
      const input = document.getElementById('upload')

      if (!input.files.length) {
        return
      }

      api.upload(input.files[0])
    })

    document.getElementById('upload_addpic').addEventListener('change', function () {
      const input = document.getElementById('upload_addpic')

      if (!input.files.length) {
        return
      }

      api.upload_addpic(input.files[0])
    })

    document.querySelectorAll('.closer').forEach(element => {
      element.addEventListener('click', (event) => {
        const target = element.dataset.target
        document.getElementById(target).classList.remove('show')
      })
    })

    document.querySelectorAll('.to-front').forEach(element => {
      element.addEventListener('click', (event) => {
        this.handleToFront(element)
      })
    })

    document.querySelectorAll('.to-back').forEach(element => {
      element.addEventListener('click', (event) => {
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
      })
    })

    document.querySelectorAll('[data-click]').forEach(element => {
      element.addEventListener('click', function () {
        const parts = this.dataset.click.split('.')
        const obj = window[parts[0]]
        const method = parts[1]

        if (obj && typeof obj[method] === 'function') {
          obj[method]()
        } else {
          console.log('Method ' + this.dataset.click + ' not found')
        }
      })
    })

    document.querySelectorAll('[data-change]').forEach(element => {
      element.addEventListener('change', function () {
        const parts = this.dataset.change.split('.')
        const obj = window[parts[0]]
        const method = parts[1]

        if (obj && typeof obj[method] === 'function') {
          obj[method]()
        } else {
          console.log('Method ' + this.dataset.change + ' not found')
        }
      })
    })

    document.querySelectorAll('[data-lang]').forEach(element => {
      element.addEventListener('click', function () {
        if (confirm(lang['All changes lost']) === false) {
          return false
        }
        document.cookie = 'lang=' + this.dataset.lang + '; path=/'
        window.document.location.reload()
      })
    })
  }

  handleClickLoad (element) {
    element.addEventListener('click', (event) => {
      const target = element.dataset.load
      api.load(target)
    })
  }

  handleClickDelete (element) {
    element.addEventListener('click', (event) => {
      if (!window.confirm(lang['Are you sure?'])) {
        return false
      }

      const target = element.dataset.delete
      api.delete(target)

      element.parentElement.remove()
    })
  }

  handleToFront (element) {
    const highestZIndex = [...document.querySelectorAll('.draggable')].reduce((maxZIndex, element) => {
      const zIndex = parseInt(getComputedStyle(element).zIndex, 10)
      return isNaN(zIndex) ? maxZIndex : Math.max(maxZIndex, zIndex)
    }, 0)
    cockpit.target.style.zIndex = (highestZIndex + 1).toString()
  }
}
