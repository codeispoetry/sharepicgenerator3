/* eslint-disable no-undef, no-unused-vars */

var api, sg, cockpit, select, undo, pixabay, uploader, component, rte, quill

window.onload = function () {
  api = new API()
  api.load('tenants/free/start.html')

  sg = new Sharepic()
  cockpit = new Cockpit()
  select = new Select()
  undo = new Undo()
  pixabay = new Pixabay()
  uploader = new Uploader()
  component = new Component()
  new UI()
  rte = new RichTextEditor()
  
  /*
    * Handles the enter key in the editables
    */
  // document.getElementById('canvas').addEventListener('keydown', (e) => {
  //   if (e.key === 'Enter') {
  //     e.preventDefault()

  //     const sel = window.getSelection()
  //     if (sel.rangeCount) {
  //       const range = sel.getRangeAt(0)
  //       const caretPos = range.endOffset
  //       const currentPara = sel.anchorNode.parentNode
  //       const text = currentPara.textContent
  //       const beforeText = text.slice(0, caretPos)
  //       const afterText = text.slice(caretPos)

  //       // Modify the text of the current paragraph
  //       currentPara.textContent = beforeText

  //       // Create a new paragraph with the remaining text
  //       const newPara = document.createElement('p')
  //       newPara.textContent = afterText
  //       currentPara.parentNode.insertBefore(newPara, currentPara.nextSibling)

  //       // Move caret to the start of the new paragraph
  //       range.setStart(newPara.firstChild, 0)
  //       range.setEnd(newPara.firstChild, 0)
  //       sel.removeAllRanges()
  //       sel.addRange(range)
  //     }
  //   }
  // }) 

}
