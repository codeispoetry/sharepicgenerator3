/* eslint-disable no-undef, no-unused-vars, no-var */
var api, sg, cockpit, undo, pixabay, component, rte, logger, ui, background

window.onload = function () {
  api = new API()
  background = new Background()
  sg = new Sharepic()
  cockpit = new Cockpit()
  undo = new Undo()
  pixabay = new Pixabay()
  component = new Component()
  ui = new UI()
  rte = new RichTextEditor()
  logger = new Logger()

  api.load('templates/' + config.starttemplate + '/start.html')

  document.getElementById('version').innerHTML = 'js4'
}
