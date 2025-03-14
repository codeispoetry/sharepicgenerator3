/* eslint-disable no-undef */
/* eslint-disable no-unused-vars */
class RTE {
  constructor () {
    this.colorMap = []
  }

  setColors () {
    this.colorMap = config.palette.map(colorCode => [colorCode, 'Color']).flat()
  }

  addColor (colorCode) {
    this.colorMap.push(colorCode, 'Color')
  }

  init () {
    if (tinymce.activeEditor) {
      rte.deinit()
    }

    document.getElementById('undo').style.opacity = 0
    const w = document.getElementById(cockpit.target.id).offsetWidth
    const h = document.getElementById(cockpit.target.id).offsetHeight
    document.getElementById(cockpit.target.id).style.removeProperty('width')
    this.rotate = document.getElementById(cockpit.target.id).style.rotate
    document.getElementById(cockpit.target.id).style.removeProperty('rotate')

    document.getElementsByTagName('header')[0].style.zIndex = 0

    tinymce.init({
      selector: `#${cockpit.target.id} .tinymce`,
      menubar: 'tools',
      plugins: 'lists',
      skin: 'oxide-dark',
      font_family_formats: freetext.font_family_formats,
      font_size_formats: '12px 14px 18px 24px 30px 36px 48px 60px 72px 96px',
      toolbar: 'undo redo | bold italic underline | fontfamily fontsize fontsizeinput lineheight alignleft aligncenter alignright | forecolor | removeformat',
      color_map: this.colorMap,
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
    document.getElementById(cockpit.target.id).style.rotate = this.rotate

    document.getElementsByTagName('header')[0].style.zIndex = 10
  }
}
