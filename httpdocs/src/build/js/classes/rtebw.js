/* eslint-disable no-undef */
/* eslint-disable no-unused-vars */
class RTEBW {

  init () {
    if (tinymce.activeEditor) {
      rtebw.deinit()
    }

    document.getElementById('undo').style.opacity = 0
    const w = document.getElementById(cockpit.target.id).offsetWidth
    const h = document.getElementById(cockpit.target.id).offsetHeight
    document.getElementById(cockpit.target.id).style.removeProperty('width')


    document.getElementsByTagName('header')[0].style.zIndex = 0

    tinymce.init({
      selector: `#${cockpit.target.id} .tinymce`,
      menubar: 'tools',
      plugins: 'lists',
      skin: 'oxide-dark',
      font_family_formats: 'GrueneTypeNeue;PT Sans',
      font_size_formats: '1em 2em 3em',
      toolbar: 'undo redo | fontfamily fontsize | forecolor | removeformat',
      color_map: [
        '#000000', 'Schwarz',
        '#f5f1e9', 'Sand',
        '#78a08c', 'Salbei',
        '#1c302a', 'Wald',
        '#e6fd53', 'Limette'
      ],
      width: `${w}px`,
      min_width: 100,
      min_height: 50,
      height: `${h}px`,
      license_key: 'gpl',
      branding: false,
      language: 'de',
      language_url: '/assets/tinymce/lang/de.js',
      content_css: '/src/Views/rte/rte.css',
      content_style: 'body, p { margin: 0; padding: 0; }',
      resize: 'both'
    })

    // there are some elements created by tinymce that we need to remove
    // cannot find the origin, why they are created and by whom
    window.setTimeout(() => {
      document.querySelector('div[aria-label="#D114EF"]')?.remove()
      document.querySelector('div[aria-label="#F8E7E7"]')?.remove()
    }, 1500)
  }

  deinit () {
    if (!tinymce.activeEditor) {
      return
    }

    document.getElementById('undo').style.opacity = 1

    const editorContainer = tinymce.activeEditor.getContainer()
    const width = editorContainer.offsetWidth
    const height = editorContainer.offsetHeight

    tinymce.remove(tinymce.activeEditor)
    if (cockpit.target === null) {
      return
    }

    document.getElementById(cockpit.target.id).style.width = `${width}px`
    document.getElementById(cockpit.target.id).style.height = `${height- 20}px`

    document.getElementsByTagName('header')[0].style.zIndex = 10
  }
}
