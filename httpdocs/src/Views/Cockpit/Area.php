<section class="mainsection" id="cockpit_area">
    <h2><?php  echo _('Area');?></h2>
    <section>
        <label>
            <input type="checkbox" id="toggle_area" checked >
            <?php  echo _('Show green area');?>
        </label>
    </section>

   

</section>

<script>

    document.getElementById('toggle_area').addEventListener('click', function(event) {
        document.getElementById('bar').style.display = this.checked ? 'block' : 'none';

    });

</script>


