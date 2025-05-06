/* eslint-disable no-undef, no-unused-vars */

class Undo {
  constructor () {
    document.addEventListener('keydown', function (event) {
      if (event.ctrlKey && event.key === 'z') {
        this.undo()
      }

      if (event.key === 'Delete') {
        if (cockpit.target.id === 'greentext'  || (cockpit.target.id.startsWith('eyecatcher_') ) ) {
          return
        }

        console.log('Delete key pressed', cockpit.target.id)
        component.delete()
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
    // console.log('Committing', new Error().stack)

    const data = document.getElementById('canvas').innerHTML
    let commits = JSON.parse(localStorage.getItem('commits'))
    commits.push(data)

    if (commits.length > 20) {
      commits = commits.slice(-20)
    }

    localStorage.setItem('commits', JSON.stringify(commits))

    this.undoing = false
  }

  undo () {
    if (tinymce.activeEditor) {
      // use tinymce undo and not this global undo
      return
    }

    const commits = JSON.parse(localStorage.getItem('commits'))

    if (commits.length <= 1) {
      alert('Nothing to undo')
      return
    }

    // If we're not already undoing, pop the latest commit
    // so that we undo immediately and not the latest commit
    if (!this.undoing) {
      this.undoing = true
      commits.pop()
    }

    const latestCommit = commits.pop()

    localStorage.setItem('commits', JSON.stringify(commits))

    document.getElementById('canvas').innerHTML = latestCommit
    cockpit.setup_sharepic()
  }
}
