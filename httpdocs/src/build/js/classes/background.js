/* eslint-disable no-undef, no-unused-vars */

class Background {
  
    color (color) {
      document.getElementById('background_color').value = color
      document.getElementById('background').style.backgroundColor = color  
    }
  
    zoom (percentage) {
      const bg = document.getElementById('background')
  
  
      if (bg.style.backgroundSize === '' || bg.style.backgroundSize === 'cover') {
        bg.style.backgroundSize = '100%'
      }
  
      bg.style.backgroundSize = percentage + '%'
    }
  
    startDrag () {
      if( document.getElementById('drag_background').checked === false ) {
        return
      } 

      document.getElementById('background').addEventListener('mousemove', this.drag)
      background.start = undefined
    }

    stopDrag () {
      document.getElementById('background').removeEventListener('mousemove', this.drag)
    }

    drag( e ) {
      const bg = document.getElementById('background')

      const x = e.clientX - bg.getBoundingClientRect().left;
      const y = e.clientY - bg.getBoundingClientRect().top;

      if (background.start === undefined) {
        const left = bg.style.backgroundPositionX;
        const top = bg.style.backgroundPositionY;
        background.start = { x, y, top, left }
      }

      const diffX = x - background.start.x;
      const diffY = y - background.start.y;

      bg.style.backgroundPositionX = parseInt(background.start.left) + diffX + 'px';
      bg.style.backgroundPositionY = parseInt(background.start.top) + diffY + 'px';
    }
  
    
  
    reset () {
      const bg = document.getElementById('background')
  
      bg.style.backgroundSize = 'cover'
      bg.style.backgroundPositionX = '0px'
      bg.style.backgroundPositionY = '0px'
    }
  
    delete () {
      document.getElementById('background').style.backgroundImage = null
    }
  }

  