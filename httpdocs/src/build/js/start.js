/* eslint-disable no-undef, no-unused-vars, no-var */
var api, sg, cockpit, undo, imagedb, component, logger, ui, background, debug, rte, settings

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
  settings = new Settings()

  api.load('templates/' + config.starttemplate + '/start.html')

  //api.load('save/1/sharepic.html')

  window.setTimeout(function () {
    ui.addColorButtons()

    const w = document.getElementById('sharepic').dataset.width
    const h = document.getElementById('sharepic').dataset.height
  }, 1000)
}

window.addEventListener('beforeunload', function (event) {
  api.autosave()
})

window.addEventListener('keydown', function (event) {
  if (cockpit.target !== null && cockpit.target.id !== 'background' && tinymce.activeEditor === null && event.key === 'Backspace') {
    event.preventDefault()
    component.delete()
  }
})