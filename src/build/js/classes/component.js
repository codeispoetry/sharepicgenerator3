/* eslint-disable no-undef, no-unused-vars */

class Component {
  constructor () {
    document.querySelectorAll('.add_elements li').forEach(element => {
      element.addEventListener('click', (event) => {
        const item = element.dataset.item
        component.add(item)
      })
    })

    document.querySelectorAll('.delete').forEach(element => {
      element.addEventListener('click', (event) => {
        const target = element.dataset.target
        document.getElementById(target).remove()
      })
    })
  }

  add (item) {
    const sharepic = document.getElementById('sharepic')
    const element = sharepic.querySelector(`#${item}`)

    if (element) {
      console.log('Element already exists')
      //return
    }

    const pattern = document.querySelector(`[data-id=${item}]`)
    const clonedElement = pattern.cloneNode(true)

    clonedElement.setAttribute('id', pattern.dataset.id)
    clonedElement.setAttribute('class', pattern.dataset.class)

    sharepic.insertAdjacentHTML('beforeend', clonedElement.outerHTML)

    // make draggable
    const highestIndex = dragItems.reduce((max, item, index) => Math.max(max, index), 0)
    console.log("highestIndex", highestIndex, item)
    dragItems[highestIndex+1] = new Drag(item, highestIndex)

    // make selectable
    // const element = document.getElementById('eyecatcher')
    // element.addEventListener('mousedown', (event) => {
    //   event.stopPropagation()
    // })
  }
}
