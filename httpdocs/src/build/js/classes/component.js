/* eslint-disable no-undef, no-unused-vars */

class Component {
  add (item) {
    logger.log('wants to add component ' + item)
    const pattern = document.querySelector(`[data-id=${item}]`)

    // Check if the maximum number of elements is reached
    const max = pattern?.dataset?.max || -1
    const currentCount = document.querySelectorAll(`#sharepic [id^=${item}_]`).length
    if (max !== -1 && currentCount >= max) {
      alert(lang['Max reached'])
      return
    }
    const clonedElement = pattern.cloneNode(true)
    const newId = pattern.dataset.id + '_' + Math.round(Math.random() * 100)

    clonedElement.setAttribute('id', newId)
    clonedElement.removeAttribute('data-id')

    clonedElement.setAttribute('class', pattern.dataset.class)
    clonedElement.removeAttribute('data-class')

    // Insert the new element into the DOM
    document.getElementById('sharepic').insertAdjacentHTML('beforeend', clonedElement.outerHTML)

    const newElement = document.getElementById(newId)
    cockpit.target = newElement
    // Click on the new element
    const inputEvent = new Event('input')
    newElement.dispatchEvent(inputEvent)

    // and select it (the click event is not enough to select it, because the input event is not yet processed)
    component.select(newElement)

    this.toFront(newElement)

    logger.log('adds component ' + item)

    undo.commit()
  }

  select (element) {
    if (!element.classList.contains('freetext')) {
      rte.deinit()
    }

    // do not reselect, if the element is already selected
    if (element.classList.contains('selected')) {
      return
    }

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

  delete () {
    document.getElementById('add_copyright').style.display = 'flex'
    logger.log('deletes component ' + cockpit.target.id)

    cockpit.target.remove()
    this.unselect()
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
    let clientX, clientY

    component.select(event.target.closest('.draggable'))

    if (event.touches) {
      event.preventDefault()

      clientX = event.touches[0].clientX
      clientY = event.touches[0].clientY
    } else {
      if (event.button !== 0) {
        return
      }
      clientX = event.clientX
      clientY = event.clientY
    }

    cockpit.target = this.parentWithOnMouseDown(event.target) || console.error('No parent with onmousedown found')

    component.dragInfo = {
      xOffset: clientX - cockpit.target.getBoundingClientRect().left + document.getElementById('canvas').getBoundingClientRect().left,
      yOffset: clientY - cockpit.target.getBoundingClientRect().top + document.getElementById('canvas').getBoundingClientRect().top
    }

    document.addEventListener('mousemove', component.dragging)
    document.addEventListener('mouseup', component.stopDrag)

    document.addEventListener('touchmove', component.dragging)
    document.addEventListener('touchend', component.stopDrag)
  }

  dragging (e) {
    let clientX, clientY
    // e.preventDefault()

    if (e.touches) {
      clientX = e.touches[0].clientX
      clientY = e.touches[0].clientY
    } else {
      clientX = e.clientX
      clientY = e.clientY
    }

    let x = clientX - component.dragInfo.xOffset
    let y = clientY - component.dragInfo.yOffset

    // Do not allow to drag the element outside the canvas
    if (cockpit.target.dataset.dragconstraint === 'true') {
      const maxLeft = document.getElementById('canvas').offsetWidth - cockpit.target.offsetWidth
      const maxTop = document.getElementById('canvas').offsetHeight - cockpit.target.offsetHeight
      x = Math.min(Math.max(x, 0), maxLeft)
      y = Math.min(Math.max(y, 0), maxTop)

      cockpit.target.style.borderColor = 'black'
      if (x === 0 || x === maxLeft || y === 0 || y === maxTop) {
        cockpit.target.style.borderColor = 'red'
      }
    }

    cockpit.target.style.top = `${y}px`
    cockpit.target.style.left = `${x}px`
  }

  stopDrag () {
    document.removeEventListener('mousemove', component.dragging)
    document.removeEventListener('mouseup', component.stopDrag)
    document.removeEventListener('touchmove', component.dragging)
    document.removeEventListener('touchend', component.stopDrag)
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
