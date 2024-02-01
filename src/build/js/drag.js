/* eslint-disable no-undef, no-unused-vars */

let dragging = false
const dragItems = []

function registerDraggableItems () {
  const draggableElements = document.querySelectorAll('.draggable')
  draggableElements.forEach((element, index) => {
    dragItems[index] = new Drag(element.id, index)
  })
}

document.addEventListener('mousemove', function (e) {
  if (dragging === false) {
    return
  }
  e.preventDefault()

  const x = e.clientX
  const y = e.clientY

  // drag, even if element is clipped outside of the sharepic
  if (e.target.dataset.clipping === 'true') {
    dragItems[dragging].move(x, y, true)
    return
  }

  // stop dragging when mouse leaves the sharepic
  const rect = document.getElementById('sharepic').getBoundingClientRect()
  if (x < rect.left || x > rect.left + rect.width || y < rect.top || y > rect.top + rect.height) {
    stopDragging()
    return
  }

  dragItems[dragging].move(x, y)
})

document.addEventListener('mouseup', function (e) {
  if (dragging === false) {
    return
  }
  stopDragging()
  undo.commit()
})

function stopDragging () {
  document.getElementsByClassName('dragging')[0]?.classList.remove('dragging')
  dragging = false
}
