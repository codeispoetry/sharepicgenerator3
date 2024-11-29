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
    document.getElementById('undo').style.opacity = 0
    const w = document.getElementById(cockpit.target.id).offsetWidth
    const h = document.getElementById(cockpit.target.id).offsetHeight
    document.getElementById(cockpit.target.id).style.removeProperty('width')
    this.rotate = document.getElementById(cockpit.target.id).style.rotate
    document.getElementById(cockpit.target.id).style.removeProperty('rotate')

    tinymce.init({
      selector: `#${cockpit.target.id} .tinymce`,
      menubar: 'tools',
      plugins: 'lists',
      skin: 'oxide-dark',
      font_family_formats: freetext.font_family_formats,
      font_size_formats: '12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt',
      toolbar: 'undo redo | bold italic underline | fontfamily fontsize fontsizeinput lineheight | forecolor | removeformat',
      color_map: this.colorMap,
      width: `${w}px`,
      min_width: 100,
      height: `${h}px`,
      license_key: 'gpl',
      branding: false,
      language: 'de',
      language_url: '/assets/tinymce/lang/de.js',
      content_css: '/src/Views/rte/rte.css',
      content_style: 'body, p { margin: 0; padding: 0; }',
      resize: 'both'
    })
  }

  deinit () {
    if (!tinymce.activeEditor) {
      return
    }

    document.getElementById('undo').style.opacity = 1
    // const maxWidth = rte.getLargestChildOf(tinymce.activeEditor.getBody())
    // const id = tinymce.activeEditor.id

    const editorContainer = tinymce.activeEditor.getContainer()
    const width = editorContainer.offsetWidth
    const height = editorContainer.offsetHeight

    tinymce.remove(tinymce.activeEditor)
    if (cockpit.target === null) {
      return
    }

    document.getElementById(cockpit.target.id).style.width = `${width}px`
    document.getElementById(cockpit.target.id).style.height = `${height}px`
    document.getElementById(cockpit.target.id).style.rotate = this.rotate
  }

  getLargestChildOf (element) {
    let maxWidth = 0
    const children = element.children
    for (const child of children) {
      child.style.display = 'inline'
      const width = child.offsetWidth

      if (width > maxWidth) {
        maxWidth = width
      }

      child.style.display = 'block'
    }

    return maxWidth
  }
}
