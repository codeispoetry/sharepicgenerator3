/* eslint-disable no-undef, no-unused-vars */
class RichTextEditor {
  init () {
    const Font = Quill.import('formats/font')
    Font.whitelist = ['Baloo2', 'Roboto-Light', 'Calibri']
    Quill.register(Font, true)

    this.registerBlock('tanne')
    this.registerBlock('klee')
  }

  registerInline (name) {
    const Inline = Quill.import('blots/inline')
    class Blot extends Inline {
      static blotName = name
      static className = name
      static tagName = 'span'
    }
    Quill.register(Blot)
  }

  registerBlock (name) {
    const Block = Quill.import('blots/block')
    class Blot extends Block {
      static blotName = name
      static className = name
      static tagName = 'div'
    }
    Quill.register(Blot)
  }

  add (selector) {
    if (!document.querySelector(selector)) {
      return
    }

    quill = new Quill(selector, {
      modules: {
        toolbar: '#toolbar'
      },
      theme: 'bubble'
    })

    undo.commit()
  }

  setClass (classname) {
    const classNames = ['tanne', 'klee']
    const range = quill.getSelection(true)

    classNames.forEach((name) => {
      quill.formatLine(range.index, range.length, name, false)
    })

    quill.formatLine(range.index, range.length, classname, true)
  }
}
