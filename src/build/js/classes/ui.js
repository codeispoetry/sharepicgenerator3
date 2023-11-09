/* eslint-disable no-undef, no-unused-vars */

class UI {

  constructor () {
    document.getElementById('create').addEventListener('click', function () {
        const output = document.getElementById('output')
        output.src = ''
        api.create()
      })

    document.getElementById('reset').addEventListener('click', function () {
        api.load()
    })

    document.getElementById('load_latest').addEventListener('click', function () {
        api.load('users/tom/workspace/sharepic.html')
    })

    document.getElementById('upload').addEventListener('change', function () {
        const input = document.getElementById('upload')

        if (!input.files.length) {
        return
        }

        api.upload(input.files[0])
    })

    document.querySelectorAll('.closer').forEach(element => {
        element.addEventListener('click', (event) => {
            const target = element.dataset.target
            document.getElementById(target).classList.remove('show')
        })
    })

    document.querySelectorAll('.to-front').forEach(element => {
        element.addEventListener('click', (event) => {
            const highestZIndex = [...document.querySelectorAll('.draggable')].reduce((maxZIndex, element) => {
            const zIndex = parseInt(getComputedStyle(element).zIndex, 10)
            return isNaN(zIndex) ? maxZIndex : Math.max(maxZIndex, zIndex)
            }, 0)

            const target = element.dataset.target
            document.getElementById(target).style.zIndex = (highestZIndex + 1).toString()
        })
    })

    document.getElementById('width').addEventListener('change', function () {
        document.getElementById('sharepic').style.width = this.value + 'px'
    })

    document.getElementById('height').addEventListener('change', function () {
        document.getElementById('sharepic').style.height = this.value + 'px'
    })

  }
}
