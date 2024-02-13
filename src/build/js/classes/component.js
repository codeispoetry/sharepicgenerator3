/* eslint-disable no-undef, no-unused-vars */

class Component {
  add (item) {
    const sharepic = document.getElementById('sharepic')
    const pattern = document.querySelector(`[data-id=${item}]`)

    const clonedElement = pattern.cloneNode(true)
    const newId = pattern.dataset.id + '_' + Math.round(Math.random() * 100)
    clonedElement.setAttribute('id', newId)
    clonedElement.setAttribute('class', pattern.dataset.class)
    clonedElement.style.top = '20px'
    clonedElement.style.left = '20px'

    sharepic.insertAdjacentHTML('beforeend', clonedElement.outerHTML)

    // Click on the new element
    const inputEvent = new Event('input')
    document.getElementById(newId).dispatchEvent(inputEvent)

    // this should be done by the above line: clicking on the element
    cockpit.target = document.getElementById(newId)

    // make draggable
    // const nextIndex = dragItems.reduce((max, item, index) => Math.max(max, index), 0) + 2
    // dragItems[nextIndex] = new Drag(newId, nextIndex)
  }
}
