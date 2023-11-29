/* eslint-disable no-undef, no-unused-vars */

class Cockpit {
  /*
   * Clear all cockpit elements and show the sharepic cockpit
   */
  show_standard () {
    const elements = document.querySelectorAll('#cockpit .show')
    elements.forEach(element => {
      element.classList.remove('show')
    })
    document.getElementById('cockpit_sharepic')?.classList.add('show')
  }

  /*
    * Show the cockpit element for the given element
    */
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

  /*
    * Setup the cockpit for the given element
    */
  setup_sharepic () {
    document.getElementById('width').value = document.getElementById('sharepic').dataset.width
    document.getElementById('height').value = document.getElementById('sharepic').dataset.height
  }

  setup_text (element) {
    const slider = document.getElementById('text_size')
    slider.value = element.style.fontSize.replace('px', '')

    const allP = document.querySelectorAll('#text p')

    allP.forEach((line, index) => {
      const slider = document.getElementById('line_indent_' + index)
      slider.classList.toggle('show', (index < allP.length))
      slider.firstElementChild.value = line.style.marginLeft.replace('px', '')

      const layout = document.getElementById('line_layout_' + index)
      layout.classList.toggle('show', (index < allP.length))

      const classes = Array.from(line.classList)
      const allowedClasses = ['sandtanne', 'tannesand', 'sandklee', 'kleesand', 'grastanne', 'tannegras']
      const colorset = classes.filter(c => allowedClasses.includes(c)).join('')

      layout.value = colorset
    })
  }

  setup_freetext (element) {
    const slider = document.getElementById('text_size')
    slider.value = element.style.fontSize.replace('px', '')
  }

  setup_eyecatcher (element) {
    const slider = document.getElementById('eyecatcher_size')
    slider.value = element.style.fontSize.replace('px', '')
  }

  setup_logo (element) {
    const slider = document.getElementById('logo_size')
    slider.value = element.style.width.replace('px', '')

    const file = document.getElementById('logo_file')
    let url = element.style.backgroundImage.replace(/url\("(\.\.\/)*/, '').replace('")', '')
    if (!url.startsWith('/')) {
      url = '/' + url
    }
    file.value = url
  }
}
