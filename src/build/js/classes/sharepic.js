/* eslint-disable no-undef, no-unused-vars */

class Sharepic {

  constructor () {
    document.addEventListener('wheel', (event) => {
        if(this.draggable === false) {
          return
        }
        this.background_zoom(-event.deltaY/10);
    });

    this.start_drag();
  }

  init(){
    const sg = document.getElementById('sharepic');

    sg.addEventListener('mouseover', () => {
      this.draggable = true
    });

    sg.addEventListener('mouseout', () => {
      this.draggable = false
    });

    this.set_size();
  }

  background_zoom(step) {
    const sg = document.getElementById('sharepic');

    let backgroundSize = sg.style.backgroundSize;

    if(backgroundSize === '' || backgroundSize === 'cover') {
      sg.style.backgroundSize = '100%';
      sg.style.backgroundPositionX = '0px'
      sg.style.backgroundPositionY = '0px'
      sg.style.backgroundRepeat = 'no-repeat'
      sg.style.backgroundColor = 'white'
      this.start_drag()
    }    

    const style = window.getComputedStyle(sg);
    backgroundSize = style.getPropertyValue('background-size').replace('%', '');
    backgroundSize = parseInt(backgroundSize, 10);

    sg.style.backgroundSize = backgroundSize + step + '%';
  }

  start_drag() {
    const sg = document.getElementById('sharepic');

    const moveHandler = (event) => this.drag(event);

    sg.addEventListener('mousedown', (event) => {
      const sg = document.getElementById('sharepic');
      this.startMouseX = event.clientX;
      this.startMouseY = event.clientY;
      this.startBackgroundX =  parseInt(sg.style.backgroundPositionX.replace('px', ''), 10);
      this.startBackgroundY =  parseInt(sg.style.backgroundPositionY.replace('px', ''), 10);

      sg.addEventListener('mousemove', moveHandler);
    });

    sg.addEventListener('mouseup', (event) => {
      sg.removeEventListener('mousemove', moveHandler);
    });
  }

  drag(event) {
    const sg = document.getElementById('sharepic');

    const dx = event.clientX - this.startMouseX + this.startBackgroundX;
    const dy = event.clientY - this.startMouseY + this.startBackgroundY;

    sg.style.backgroundPositionX = dx + 'px';
    sg.style.backgroundPositionY = dy + 'px';
  }

  reset_background() {
    const sg = document.getElementById('sharepic')
    
    sg.style.backgroundColor = 'green'
    sg.style.backgroundSize = 'cover'
    sg.style.backgroundRepeat = 'no-repeat'
    sg.style.backgroundPositionX = '0px'
    sg.style.backgroundPositionY = '0px'
  }

  make_background_adjustable() {
    const sg = document.getElementById('sharepic')
    
    sg.style.backgroundColor = 'red'
    sg.style.backgroundSize = '100%'
    sg.style.backgroundRepeat = 'no-repeat'
    sg.style.backgroundPositionX = '0px'
    sg.style.backgroundPositionY = '0px'

    this.start_drag()
  }

  set_size() {
    const sg = document.getElementById('sharepic');
    let width = parseInt(document.getElementById('width').value);
    let height = parseInt(document.getElementById('height').value);
   
    const ratio = width / height;
    const max_width = 800
    const max_height = 600
    
    let zoom = Math.min(max_width / width, max_height / height);

    const new_width = width * zoom;
    const new_height = height * zoom;

    sg.style.width = new_width + 'px';
    sg.style.height = new_height + 'px';

    sg.dataset.zoom = zoom
    sg.dataset.width = width
    sg.dataset.height = height

  }
}
