class Undo {
    
    constructor() {
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'z') {
                this.undo();
            }
        }.bind(this));

        localStorage.setItem('commits', JSON.stringify(new Array()));

        document.querySelector('[contenteditable]').addEventListener('blur', () => {
            this.commit();
        });

        document.querySelector('input').addEventListener('blur', () => {
            this.commit();
        });

    }

    commit(){
        const data = document.getElementById('canvas').innerHTML;
        const commits = JSON.parse(localStorage.getItem('commits'));
        commits.push(data);
        localStorage.setItem('commits', JSON.stringify(commits));
    }

    undo(){
        const commits = JSON.parse(localStorage.getItem('commits'));

        console.log(commits.length)

        if(commits.length === 0){
            return;
        }

        const latestCommit = commits.pop();
        localStorage.setItem('commits', JSON.stringify(commits));
    

        document.getElementById('canvas').innerHTML = latestCommit;
        register_draggable_items();
        select.setup();
        cockpit.setup_sharepic();
    }

}