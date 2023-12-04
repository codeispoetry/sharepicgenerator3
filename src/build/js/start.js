/* eslint-disable no-undef, no-unused-vars, no-var */
var api, sg, cockpit, select, undo, pixabay, uploader, component, rte, quill, logger, ui

window.onload = function () {
  api = new API()
  api.load('tenants/de/start.html')

  sg = new Sharepic()
  cockpit = new Cockpit()
  select = new Select()
  undo = new Undo()
  pixabay = new Pixabay()
  uploader = new Uploader()
  component = new Component()
  ui = new UI()
  rte = new RichTextEditor()
  logger = new Logger()
}
