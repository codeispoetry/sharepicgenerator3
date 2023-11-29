/* eslint-disable no-undef, no-unused-vars */

class Sharepic {
  constructor () {
    document.addEventListener('wheel', (event) => {
      if (this.draggable === false) {
        return
      }
      this.background_zoom(-event.deltaY / 10)
    })

    document.querySelectorAll('[data-sizepreset]').forEach(element => {
      element.addEventListener('click', function () {
        const sizePreset = this.dataset.sizepreset.split(':')
        document.getElementById('width').value = sizePreset[0]
        document.getElementById('height').value = sizePreset[1]

        const event = new Event('change')
        document.getElementById('height').dispatchEvent(event)
      })
    })

    this.start_drag()
  }

  init () {
    const sg = document.getElementById('sharepic')

    sg.addEventListener('mouseover', () => {
      this.draggable = true
    })

    sg.addEventListener('mouseout', () => {
      this.draggable = false
    })

    this.set_size()
    this.start_drag()
  }

  background_zoom (step) {
    const sg = document.getElementById('sharepic')

    let backgroundSize = sg.style.backgroundSize

    if (backgroundSize === '' || backgroundSize === 'cover') {
      sg.style.backgroundSize = '100%'
      sg.style.backgroundPositionX = '0px'
      sg.style.backgroundPositionY = '0px'
      sg.style.backgroundRepeat = 'no-repeat'
      sg.style.backgroundColor = 'white'
      this.start_drag()
    }

    const style = window.getComputedStyle(sg)
    backgroundSize = style.getPropertyValue('background-size').replace('%', '')
    backgroundSize = parseInt(backgroundSize, 10)

    sg.style.backgroundSize = backgroundSize + step + '%'
  }

  start_drag () {
    const sg = document.getElementById('sharepic')

    const moveHandler = (event) => this.drag(event)

    sg.addEventListener('mousedown', (event) => {
      if (event.button !== 0) {
        return
      }

      const sg = document.getElementById('sharepic')
      this.startMouseX = event.clientX
      this.startMouseY = event.clientY
      this.startBackgroundX = parseInt(sg.style.backgroundPositionX.replace('px', ''), 10)
      this.startBackgroundY = parseInt(sg.style.backgroundPositionY.replace('px', ''), 10)

      sg.addEventListener('mousemove', moveHandler)
    })

    sg.addEventListener('mouseup', (event) => {
      sg.removeEventListener('mousemove', moveHandler)
    })
  }

  drag (event) {
    const sg = document.getElementById('sharepic')

    const dx = event.clientX - this.startMouseX + this.startBackgroundX
    const dy = event.clientY - this.startMouseY + this.startBackgroundY

    sg.style.backgroundPositionX = dx + 'px'
    sg.style.backgroundPositionY = dy + 'px'
  }

  reset_background () {
    const sg = document.getElementById('sharepic')

    sg.style.backgroundSize = 'cover'
    sg.style.backgroundRepeat = 'no-repeat'
    sg.style.backgroundPositionX = '0px'
    sg.style.backgroundPositionY = '0px'
  }

  set_size () {
    const sg = document.getElementById('sharepic')
    const width = parseInt(document.getElementById('width').value)
    const height = parseInt(document.getElementById('height').value)

    const ratio = width / height
    const maxWidth = 800
    const maxHeight = 600

    const zoom = Math.min(maxWidth / width, maxHeight / height)

    const newWidth = width * zoom
    const newHeight = height * zoom

    sg.style.width = newWidth + 'px'
    sg.style.height = newHeight + 'px'

    sg.dataset.zoom = zoom
    sg.dataset.width = width
    sg.dataset.height = height
  }
}
