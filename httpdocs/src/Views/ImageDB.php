<div id="imagedb_page" class="imagedb_page">
    <div style="display:flex;justify-content:space-between;">
        <div>
            <p>
                <?php echo _('Single click on an image sets the image as background, double click sets image and closes results.'); ?>
            </p>
        </div>
        <div class="closer" onClick="ui.close('#imagedb_page')">
            <img src="assets/icons/close.svg">
        </div>
    </div>
    <div id="imagedb_results" class="imagedb_results"></div>
</div>