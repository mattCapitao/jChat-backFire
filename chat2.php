<?php $c = $_REQUEST['c'] ?: 'general'?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>ChatApp</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
<style>
#app {
	padding-top: 20px;
	overflow: hidden;
	height: 100%;
	width: 100%;

}
#workspace {
	min-height: 100%;
	height: 100%;
	width: 100%;
	position: relative;
	top: 0px;
	left: 0;
	display: inline-block;
}
#msgwrap {
	height: 100%;
	overflow-y: auto;
	padding: 20px 0 0px 0;
}
#msgwin {
	position: relative;
	height: 100%;
	overflow-y: auto;
	padding: 20px 15px 60px 15px;
}
.input {
	background-color: #ffffff;
	bottom: 0;
	position: fixed;
	padding: 10px;
}
</style>
</head>
<body> 

  <div id="app">
  
    <div id="header" class="navbar navbar-default navbar-fixed-top">
      <div class="col-xs-12 col-md-12">
        <div id="controls">Menu</div>
      </div>
    </div>
    
    <div id="workspace">
      <div id="msgwrap" class="col-xs-12 col-md-12">
        <div id="msgwin"></div>
      </div>
    </div>
    
    <div class="col-xs-12 col-md-12 input">
      <div id="input">
        <textarea rows="2" placeholder="Enter your message here, then hit Return to send." id="newMessage" class="form-control"></textarea>
      </div>
    </div>
    
  </div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> 
<script src="/js/underscore.js"></script> 
<script src="http://backbonejs.org/backbone-min.js"></script> 
<script src="https://cdn.firebase.com/js/client/1.0.21/firebase.js"></script> 
<script src="https://cdn.firebase.com/libs/backfire/0.3.0/backfire.min.js"></script> 
<script>
var Msg = Backbone.Model.extend({
	defaults: function(){ 
		return {'user' : 'anon', 'message' : 'New Message!'}
	}
}),

MsgL = Backbone.Firebase.Collection.extend({
	model: Msg,
	firebase: new Firebase("https://boiling-heat-4811.firebaseio.com/<?php echo $c; ?>/messages")
}),

MsgV = Backbone.View.extend({
  tagName: 'p',
  template: _.template('<span class="<%= id %>"><strong><%= user %>:</strong> </span><span class="<%= id %>"><%= message %></span>'),
  initialize: function(){
	  this.model.on('change', this.render, this);
  },  
  render: function(){
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
  }
}),

MsgLV = Backbone.View.extend({
  el: $("#msgwin"),
  initialize: function(){
	  this.collection.on('add', this.addOne, this);
	  this.collection.on('reset', this.render, this);
  },
  render: function(){
	  this.collection.forEach(this.addOne, this);
  },
  addOne: function(model){
	  var msgV = new MsgV({model: model});
	  msgV.render();
	  this.$el.append(msgV.el); 
	  $("#msgwin").scrollTop($("#msgwin")[0].scrollHeight);
  }
}),

AppRouter = new (Backbone.Router.extend({
  routes: { "messages/:id": "show", "": "index" },
  initialize: function(options){
	  this.msgL = new MsgL();
  },
  index: function(){
	var msgsV = new MsgLV({collection: this.msgL});
	msgsV.render();
	$('#msgwin').html(msgsV.el);
  },
  show: function(id){
	var msg = new Msg({id: id})
	msgV = new MsgV({model: msg});
	msgV.render();
	$('#msgwin').html(msgV.el);
	msg.fetch();
  },
  start: function(){
	  Backbone.history.start({pushState: true});
  }
}));

$(function(){
	AppRouter.start();
	
	var setUser=function(){
		try{
			localStorage.setItem('test', '1');
			if(typeof localStorage.getItem('userName')==='string' && localStorage.getItem('userName').length > 0){
				user = localStorage.getItem('userName'); 
			}else{
				user=prompt('Please enter a user name.');
				localStorage.setItem('userName',user);
			}
		}catch(error){
			var erMsg='This may be due to private browsing mode. If you wish to cache your username for future logins please diable private browsing.';
			user=prompt('Please enter a user name.');
			alert('Your username could not be saved for future logins:'  + "\n\n" +erMsg );
		}
	},
	
	sendAdd=function(){
		AppRouter.msgL.add({
			user: user,
			message: $('#newMessage').val()			
		});
		$('#newMessage').val('');
	};
	
	setUser();

	$('#app').on('blur', '#newMessage',  function(e){
		var m=$('#newMessage').val();
		if(typeof m==='string' && m.length > 0){
			sendAdd();
		}
	});

	$('#app').on('keypress', '#newMessage', function(e){
		if(e.which === 13){
			sendAdd();
			return false;
		}
	});
});
</script>
</body>
</html>