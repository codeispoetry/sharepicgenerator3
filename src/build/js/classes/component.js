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

    clonedElement.style.top = '20px'
    clonedElement.style.left = '20px'

    // Insert the new element into the DOM
    document.getElementById('sharepic').insertAdjacentHTML('beforeend', clonedElement.outerHTML)

    // Click on the new element
    const inputEvent = new Event('input')
    document.getElementById(newId).dispatchEvent(inputEvent)

    // and select it (the click event is not enough to select it, because the input event is not yet processed)
    component.select(document.getElementById(newId))
  }

  select (element) {
    element.classList.add('selected')
    ui.showTab(element.dataset.cockpit)

    cockpit.target = element
    const cockpitEelement = 'setup_' + element.dataset.cockpit
    if (typeof cockpit[cockpitEelement] === 'function') {
      cockpit[cockpitEelement](element)
    }
  }

  toFront (element) {
    console.log(element)
    const highestZIndex = [...document.querySelectorAll('.draggable')].reduce((maxZIndex, element) => {
      const zIndex = parseInt(getComputedStyle(element).zIndex, 10)
      return isNaN(zIndex) ? maxZIndex : Math.max(maxZIndex, zIndex)
    }, 0)
    cockpit.target.style.zIndex = (highestZIndex + 1).toString()
  }

  toBack (element) {
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
  }

  startDrag (event) {
    if (event.button !== 0 || event.target.classList.contains('draggable') === false) {
      return
    }

    cockpit.target = event.target

    component.dragInfo = {
      xOffset: event.clientX - cockpit.target.getBoundingClientRect().left + document.getElementById('canvas').getBoundingClientRect().left,
      yOffset: event.clientY - cockpit.target.getBoundingClientRect().top + document.getElementById('canvas').getBoundingClientRect().top
    }

    document.addEventListener('mousemove', component.dragging)
    document.addEventListener('mouseup', component.stopDrag)
  }

  dragging (e) {
    e.preventDefault()

    const x = e.clientX - component.dragInfo.xOffset
    const y = e.clientY - component.dragInfo.yOffset

    cockpit.target.style.top = `${y}px`
    cockpit.target.style.left = `${x}px`
  }

  stopDrag () {
    document.removeEventListener('mousemove', component.dragging)
    document.removeEventListener('mouseup', component.stopDrag)
  }

}
