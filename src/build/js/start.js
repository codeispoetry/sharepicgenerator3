/* eslint-disable no-undef, no-unused-vars, no-var */
var api, sg, cockpit, select, undo, pixabay, component, rte, quill, logger, ui

window.onload = function () {
  api = new API()
  api.load('templates/' + config.starttemplate + '/start.html')

  sg = new Sharepic()
  cockpit = new Cockpit()
  select = new Select()
  undo = new Undo()
  pixabay = new Pixabay()
  component = new Component()
  ui = new UI()
  rte = new RichTextEditor()
  logger = new Logger()
}

function rgbToHex (rgb) {
  const sep = rgb.indexOf(',') > -1 ? ',' : ' '
  rgb = rgb.substr(4).split(')')[0].split(sep)

  let r = (+rgb[0]).toString(16)
  let g = (+rgb[1]).toString(16)
  let b = (+rgb[2]).toString(16)

  if (r.length === 1) { r = '0' + r }
  if (g.length === 1) { g = '0' + g }
  if (b.length === 1) { b = '0' + b }

  return '#' + r + g + b
}

function makeSquare (target) {
  target.style.width = 'auto'
  target.style.height = 'auto'

  const width = parseInt(window.getComputedStyle(target).getPropertyValue('width'), 10)
  const height = parseInt(window.getComputedStyle(target).getPropertyValue('height'), 10)

  target.style.width = Math.max(width, height) + 'px'
  target.style.height = Math.max(width, height) + 'px'
}
