<section class="mainsection" id="cockpit_freetext">
    <h2><?php  echo _('Text');?></h2>
    <section>
        <button onClick="component.add('freetext')"><?php  echo _('Add text');?></button>
    </section> 

    <section class="selected_only">
        <h3><?php  echo _('Total size');?></h3>
        <input type="range" min="0" max="100" value="50" class="slider" id="text_size" oninput="freetext.setSize(this)">      
    </section>
    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>

    <div id="rte-container">
        <textarea id="rte"></textarea>
    </div>
</section>

<script>
    class Freetext{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
        }

        setContent(content) {
            const editor = tinymce.get('rte');
            editor.setContent( content );
        }
    }
    const freetext = new Freetext();
</script>
<script src="/node_modules/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
