var VKStats = {
	gate_url: "http://stats.local/gate.php",
	
	init: function (settings){
		this.settings=settings;
	},

	getProjects: function(){
		return this.query({"act":"getProjects"});
	},

	isAllowed: function(obj){
		//if(!obj.pid){obj.pid=this.settings.project_id;}
		return this.query({"act":"isAllowed", "project_id": obj.pid, "cookie": obj.uid});
	},

	isCompleted: function(obj){
		
		return this.query({"act":"isCompleted","name":obj.name,"project_id":obj.pid});
	},

	getActions: function(obj){
		
		return this.query({"act":"getActions","project_id": obj.pid});
	},

	trackAction: function(obj){
		
		return this.query({"act":"trackAction","name":obj.action,"project_id":obj.pid});
	},
	
	query: function (data, callback){
		if (window.XMLHttpRequest) {
			xmlhttp=new XMLHttpRequest();
		} else {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		async=false;
		if(typeof callback == 'function'){
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					callback(JSON.parse(xmlhttp.responseText));
				}
			}
			async=true;
		}
		data["cookie"]=this.cookie("_vk_id");
		if(!data["cookie"]){
			data["cookie"]=this.make_session();
		}
		if(!data["project_id"] || data["project_id"]==undefined){ data["project_id"]=this.settings.project_id; }
		xmlhttp.open("GET",this.gate_url+"?"+this.make_query(data),async);
		xmlhttp.send(null);
		if(!async){return JSON.parse(xmlhttp.responseText);}
	},
	
	make_query: function (input, temp) {
		var output = [];
		Object.keys(input).forEach(function (v){
			k=v;
			var k = encodeURIComponent(k.replace(/[!'()*]/g, escape));
			temp? k = temp + '[' + k + ']' : ''
			if (typeof input[v] === 'object') {
				var query = this.make_query(input[v], k)
				output.push(query)
			}else {
				var value = encodeURIComponent((input[v]+"").replace(/[!'()*]/g, escape));
				output.push(k + '=' + value);
			}
		});
		return output.join('&');
	},

	cookie: function (k){return(document.cookie.match('(^|; )'+k+'=([^;]*)')||0)[2]},
	make_id: function(){
	    var text = "";
	    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	    for( var i=0; i < 9; i++ )
	        text += possible.charAt(Math.floor(Math.random() * possible.length));

	    return text;
	},

	make_session: function(){
		sid=this.make_id();
		var date = new Date(); date.setTime(date.getTime() * 2);
		expires = "; expires=" + date.toGMTString();
		document.cookie = "_vk_id=" + sid + expires + "; path=/";
		return sid;
	}
}