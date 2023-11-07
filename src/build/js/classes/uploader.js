/* eslint-disable no-undef, no-unused-vars */

class Uploader {
  constructor () {
    const dropArea = document.getElementById('canvas')
    dropArea.addEventListener('drop', this.handle, false)
  }

  handle (e) {
    e.preventDefault()
    e.stopPropagation()

    const files = e.dataTransfer.files

    api.upload(files[0])
  }
}
