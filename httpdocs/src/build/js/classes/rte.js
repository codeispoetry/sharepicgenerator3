/* eslint-disable no-undef */
/* eslint-disable no-unused-vars */
class RTE {
  constructor () {
    this.colorMap = []
    // this.init();
  }

  setColors () {
    this.colorMap = config.palette.map(colorCode => [colorCode, 'Color']).flat()
    // this.init();
  }

  addColor (colorCode) {
    this.colorMap.push(colorCode, 'Color')
    // this.init();
  }

  init () {
    document.getElementById('undo').style.opacity = 0
    const w = document.getElementById(cockpit.target.id).offsetWidth
    const h = document.getElementById(cockpit.target.id).offsetHeight
    document.getElementById(cockpit.target.id).style.removeProperty('width')

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
      content_css: '/src/Views/rte/rte.css',
      content_style: 'body, p { margin: 0; padding: 0; }',
      resize: 'both'
    })
  }

  deinit () {
    if (tinymce.activeEditor) {
      document.getElementById('undo').style.opacity = 1
      // const maxWidth = rte.getLargestChildOf(tinymce.activeEditor.getBody())
      // const id = tinymce.activeEditor.id

      const editorContainer = tinymce.activeEditor.getContainer()
      const width = editorContainer.offsetWidth
      const height = editorContainer.offsetHeight

      tinymce.remove(tinymce.activeEditor)
      document.getElementById(cockpit.target.id).style.width = `${width}px`
      document.getElementById(cockpit.target.id).style.height = `${height}px`
    }
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
