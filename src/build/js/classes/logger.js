/* eslint-disable no-undef, no-unused-vars */

class Logger {

    constructor () {
        this.log_data = {}
    }
   
    prepare_log_data( data ) {
        this.log_data = data
    }

    log (data) {
        const payload = {
            data,
            ...this.log_data
        };

        fetch('/index.php/felogger/normal/', {
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
            .catch((error) => console.error('Error:', error));

    }

}
