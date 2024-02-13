/* eslint-disable no-undef, no-unused-vars */

class Component {
  constructor () {

  }

  add (item) {
    const sharepic = document.getElementById('sharepic')
    // const element = sharepic.querySelector(`#${item}`)
    const pattern = document.querySelector(`[data-id=${item}]`)

    if (pattern.dataset?.max === '1') {
      console.log('Element already exists')
      return
    }

    const clonedElement = pattern.cloneNode(true)
    const newId = pattern.dataset.id + '_' + Math.round(Math.random() * 100)
    clonedElement.setAttribute('id', newId)
    clonedElement.setAttribute('class', pattern.dataset.class)
    clonedElement.style.top = '20px'
    clonedElement.style.left = '20px'

    sharepic.insertAdjacentHTML('beforeend', clonedElement.outerHTML)

    // Trigger the input event listener
    const inputEvent = new Event('input')
    document.getElementById(newId).dispatchEvent(inputEvent)

    // make draggable
    const nextIndex = dragItems.reduce((max, item, index) => Math.max(max, index), 0) + 2
    dragItems[nextIndex] = new Drag(newId, nextIndex)

    select.setActive(document.getElementById(newId))
  }
}
