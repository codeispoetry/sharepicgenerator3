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

    const before = {
      width: bg.offsetWidth * (bg.style.backgroundSize.replace('%', '') / 100),
      height: bg.offsetHeight * (bg.style.backgroundSize.replace('%', '') / 100)
    }

    bg.style.backgroundSize = percentage + '%'

    const after = {
      width: bg.offsetWidth * (percentage / 100),
      height: bg.offsetHeight * (percentage / 100)
    }

    const diff = {
      x: (after.width - before.width) / 2,
      y: (after.height - before.height) / 2
    }

    bg.style.backgroundPositionX = parseInt(bg.style.backgroundPositionX.replace('px', '')) - diff.x + 'px'
    bg.style.backgroundPositionY = parseInt(bg.style.backgroundPositionY.replace('px', '')) - diff.y + 'px'
  }

  startDrag () {
    if (document.getElementById('drag_background').checked === false) {
      return
    }

    const bg = document.getElementById('background')

    bg.addEventListener('mousemove', this.drag)
    background.start = undefined
  }

  stopDrag () {
    document.getElementById('background').removeEventListener('mousemove', this.drag)
  }

  drag (e) {
    const bg = document.getElementById('background')

    const x = e.clientX - bg.getBoundingClientRect().left
    const y = e.clientY - bg.getBoundingClientRect().top

    const sharepicWidth = parseInt(document.getElementById('sharepic').style.width.replace('px', ''))
    const sharepicHeight = parseInt(document.getElementById('sharepic').style.height.replace('px', ''))
    if (x + 5 > sharepicWidth || y + 5 > sharepicHeight || x < 0 || y < 0) {
      background.stopDrag()
      return
    }

    if (background.start === undefined) {
      const left = bg.style.backgroundPositionX
      const top = bg.style.backgroundPositionY
      background.start = { x, y, top, left }
    }

    const diffX = x - background.start.x
    const diffY = y - background.start.y

    bg.style.backgroundPositionX = parseInt(background.start.left) + diffX + 'px'
    bg.style.backgroundPositionY = parseInt(background.start.top) + diffY + 'px'
  }

  reset () {
    const bg = document.getElementById('background')

    bg.style.backgroundSize = 'cover'
    bg.style.backgroundPositionX = '0px'
    bg.style.backgroundPositionY = '0px'
  }

  delete () {
    document.getElementById('background').style.backgroundImage = null
    background.setCredits('')
    ui.showSearchImageTab('background')
  }

  filter (key, value) {
    const bg = document.getElementById('background')
    const filters = bg.style.filter.split(' ')

    const filterIndex = filters.findIndex(filter => filter.startsWith(key))

    if (key === 'blur') {
      value = value + 'px'
    }

    if (filterIndex !== -1) {
      filters[filterIndex] = `${key}(${value})`
    } else {
      filters.push(`${key}(${value})`)
    }

    bg.style.filter = filters.join(' ')
  }

  setCredits (text) {
    document.querySelector('#image-credits').innerHTML = text
    const copyright = document.querySelector('#sharepic [id^=copyright_]')
    if (copyright) {
      copyright.innerHTML = text
    }
  }
}
