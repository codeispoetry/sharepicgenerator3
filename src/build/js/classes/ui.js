/* eslint-disable no-undef, no-unused-vars */

class UI {

  constructor () {
    document.querySelectorAll('[data-load]').forEach(element => {
        element.addEventListener('click', (event) => {
            const target = element.dataset.load
            api.load(target)
        })
    })

    document.querySelectorAll('[data-delete]').forEach(element => {
        element.addEventListener('click', (event) => {

            if( ! window.confirm('Are you sure?') ) {
                return false;
            }

            const target = element.dataset.delete
            api.delete(target)

            element.parentElement.remove();
        })
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


    document.querySelectorAll('[data-click]').forEach( element => {
        element.addEventListener('click', function () {
            let parts = this.dataset.click.split('.');
            let obj = window[parts[0]];
            let method = parts[1];

            if (obj && typeof obj[method] === 'function') {
                obj[method]();
            } else {
                console.log('Method ' + this.dataset.click + ' not found');
            }
        })  
    })

    document.querySelectorAll('[data-change]').forEach( element => {
        element.addEventListener('change', function () {
            let parts = this.dataset.change.split('.');
            let obj = window[parts[0]];
            let method = parts[1];

            if (obj && typeof obj[method] === 'function') {
                obj[method]();
            } else {
                console.log('Method ' + this.dataset.change + ' not found');
            }
        })  
    })

    document.querySelectorAll('[data-lang]').forEach( element => {
        element.addEventListener('click', function () {
            document.cookie = "lang=" + this.dataset.lang + "; path=/";
            window.document.location.reload();
        })  
    })

  }

 
}
