<div id="toolbar">
  <select class="ql-font">
    <option selected>Baloo</option>
    <option value="Baloo2">Baloo</option>
    <option value="Roboto-Light">Roboto</option>
	<option value="Calibri">Calibri</option>
  </select>

  <select class="ql-size">
    <option value="small"></option>
    <!-- Note a missing, thus falsy value, is used to reset to default -->
    <option selected></option>
    <option value="large"></option>
    <option value="huge"></option>
  </select>

  <button class="ql-bold"></button>
  <button class="ql-italic"></button>
  <button class="ql-underline"></button>
  <button class="ql-strike"></button>

  <button class="ql-list" value="ordered"></button>
  <button class="ql-list" value="bullet"></button>

  <button class="ql-script" value="sub"></button>
  <button class="ql-script" value="super"></button>

  <button class="ql-indent" value="-1"></button>
  <button class="ql-indent" value="+1"></button>
  <select class="ql-color">
  	<?php  echo colorOptions(); ?>
  </select>
  <select class="ql-background">
	<?php echo colorOptions(); ?>
  </select>

  <button class="ql-align" value=""></button>
  <button class="ql-align" value="center"></button>
  <button class="ql-align" value="right"></button>
  <button class="ql-align" value="justify"></button>

  <select class="ql-size" onChange="rte.setClass(this.value);">
	<option value="tanne">Tanne</option>
	<option value="klee">Klee</option>
  </select>
  <button class="ql-clean"></button>
</div>

<?php

function ColorOptions(){
	return '<option value="rgb(0, 0, 0)">
	<option value="rgb(230, 0, 0)">
	<option value="rgb(255, 153, 0)">
	<option value="rgb(255, 255, 0)">
	<option value="rgb(0, 138, 0)">
	<option value="rgb(0, 102, 204)">
	<option value="rgb(153, 51, 255)">
	<option value="rgb(255, 255, 255)">
	<option value="rgb(250, 204, 204)">
	<option value="rgb(255, 235, 204)">
	<option value="rgb(204, 224, 245)">
	<option value="rgb(235, 214, 255)">
	<option value="rgb(187, 187, 187)">
	<option value="rgb(102, 185, 102)">';
}
