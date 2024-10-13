/* eslint-disable no-undef, no-unused-vars */

class Settings {
    addColor ( color ) {
        if (config.palette.includes(color)) {
           alert(lang['Please choose a new color first'])
           return
        }
        config.palette.push(color)
        ui.addColorButton(color)
        this.save()
    }

    removeColor ( color ) {
        config.palette = config.palette.filter( c => c !== ui.rgbToHex(color) )
        ui.removeColorButton(color)
        this.save()
    }

    save() {
        const options = {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(config)
          }
      
          fetch(api.api + '&m=save_config', options)
            .then(response => {
              if (response.status !== 200) {
                throw new Error(response.status + ' ' + response.statusText)
              }
              return response.text()
            })
            .then(data => {
              if (config.debug) {
                console.log(data)
              }
              data = JSON.parse(data)
              console.log(data)
            })
            .catch(error => {
              console.error('Error:', error)
            })
    }
}
