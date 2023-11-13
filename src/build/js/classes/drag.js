/* eslint-disable no-undef, no-unused-vars */

class Drag {
  constructor (id, index) {
    this.item = document.getElementById(id)

    this.item.addEventListener('mousedown', (e) => {
      if( e.button !== 0) {
        return
      }
      dragging = index

      const canvas = document.getElementById('canvas').getBoundingClientRect()
      const dragItem = this.item.getBoundingClientRect()

      this.offsetX = canvas.left - (dragItem.left - e.clientX)
      this.offsetY = canvas.top - (dragItem.top - e.clientY)
    })
  }

  move (x, y) {
    x -= this.offsetX
    y -= this.offsetY

    this.item.style.top = `${y}px`
    this.item.style.left = `${x}px`
  }
}
