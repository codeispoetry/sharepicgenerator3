/* eslint-disable no-undef, no-unused-vars */

class Drag {
  constructor (id, index) {
    this.item = document.getElementById(id)

    this.item.addEventListener('mousedown', (e) => {
      if (e.button !== 0 || e.target !== this.item) {
        return
      }

      dragging = index

      const canvas = document.getElementById('canvas').getBoundingClientRect()
      const dragItem = this.item.getBoundingClientRect()

      this.item.classList.add('dragging')

      this.offsetX = canvas.left - (dragItem.left - e.clientX)
      this.offsetY = canvas.top - (dragItem.top - e.clientY)
    })
  }

  move (x, y) {
    x -= this.offsetX
    y -= this.offsetY

    const doNotLeaveCanvas = true

    if (doNotLeaveCanvas) {
      const sq = document.getElementById('sharepic').getBoundingClientRect()

      const right = sq.width - this.item.clientWidth
      const bottom = sq.height - this.item.clientHeight

      x = Math.max(x, 10)
      y = Math.max(y, 10)
      x = Math.min(x, right)
      y = Math.min(y, bottom)
    }

    this.item.style.top = `${y}px`
    this.item.style.left = `${x}px`
  }
}
