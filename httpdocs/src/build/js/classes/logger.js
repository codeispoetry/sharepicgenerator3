/* eslint-disable no-undef, no-unused-vars */

class Logger {
  constructor () {
    this.log_data = {}
  }

  prepare_log_data (data, append = false) {
    if (append) {
      this.log_data = Object.assign({}, this.log_data, data)
    } else {
      this.log_data = data
    }
  }

  log (data, type = 'normal', short='') {

    const payload = {
      short,
      data,
      ...this.log_data
    }

    this.log_data = {}

    fetch(config.url + '/index.php?c=felogger&m=' + type, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    })
      .then(response => {
        if (response.status !== 200) {
          throw new Error(response.status + ' ' + response.statusText)
        }
        return response.text()
      })
      .then(data => {})
      .catch((error) => console.error('Error:', error))
  }
}
