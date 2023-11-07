/* eslint-disable no-undef, no-unused-vars */

let api, cockpit, select, undo, pixabay, uploader

window.onload = function () {
  api = new API()
  api.load()

  cockpit = new Cockpit()
  select = new Select()
  undo = new Undo()
  pixabay = new Pixabay()
  uploader = new Uploader()

  document.getElementById('create').addEventListener('click', function () {
    const output = document.getElementById('output')
    output.src = ''
    api.create()
  })

  document.getElementById('reset').addEventListener('click', function () {
    api.load()
  })

  document.getElementById('load_latest').addEventListener('click', function () {
    api.load('users/tom/workspace/sharepic.html')
  })

  document.getElementById('upload').addEventListener('change', function () {
    const input = document.getElementById('upload')

    if (!input.files.length) {
      return
    }

    api.upload(input.files[0])
  })

  document.getElementById('width').addEventListener('change', function () {
    document.getElementById('sharepic').style.width = this.value + 'px'
  })
  document.getElementById('height').addEventListener('change', function () {
    document.getElementById('sharepic').style.height = this.value + 'px'
  })

  document.getElementById('canvas').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault()

      const sel = window.getSelection();
      if (sel.rangeCount) {
        const range = sel.getRangeAt(0);
        const caretPos = range.endOffset;
        const currentPara = sel.anchorNode.parentNode;
        const text = currentPara.textContent;
        const beforeText = text.slice(0, caretPos);
        const afterText = text.slice(caretPos);
  
        // Modify the text of the current paragraph
        currentPara.textContent = beforeText;
  
        // Create a new paragraph with the remaining text
        const newPara = document.createElement('p');
        newPara.textContent = afterText;
        currentPara.parentNode.insertBefore(newPara, currentPara.nextSibling);
  
        // Move caret to the start of the new paragraph
        range.setStart(newPara.firstChild, 0);
        range.setEnd(newPara.firstChild, 0);
        sel.removeAllRanges();
        sel.addRange(range);
      }
    }
  })

  document.querySelectorAll('.to-front').forEach(element => {
    element.addEventListener('click', (event) => {
      const highestZIndex = [...document.querySelectorAll('.draggable')].reduce((maxZIndex, element) => {
        const zIndex = parseInt(getComputedStyle(element).zIndex, 10)
        return isNaN(zIndex) ? maxZIndex : Math.max(maxZIndex, zIndex)
      }, 0)

      const target = element.dataset.target
      document.getElementById(target).style.zIndex = (highestZIndex + 1).toString()
    })
  })

  document.querySelectorAll('.delete').forEach(element => {
    element.addEventListener('click', (event) => {
      const target = element.dataset.target
      document.getElementById(target).remove()
    })
  })

  document.querySelectorAll('.closer').forEach(element => {
    element.addEventListener('click', (event) => {
      const target = element.dataset.target
      document.getElementById(target).classList.remove('show')
    })
  })

}
