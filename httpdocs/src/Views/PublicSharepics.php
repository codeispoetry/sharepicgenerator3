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
    <div id="imagedb_results" class="imagedb_results">

    <?php
        $templates = glob( 'public_savings/*' );

        foreach( $templates as $dir ){
                $id = basename($dir);
                $name = json_decode( file_get_contents( $dir . '/info.json' ) )->name;
                $thumbnail = $dir . '/thumbnail.png';
                
                printf(
                    '<div class="image" style="background-image: url(%s)" onClick="api.load(\'public_savings/%s/sharepic.html\')")></div>', 
                    $thumbnail,
                    $id 
                );

        }
    ?>

    </div>
</div>