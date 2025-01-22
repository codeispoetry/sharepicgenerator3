<div id="imagedb_page" class="imagedb_page">
    <div style="display:flex;justify-content:space-between;">
        <div>
            <h3>
                <span id="image_db_source_name">ImageDB</span>
            </h3>
            <div class="button-group" style="display:flex">
                <input type="text" style="width:100%;" name="imagedb_q1" id="imagedb_q1" placeholder="<?php  echo _('search in image database');?>">
                <button onClick="imagedb.search( document.getElementById('imagedb_q1').value )"><img src="assets/icons/search.svg"></button> 
            </div>  
            
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