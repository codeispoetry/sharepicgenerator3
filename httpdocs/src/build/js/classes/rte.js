class RTE{
    constructor(){
        this.colorMap = []
        this.init();
    }

    setColors(){
        this.colorMap = config.palette.map(colorCode => [colorCode, 'Color']).flat();
        this.init();
    }

    addColor(colorCode){
        this.colorMap.push(colorCode, 'Color');
        this.init();
    }

    init (){
        if ( tinymce.get('rte') !== undefined ){
            tinymce.remove('#rte');
        }

        tinymce.init({
            selector: '#rte',
            menubar: 'tools',
            plugins: 'lists',
            skin: 'oxide-dark',
            font_family_formats: '<?php echo Sharepicgenerator\Controllers\Fonts::get_font_family_formats(); ?>',
            font_size_formats: '12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt',
            toolbar: 'undo redo | bold italic underline | fontfamily fontsize fontsizeinput lineheight | forecolor | removeformat',
            color_map: this.colorMap,
            setup: function ( editor ) {
                editor.on('change keyup', function () {
                    cockpit.target.innerHTML = editor.getContent();
                });
                editor.on('focus', function () {
                document.getElementById('rte-container').classList.add('rte-focus');
                });
                editor.on('blur', function () {
                    document.getElementById('rte-container').classList.remove('rte-focus');
                });
            },
            'license_key': 'gpl',
            'branding': false,    
            'content_css': '/src/Views/rte/rte.css',
        });
    }
}
