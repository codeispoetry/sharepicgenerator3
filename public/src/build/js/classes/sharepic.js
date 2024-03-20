/* eslint-disable no-undef, no-unused-vars */

class Sharepic {
  constructor () {
    document.querySelectorAll('[data-sizepreset]').forEach(element => {
      element.addEventListener('click', function () {
        const sizePreset = this.dataset.sizepreset.split(':')
        document.getElementById('width').value = sizePreset[0]
        document.getElementById('height').value = sizePreset[1]

        const event = new Event('change')
        document.getElementById('height').dispatchEvent(event)
      })
    })

    document.getElementById('background_size').addEventListener('input', () => {
      const percentage = document.getElementById('background_size').value
      this.backgroundZoom(percentage)
    })

    this.startDrag()
  }

  init () {
    const sg = document.getElementById('sharepic')

    sg.addEventListener('mouseover', () => {
      this.draggable = true
    })

    sg.addEventListener('mouseout', () => {
      this.draggable = false
    })

    this.setSize()
    this.startDrag()
  }

  backgroundColor (btn) {
    document.getElementById('sharepic').style.backgroundColor = btn.value
  }

  backgroundZoom (percentage) {
    const sg = document.getElementById('sharepic')

    const backgroundSize = sg.style.backgroundSize

    if (backgroundSize === '' || backgroundSize === 'cover') {
      sg.style.backgroundSize = '100%'
      sg.style.backgroundPositionX = '0px'
      sg.style.backgroundPositionY = '0px'
      sg.style.backgroundRepeat = 'no-repeat'
      sg.style.backgroundColor = 'white'
      this.startDrag()
    }

    // const style = window.getComputedStyle(sg)
    // backgroundSize = style.getPropertyValue('background-size').replace('%', '')
    // backgroundSize = parseInt(backgroundSize, 10)

    sg.style.backgroundSize = percentage + '%'
  }

  startDrag () {
    const sg = document.getElementById('sharepic')

    const moveHandler = (event) => this.drag(event)

    sg.addEventListener('mousedown', (event) => {
      if (event.button !== 0) {
        return
      }

      if (!document.getElementById('drag_background').checked) {
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

  resetBackground () {
    const sg = document.getElementById('sharepic')

    sg.style.backgroundSize = 'cover'
    sg.style.backgroundRepeat = 'no-repeat'
    sg.style.backgroundPositionX = '0px'
    sg.style.backgroundPositionY = '0px'
  }

  deleteBackgroundImage () {
    const sg = document.getElementById('sharepic')

    sg.style.backgroundImage = 'none'
  }

  setSize () {
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

    this.putBackOnCanvas()

    document.getElementById('sharepic').classList.toggle('small', newWidth < 400)

  }

  /*
    Put all draggable elements back on canvas, in case they are
    outside. After dragging or changing the sharepic dimensions
  */
  putBackOnCanvas () {
    document.querySelectorAll('#sharepic .draggable').forEach(element => {
      const sharepicWidth = parseInt(document.getElementById('sharepic').style.width.replace('px', ''))
      const sharepicHeight = parseInt(document.getElementById('sharepic').style.height.replace('px', ''))
      if (element.style.left.replace('px', '') > sharepicWidth) {
        element.style.left = (sharepicWidth - element.offsetWidth) + 'px'
      }
      if (element.style.top.replace('px', '') > sharepicHeight) {
        element.style.top = (sharepicHeight - element.offsetHeight) + 'px'
      }

      if (element.style.left.replace('px', '') < -element.offsetWidth) {
        element.style.left = '0px'
      }
      if (element.style.top.replace('px', '') < -element.offsetHeight) {
        element.style.top = '0px'
      }
    })
  }
}
