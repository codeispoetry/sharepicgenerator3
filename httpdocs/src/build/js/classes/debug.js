/* eslint-disable no-undef, no-unused-vars */

class Debug {
  constructor () {
    window.onerror = (message, source, lineno, colno, error) => {
        const browser = 'Browser: ' + this.getBrowserInfo().name + ' ' + this.getBrowserInfo().version
        const bug = 'Error: ' + message + ' in ' + source + ' at line ' + lineno + ' column ' + colno;
        logger.log(browser + '\t' + bug, 'bug');

        document.querySelector('.bug-detected').style.display = 'block'
    };
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

  createReport( ) {

    document.getElementById('debug').style.display = 'block'
    logger.log('Browser: ' + this.getBrowserInfo().name + ' ' + this.getBrowserInfo().version + '\n');

    alert("Danke, Dein Report wurde gesendet.")
  }
}
