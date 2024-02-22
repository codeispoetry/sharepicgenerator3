/* eslint-disable no-undef, no-unused-vars */

class Component {
  add (item) {
    const pattern = document.querySelector(`[data-id=${item}]`)

    const clonedElement = pattern.cloneNode(true)
    const newId = pattern.dataset.id + '_' + Math.round(Math.random() * 100)

    clonedElement.setAttribute('id', newId)
    clonedElement.removeAttribute('data-id')

    clonedElement.setAttribute('class', pattern.dataset.class)
    clonedElement.removeAttribute('data-class')

    // Insert the new element into the DOM
    document.getElementById('sharepic').insertAdjacentHTML('beforeend', clonedElement.outerHTML)

    // Initialize RichtTextEditor on the new element
    if (clonedElement.dataset.rte === 'true') {
      clonedElement.removeAttribute('data-rte')
      rte.add('#' + newId)
    }

    // Click on the new element
    const inputEvent = new Event('input')
    document.getElementById(newId).dispatchEvent(inputEvent)

    // and select it (the click event is not enough to select it, because the input event is not yet processed)
    component.select(document.getElementById(newId))

    undo.commit()
  }

  select (element) {
    document.querySelector('.selected')?.classList.remove('selected')
    element.classList.add('selected')
    ui.showTab(element.dataset.cockpit, element)
  }

  unselect () {
    document.querySelectorAll('.selected_only').forEach(element => {
      element.style.display = 'none'
    })

    document.querySelector('.selected')?.classList.remove('selected')
    cockpit.target = null
  }

  toFront (element) {
    if (cockpit.target === null) {
      return
    }
    const highestZIndex = [...document.querySelectorAll('.draggable')].reduce((maxZIndex, element) => {
      const zIndex = parseInt(getComputedStyle(element).zIndex, 10)
      return isNaN(zIndex) ? maxZIndex : Math.max(maxZIndex, zIndex)
    }, 0)
    cockpit.target.style.zIndex = (highestZIndex + 1).toString()

    undo.commit()
  }

  toBack (element) {
    if (cockpit.target === null) {
      return
    }
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
    let thisZIndex = 1
    sortedElementsByZIndex.forEach(element => {
      thisZIndex++
      element.style.zIndex = thisZIndex.toString()
    })

    cockpit.target.style.zIndex = 1

    undo.commit()
  }

  startDrag (event) {
    if (event.button !== 0) {
      return
    }

    cockpit.target = this.parentWithOnMouseDown(event.target) || console.error('No parent with onmousedown found')

    component.dragInfo = {
      xOffset: event.clientX - cockpit.target.getBoundingClientRect().left + document.getElementById('canvas').getBoundingClientRect().left,
      yOffset: event.clientY - cockpit.target.getBoundingClientRect().top + document.getElementById('canvas').getBoundingClientRect().top
    }

    document.addEventListener('mousemove', component.dragging)
    document.addEventListener('mouseup', component.stopDrag)
  }

  dragging (e) {
    e.preventDefault()

    let x = e.clientX - component.dragInfo.xOffset
    let y = e.clientY - component.dragInfo.yOffset

    // Do not allow to drag the element outside the canvas
    if (cockpit.target.dataset.dragconstraint === 'true') {
      const maxLeft = document.getElementById('canvas').offsetWidth - cockpit.target.offsetWidth
      const maxTop = document.getElementById('canvas').offsetHeight - cockpit.target.offsetHeight
      x = Math.min(Math.max(x, 0), maxLeft)
      y = Math.min(Math.max(y, 0), maxTop)
    }

    cockpit.target.style.top = `${y}px`
    cockpit.target.style.left = `${x}px`
  }

  stopDrag () {
    document.removeEventListener('mousemove', component.dragging)
    document.removeEventListener('mouseup', component.stopDrag)
    sg.putBackOnCanvas()
    undo.commit()
  }

  parentWithOnMouseDown (element) {
    while (element) {
      if (element.onmousedown) {
        return element
      }
      element = element.parentElement
    }
    return null
  }
}
