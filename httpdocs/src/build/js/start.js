/* eslint-disable no-undef, no-unused-vars, no-var */
var api, sg, cockpit, undo, imagedb, component, logger, ui, background, debug, rte, settings, publics

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
  rtebw = new RTEBW()
  settings = new Settings()
  publics = new Public_Sharepics()

  api.load('templates/' + config.starttemplate + '/start.html')

  window.setTimeout(function () {
    ui.addColorButtons()

    const w = document.getElementById('sharepic').dataset.width
    const h = document.getElementById('sharepic').dataset.height
    sg.setPreset('1:1', 'Post quadratisch', 1080, 1080)

    logger.log('logs in', 'normal', 'logs in')
  }, 1000)
}

window.addEventListener('beforeunload', function (event) {
  api.autosave()
})

window.addEventListener('keydown', function (event) {
  if (cockpit.target !== null && cockpit.target.id !== 'background' && tinymce.activeEditor === null && event.key === 'Backspace') {

    if (cockpit.target.id.startsWith('eyecatcher_') || config.tenant === 'greens') {
      return
    }
    event.preventDefault()
    component.delete()
  }
})

document.getElementById('accept_terms')?.addEventListener('change', function (event) {
  const button = document.querySelector('dialog.full-modal button')
  button.disabled = !event.target.checked
})

document.querySelector('dialog.full-modal button')?.addEventListener('click', function (event) {
  const dialog = document.querySelector('#terms-of-use')
  config.terms_accepted = true
  settings.save()
  dialog.close()
})