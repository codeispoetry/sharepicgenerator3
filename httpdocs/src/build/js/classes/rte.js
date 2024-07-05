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
        const w= document.getElementById(cockpit.target.id).offsetWidth
        const h= document.getElementById(cockpit.target.id).offsetHeight
        console.log(w,h)
        document.getElementById(cockpit.target.id).style.removeProperty('width')
        tinymce.init({
            selector: `#${cockpit.target.id} .tinymce`,
            menubar: 'tools',
            plugins: 'lists',
            skin: 'oxide-dark',
            font_family_formats: freetext.font_family_formats,
            font_size_formats: '12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt',
            toolbar: 'undo redo | bold italic underline | fontfamily fontsize fontsizeinput lineheight | forecolor | removeformat',
            color_map: this.colorMap,
            width: `${w}px`,
            height: `${h}px`,
            setup: function ( editor ) {
                editor.on('change keyup', function () {                
                   // Silence is golden.
                });
                editor.on('init', function () {
                    const editorContainer = editor.getContainer();
                    const observer = new MutationObserver(function (mutations) {
                        mutations.forEach(function (mutation) {
                            if (mutation.attributeName === 'style') {
                                const width = editorContainer.offsetWidth;
                                const height = editorContainer.offsetHeight;
                                console.log(`Editor width: ${width}px, height: ${height}px`);
                            }
                        });
                    });
        
                    // Start observing the editor container for changes in attributes
                    observer.observe(editorContainer, {
                        attributes: true, // Listen for attribute changes
                        attributeFilter: ['style'] // Filter to only listen for style changes
                    });
                });
            },
            // init_instance_callback: function (editor) {
            //     const sharepic = document.getElementById('sharepic');
            //     const text = document.getElementById(cockpit.target.id);
            //     const editArea = document.querySelector('.tox-edit-area');
            //     const position={
            //         width: sharepic.offsetWidth - text.offsetLeft,
            //         height: sharepic.offsetHeight - text.offsetTop
            //     }

            //     // editArea.style.width = `${position.width + 4}px`;
            //     // editArea.style.height = `${position.height}px`;
            //     // document.querySelector('.tox-tinymce').style.height = `${position.height}px`;

            // },
            'license_key': 'gpl',
            'branding': false,    
            'content_css': '/src/Views/rte/rte.css',
            'content_style': 'body, p { margin: 0; padding: 0; }',
            'resize': 'both',
        });
    }

    deinit ( ){
        if (tinymce.activeEditor) {
 
            document.getElementById('undo').style.opacity = 0;
            // const maxWidth = rte.getLargestChildOf(tinymce.activeEditor.getBody())
            // const id = tinymce.activeEditor.id

            const editorContainer = tinymce.activeEditor.getContainer();
            const width = editorContainer.offsetWidth;
            const height = editorContainer.offsetHeight;

            tinymce.remove(tinymce.activeEditor);
            document.getElementById(cockpit.target.id).style.width = `${width}px`
            document.getElementById(cockpit.target.id).style.height = `${height}px`
        }
    }

    getLargestChildOf( element ){
        let maxWidth = 0
        const children = element.children
        for (const child of children) {
            
            child.style.display = 'inline'
            const width = child.offsetWidth

            if (width > maxWidth) {
                maxWidth = width
            }

            child.style.display = 'block'
        }

        return maxWidth
    }
}
