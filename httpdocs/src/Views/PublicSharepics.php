<div id="public_sharepics" class="imagedb_page">
    <h3 style="display: flex;justify-content: space-between;align-items: top;">
        <span>
            <?php echo _('Public Sharepics'); ?>
        </span>
        <div class="closer" onClick="ui.close('#public_sharepics')" style="cursor: pointer;">
            <img src="assets/icons/close.svg">
        </div>
    </h3>
    
    <p>
        <?php echo _('Choose from public templates. Manage your own public templates in the the menu.'); ?>
    </p>

    <div class="button-group" style="display:flex;margin-bottom: 1em;">
        <input type="text" style="width:100%;" name="publics_filter" id="publics_filter" placeholder="<?php echo _('Filter ny name or owner'); ?>" oninput="publics.filter(this.value)">
        <button onclick="publics.filter( document.getElementById('publics_filter').value )"><img src="assets/icons/search.svg"></button> 
    </div>

    <div id="public_sharepic_results" class="imagedb_results">

    <?php
        $templates = glob( 'public_savings/*' );
        foreach( $templates as $dir ){
                $id = basename($dir);
                $name = json_decode( file_get_contents( $dir . '/info.json' ) )->name;
                $thumbnail = $dir . '/thumbnail.png';
                
                sprintf(
                    '<div class="image" style="background-image: url(%s)" onClick="api.load(\'public_savings/%s/sharepic.html\')")></div>', 
                    $thumbnail,
                    $id 
                );

        }
    ?>

    </div>
</div>