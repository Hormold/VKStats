<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<script src="lib.js" type="text/javascript"></script>
	<link rel="stylesheet" href="etc/main.css">
	<script>
	VKStats.init({
		"project_id":1
	});
	</script>
</head>
<body bgcolor="#FFFFFF" onLoad="loaded();">
<div class="opacity">
	<div class="right"><a href="javascript://return void();" onClick="VKStats.make_session(); location.href='/';">Сбросить сессию</a><br />
	Зеленка кнопка/текст - действие уже было сделано.<br />
	Отключенная кнопка - действие целевое, выполнено.<br />

	</div>

	<div id="step1">

		<h3>Выберите доступный проект</h3>
		<select id="step1_box" onChange="step2(this.value)">
			<option value="-1">=== Выбрать ===</option>
		</select>
	</div>

	<div id="step2">
		<h3>Доступные элементы:</h3>
	</div>

	<div id="step1_error">
		<h3>Ошибка! Доступ запрещен! Попробуйте сбросить сессию.</h3>
	</div>

	
</div>

<script>
	function doClick(button){
		name=button.getAttribute("data-id");
		if(VKStats.trackAction({action: name,pid: button.getAttribute("data-pid")}).response){
			if(button.getAttribute("data-target")==1){
				alert("Target Action is Done.");
				button.setAttribute("disabled","disabled");
			}else{
				button.className="green";
				button.innerHTML+="!";
			}
		}
	}

	function doClick_link(link){
		name=link.getAttribute("data-id");
		if(VKStats.trackAction({action: name, pid: link.getAttribute("data-pid")}).response){
			if(link.getAttribute("data-target")==1){
				alert("Target Action Link is Done.");
				link.href = "javascript:void(0)";
			}else{
				link.className="green";
				link.innerHTML+="!";
			}
		}
	}

	function step2(value){ // Генерация "сайта"
		if(value=="-1"){ return false; }
		step2_items = document.getElementById("step2");
		if(VKStats.isAllowed({
			pid: value,
			uid: VKStats.cookie("__vk_id")
		}).response){
			actions=VKStats.getActions({pid:value});
			step2_items.style.display="block";
			if(actions.response!==null){
				for(i in actions.response){
					action=actions.response[i];
					if(action.type=="action"){
						b = document.createElement("button");
						b.setAttribute("data-id", action.name);
						b.setAttribute("data-target",action.target_action);
						b.setAttribute("data-pid",value);
						b.innerHTML = action.name;
						if(VKStats.isCompleted({name:action.name,pid:value}).response){
							if(action.target_action==0){
								b.setAttribute("class", "green");
							}else{
								b.setAttribute("disabled", "disabled");
							}
						}
						b.onclick = function(){doClick(this)};
						step2_items.appendChild(b);
					}else{
						b = document.createElement("a");
						b.setAttribute("data-id", action.name);
						b.setAttribute("data-target",action.target_action);
						b.setAttribute("data-pid",value);
						b.innerHTML = action.name;
						b.href = action.value;
						if(VKStats.isCompleted({name:action.name,pid:value}).response){
							if(action.target_action==0){
								b.setAttribute("class", "green");
							}else{
								b.href = "javascript:alert('Action is already done.')";
							}
						}
						b.onclick = function(){doClick_link(this)};
						step2_items.appendChild(b);
					}
				}
			}else{
				step2_items.innerHTML="Нет доступных элементов";
			}
		}else{
			document.getElementById("step1_error").style.display="block";
		}
		document.getElementById("step1").style.display="none";
	}

	function loaded(){
		projects=VKStats.getProjects();
		step1=document.getElementById("step1_box");
		for(i in projects.response){
			option = document.createElement("option");
			option.setAttribute("value", projects.response[i].id);
			option.innerHTML = projects.response[i].title;
			step1.appendChild(option);
		}
		//VKStats.trackAction({action:'PAGE_LOADED',pid:1});
	}
</script>
</body>
</html>
