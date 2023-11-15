/* eslint-disable no-undef, no-unused-vars */

class RichTextEditor {
  init () {
    var FontAttributor = Quill.import('attributors/class/font');
    FontAttributor.whitelist = fonts
    Quill.register(FontAttributor, true);

    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],    

        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],     
        [{ 'indent': '-1'}, { 'indent': '+1' }],          
      
        [{ 'size': ['small', false, 'large', 'huge'] }], 
      
        [{ 'color': [] }, { 'background': [] }],     
        [{ 'font': FontAttributor.whitelist }],
        [{ 'align': [] }],                         
    ];

    quill = new Quill('#text', {
      modules: {
        toolbar: toolbarOptions
      },
      theme: 'bubble'
    });

    this.set_fonts();

  }

  destroy() { 
    quill.disable();
  }

  set_fonts() {
    let elements = document.querySelectorAll('.ql-font .ql-picker-item');

    let style = document.createElement('style');
    elements.forEach((element) => {
      let fontName = element.dataset.value;
      
      style.innerHTML += `
        [data-value="${fontName}"] {
          font-family: '${fontName}', sans-serif;
        }
        [data-value="${fontName}"]::before {
          content: '${fontName}' !important;
        }
        .ql-font-${fontName} {
          font-family: '${fontName}', sans-serif;
        }
        @font-face {
          font-family: '${fontName}';
          font-style: normal;
          font-weight: 400;
          src: url('/assets/fonts/${fontName}.woff2') format('woff2');
        }
      `;        
    });

    document.getElementById('sharepic').appendChild(style);
  }
}
