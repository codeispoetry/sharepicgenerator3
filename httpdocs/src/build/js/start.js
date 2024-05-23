/* eslint-disable no-undef, no-unused-vars, no-var */
var api, sg, cockpit, undo, imagedb, component, logger, ui, background, debug, rte

window.onload = function () {
  api = new API()
  background = new Background()
  sg = new Sharepic()
  cockpit = new Cockpit()
  undo = new Undo()
  imagedb = new ImageDB()
  component = new Component()
  ui = new UI()
  logger = new Logger()
  debug = new Debug()
  rte = new RTE()

  api.load('templates/' + config.starttemplate + '/start.html')
}
