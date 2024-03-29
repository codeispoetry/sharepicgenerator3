<div id="pixabay_page" class="pixabay_page">
    <div style="display:flex;justify-content:space-between;">
        <div>
            <?php echo _('All images are from '); ?>
            <p>
                <?php echo _('Single click on an image sets the image as background, double click sets image and closes results.'); ?>
            </p>
            <a href="https://pixabay.com" target="_blank">Pixabay</a>.
        </div>
        <div class="closer" onClick="ui.close('#pixabay_page')">
            <img src="assets/icons/close.svg">
        </div>
    </div>
    <div id="pixabay_results" class="pixabay_results"></div>
</div>