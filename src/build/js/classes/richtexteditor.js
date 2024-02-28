/* eslint-disable no-undef, no-unused-vars */
class RichTextEditor {
  init () {

  }

  add (selector) {
    if (!document.querySelector(selector)) {
      return
    }

    this.setPosition()

    undo.commit()
  }

  setPosition(){
    const text = cockpit.target.getBoundingClientRect();
    const rte = document.querySelector('#rte');
  
    const toolbarHeight = parseInt(getComputedStyle(document.querySelector('#rte')).height.replace('px', ''))
    rte.style.top = text.top - toolbarHeight - 15 + 'px';
    rte.style.left = text.left + 'px';
  }

  hide(){
    const rte = document.querySelector('#rte');  
    rte.style.top = '-100px';
  }

  setStyle(styleName, value){
    const selection = window.getSelection();
    if (!selection.rangeCount) {
      return
    }

    const range = selection.getRangeAt(0);
    const clonedSelection = range.cloneContents();

    let styleChanged = false;

    clonedSelection.childNodes.forEach(function(node) {
      if (node.nodeType === Node.ELEMENT_NODE && node.style[styleName] ){
        node.style[styleName] = value;
        styleChanged = true;
      }
    });

    if( styleChanged) {
      range.deleteContents();
      range.insertNode(clonedSelection);
      return;
    }

    const tag = document.createElement('span');
    tag.appendChild(clonedSelection);
    tag.style[styleName] = value
    range.deleteContents();
    range.insertNode(tag);
  }

  clearFormat(){
    const selection = window.getSelection();
    if (!selection.rangeCount) {
        return;
    }

    const range = selection.getRangeAt(0);
    var textNode = document.createTextNode(range.toString());

    range.deleteContents();
    range.insertNode(textNode);
  }


}
