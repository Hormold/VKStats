<?php if(!class_exists('raintpl')){exit;}?><form method="POST" action="?page=actions.new&id=<?php echo $pid;?>" class="form-horizontal">
<fieldset>
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Название действия</label>  
  <div class="col-md-4">
  <input id="textinput" name="title" type="text" placeholder="BLUE_BUTTON" class="form-control input-md">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Тип действия</label>  
  <div class="col-md-4">
  <select onChange="fixInput(this.value);" name="type" class="form-control input-md">
    <option value="redirect">redirect</option>
    <option value="action">action</option>
  </select>
  </div>
</div>

<div class="form-group" id="val" >
  <label class="col-md-4 control-label" for="textinput">Значение</label>  
  <div class="col-md-4">
  <input name="value" type="text" placeholder="http://vk.com/" class="form-control input-md">
  </div>
</div>

<div class="form-group" id="target">
  <label class="col-md-4 control-label" for="textinput">Целевое действие</label>  
  <div class="col-md-4">
  <input name="target_action" type="checkbox" placeholder="" class="form-control input-md">
  </div>
</div>

<div class="form-group"> <label class="col-md-4 control-label" for="textinput">&nbsp;</label>  
  <div class="col-md-4">
    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Создать дейстие</button>
  </div>
</div>

</fieldset>
</form>

<script>
function fixInput(value){
  if(value!=="action"){
    $("#val").show();
  }else{
    $("#val").hide();
  }
}
</script>