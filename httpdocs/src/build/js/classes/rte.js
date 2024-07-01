class RTE{
    constructor(){
        this.colorMap = []
       // this.init();
    }

    setColors(){
        this.colorMap = config.palette.map(colorCode => [colorCode, 'Color']).flat();
       // this.init();
    }

    addColor(colorCode){
        this.colorMap.push(colorCode, 'Color');
        //this.init();
    }

    init (){
        document.getElementById('undo').style.opacity = 0;
        tinymce.init({
            selector: `#${cockpit.target.id} .tinymce`,
            menubar: 'tools',
            plugins: 'lists',
            skin: 'oxide-dark',
            font_family_formats: freetext.font_family_formats,
            font_size_formats: '12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt',
            toolbar: 'undo redo | bold italic underline | fontfamily fontsize fontsizeinput lineheight | forecolor | removeformat',
            color_map: this.colorMap,
            setup: function ( editor ) {
                editor.on('change keyup', function () {                
                   // Silence is golden.
                });
            },
            init_instance_callback: function (editor) {
                const sharepic = document.getElementById('sharepic');
                const text = document.getElementById(cockpit.target.id);
                const editArea = document.querySelector('.tox-edit-area');
                const position={
                    width: sharepic.offsetWidth - text.offsetLeft,
                    height: sharepic.offsetHeight - text.offsetTop
                }
                // text.style.width = `${position.width}px`;
                // text.style.height = `${position.height}px`;
                editArea.style.width = `${position.width}px`;
                editArea.style.height = `${position.height}px`;
            },
            'license_key': 'gpl',
            'branding': false,    
            'content_css': '/src/Views/rte/rte.css',
            'content_style': 'body, p { margin: 0; padding: 0; }',
        });
    }

    deinit ( id = 'tinymce' ){
        if (tinymce.activeEditor) {
            document.getElementById('undo').style.opacity = 0;
            tinymce.remove(tinymce.activeEditor);
        }
    }
}
