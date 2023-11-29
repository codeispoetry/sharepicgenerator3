/* eslint-disable no-undef, no-unused-vars */

class Select {
  setup () {
    this.unselect_all()
    const elements = document.querySelectorAll('.selectable')
    const sharepic = document.getElementById('sharepic')

    elements.forEach(element => {
      element.addEventListener('mousedown', (event) => {
        // do nothing on right click
        if (event.button !== 0) {
          return
        }
        event.stopPropagation()
        let target = event.target

        if (target.classList.contains('selectable')) {
          this.set_active(target)
          return
        }

        while (target !== sharepic) {
          if (target.classList.contains('selectable')) {
            this.set_active(target)
            break
          }
          target = target.parentNode
        }
      })
    })
  }

  unselect_all () {
    const elements = document.querySelectorAll('.selectable.active')
    elements.forEach(element => {
      element.classList.remove('active')
    })
    cockpit.show_standard()
  }

  set_active (element) {
    if (dragging === true) {
      return
    }
    this.unselect_all()

    element.classList.add('active')
    cockpit.show(element)
  }
}
