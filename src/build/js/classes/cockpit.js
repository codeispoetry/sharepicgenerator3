/* eslint-disable no-undef, no-unused-vars */

class Cockpit {
  clear_all () {
    const elements = document.querySelectorAll('#cockpit .show')
    elements.forEach(element => {
      element.classList.remove('show')
    })
    document.getElementById('cockpit_sharepic')?.classList.add('show')
  }

  show (element) {
    const elements = document.querySelectorAll('#cockpit .show')
    elements.forEach(element => {
      element.classList.remove('show')
    })

    const cockpitEelement = 'setup_' + element.dataset.cockpit
    if (typeof this[cockpitEelement] === 'function') {
      this[cockpitEelement](element)
    }

    const id = 'cockpit_' + element.dataset.cockpit
    document.getElementById(id)?.classList.add('show')
  }

  setup_sharepic () {
    const width = document.getElementById('width')
    const height = document.getElementById('height')
    width.value = document.getElementById('sharepic').style.width.replace('px', '')
    height.value = document.getElementById('sharepic').style.height.replace('px', '')
  }

  setup_text (element) {
    const slider = document.getElementById('text_size')
    slider.value = element.style.fontSize.replace('px', '')

    const allP = document.querySelectorAll('#text p');

    allP.forEach( (line, index) => {
      const slider = document.getElementById('text_indent_' + index)
      slider.classList.toggle('show', (index < allP.length))
      slider.firstElementChild.value = line.style.marginLeft.replace('px', '')
    })
  }

  setup_eyecatcher (element) {
    const slider = document.getElementById('eyecatcher_size')
    slider.value = element.style.fontSize.replace('px', '')
  }

  setup_logo (element) {
    const slider = document.getElementById('logo_size')
    slider.value = element.style.width.replace('px', '')
  }
}
