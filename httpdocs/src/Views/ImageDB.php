<div id="imagedb_page" class="imagedb_page">
    <h3 style="display: flex;justify-content: space-between;align-items: top;">
        <span id="image_db_source_name">ImageDB</span>
        <div class="closer" onClick="ui.close('#imagedb_page')" style="cursor: pointer;">
            <img src="assets/icons/close.svg">
        </div>
    </h3>
    <div class="button-group" style="display:flex">
        <input type="text" style="width:100%;" name="imagedb_q1" id="imagedb_q1" placeholder="<?php  echo _('search in image database');?>">
        <button onClick="imagedb.search( document.getElementById('imagedb_q1').value )"><img src="assets/icons/search.svg"></button> 
    </div>  
    
    <p>
        <?php echo _('Single click on an image sets the image as background, double click sets image and closes results.'); ?>
    </p>
    <div id="no_results" style="margin-bottom: 1em"></div>
    <div id="imagedb_results" class="imagedb_results"></div>
</div>