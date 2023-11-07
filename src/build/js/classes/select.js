/* eslint-disable no-undef, no-unused-vars */

class Select {
  setup () {
    this.clear_all()
    const elements = document.querySelectorAll('.selectable')
    elements.forEach(element => {
      element.addEventListener('mousedown', (event) => {
        event.stopPropagation()
        this.set_active(event.target)
      })
    })

    document.getElementById('canvas').addEventListener('click', (event) => {
      this.set_active(event.target)
    })
  }

  clear_all () {
    const elements = document.querySelectorAll('.selectable.active')
    elements.forEach(element => {
      element.classList.remove('active')
    })
    cockpit.clear_all()
  }

  set_active (element) {
    if (dragging === true) {
      return
    }
    const elements = document.querySelectorAll('.selectable')
    elements.forEach(element => {
      element.classList.remove('active')
    }
    )

    element.classList.add('active')

    cockpit.show(element)
  }
}
