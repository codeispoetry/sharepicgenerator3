/* eslint-disable no-undef, no-unused-vars */
class RichTextEditor {
  init () {

  }

  add (selector) {
    if (!document.querySelector(selector)) {
      return
    }

    const fontAttributor = Quill.import('attributors/class/font');
    fontAttributor.whitelist = ['Baloo2', 'Roboto-Light', 'Calibri']
    Quill.register(fontAttributor, true)

    const toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
      ['blockquote', 'code-block'],
    
      [{ 'header': 1 }, { 'header': 2 }],               // custom button values
      [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
      [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
      [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    
      [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    
      [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
      [{ 'font': fontAttributor.whitelist }],
      [{ 'align': [] }],
    
      ['clean']                                         // remove formatting button
    ];

    quill = new Quill(selector, {
      modules: {
        toolbar: toolbarOptions
      },
      theme: 'bubble'
    })

    undo.commit()
  }

}
