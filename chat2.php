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
<script src="chat.js"></script>
</body>
</html>