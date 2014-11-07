
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