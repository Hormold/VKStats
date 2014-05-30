<?php if(!class_exists('raintpl')){exit;}?><form method="POST" action="?page=new" class="form-horizontal">
<fieldset>

<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Название проекта</label>  
  <div class="col-md-4">
  <input id="textinput" name="title" type="text" placeholder="ВКонтакте" class="form-control input-md">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Ссылка на сайт</label>  
  <div class="col-md-4">
  <input id="textinput" name="link" type="text" placeholder="http://vk.com/" class="form-control input-md">
  </div>
</div>

<div class="form-group"> <label class="col-md-4 control-label" for="textinput">&nbsp;</label>  
  <div class="col-md-4">
    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Создать проект</button>
  </div>
</div>

</fieldset>
</form>
