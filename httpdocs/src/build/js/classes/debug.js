/* eslint-disable no-undef, no-unused-vars */

class Debug {
  constructor () {
    window.onerror = (message, source, lineno, colno, error) => {
      if (config.debug_logged) {
        return
      }

      const bugDescription = 'Error: ' + message + ' in ' + source + ' at line ' + lineno + ' column ' + colno
      this.saveDebugInfo(bugDescription)

      document.querySelector('.bug-detected').style.display = 'block'

      config.debug_logged = true
    }

    const supportedBrowsers = ['Chrome', 'Firefox']
    if (!supportedBrowsers.includes(this.getBrowserInfo().name)) {
      document.querySelector('.browser-not-supported').style.display = 'block'
    }
  }

  getBrowserInfo () {
    const ua = navigator.userAgent; let tem
    let M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || []
    if (/trident/i.test(M[1])) {
      tem = /\brv[ :]+(\d+)/g.exec(ua) || []
      return { name: 'IE', version: (tem[1] || '') }
    }
    if (M[1] === 'Chrome') {
      tem = ua.match(/\b(OPR|Edge)\/(\d+)/)
      if (tem != null) return { name: tem[1].replace('OPR', 'Opera'), version: tem[2] }
    }
    M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?']
    if ((tem = ua.match(/version\/(\d+)/i)) != null) M.splice(1, 1, tem[1])
    return {
      name: M[0],
      version: M[1]
    }
  }

  saveDebugInfo (bugDescription) {
    const data = {
      data: document.getElementById('canvas').outerHTML,
      name: 'bug.html',
      mode: 'bug'
    }

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }

    return fetch(api.api + '&m=save', options)
      .then(response => {
        return response.json()
      })
      .then(data => {
        const browser = 'Browser: ' + this.getBrowserInfo().name + ' ' + this.getBrowserInfo().version
        const logLine = browser + '\t' + bugDescription + '\t' + data.full_path
        logger.log(logLine, 'bug')
      }
      )
      .catch(error => console.error('Error:', error))
  }
}
