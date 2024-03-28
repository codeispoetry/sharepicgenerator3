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
