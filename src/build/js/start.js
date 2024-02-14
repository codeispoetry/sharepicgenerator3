/* eslint-disable no-undef, no-unused-vars, no-var */
var api, sg, cockpit, undo, pixabay, component, rte, quill, logger, ui

window.onload = function () {
  api = new API()
  api.load('templates/' + config.starttemplate + '/start.html')
  sg = new Sharepic()
  cockpit = new Cockpit()
  undo = new Undo()
  pixabay = new Pixabay()
  component = new Component()
  ui = new UI()
  rte = new RichTextEditor()
  logger = new Logger()

  ui.showTab('search')
}
