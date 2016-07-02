<!DOCTYPE html>
<html>
<head>
<style>
div#box1 {
	background: #9DCEFF;
	width: 400px;
	height: 200px;
	overflow: hidden;
}
</style>
<script>
function slideOpen(el){
	var elem = document.getElementById(el);
	elem.style.transition = "height 0.2s linear 0s";
	elem.style.height = "200px";
}
function slideClosed(el){
	var elem = document.getElementById(el);
	elem.style.transition = "height 0.2s linear 0s";
	elem.style.height = "0px";
}
</script>
</head>
<body>
<button onclick="slideClosed('box1');">slideClosed</button>
<button onclick="slideOpen('box1');">slideOpen</button>
<div id="box1">Content in box 1 ...</div>
</body>
</html>