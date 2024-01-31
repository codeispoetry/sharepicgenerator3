/* eslint-disable no-undef, no-unused-vars */

class Undo {
  constructor () {
    document.addEventListener('keydown', function (event) {
      if (event.ctrlKey && event.key === 'z') {
        this.undo()
      }
    }.bind(this))

    localStorage.setItem('commits', JSON.stringify([]))

    // If we're undoing, we don't want to set the current state.
    this.undoing = false

    document.querySelectorAll('input').forEach(element => {
      element.addEventListener('blur', () => {
        this.commit()
      })
    })
  }

  commit () {
    const data = document.getElementById('canvas').innerHTML
    const commits = JSON.parse(localStorage.getItem('commits'))
    commits.push(data)
    localStorage.setItem('commits', JSON.stringify(commits))

    this.undoing = false
  }

  undo () {
    const commits = JSON.parse(localStorage.getItem('commits'))

    if (commits.length === 0) {
      alert("Nothing to undo")
      return
    }

    // If we're not already undoing, pop the latest commit
    // so that we undo immediately and not the latest commit
    if( !this.undoing ) {
      this.undoing = true
      commits.pop() 
    }

    const latestCommit = commits.pop()

    localStorage.setItem('commits', JSON.stringify(commits))

    document.getElementById('canvas').innerHTML = latestCommit
    registerDraggableItems()
    select.setup()
    cockpit.setup_sharepic()
  }
}
