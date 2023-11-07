/* eslint-disable no-undef, no-unused-vars */

let dragging = false
const dragItems = []

function registerDraggableItems () {
  const draggableElements = document.querySelectorAll('.draggable')
  draggableElements.forEach((element, index) => {
    dragItems[index] = new Drag(element.id, index)
  })
}

// Add the mousemove event listener
document.addEventListener('mousemove', function (e) {
  if (dragging === false) {
    return
  }

  e.preventDefault()

  const x = e.clientX
  const y = e.clientY

  // access object by variable name
  dragItems[dragging].move(x, y)
})

// Add the mouseup event listener
document.addEventListener('mouseup', function (e) {
  dragging = false
  undo.commit()
})
