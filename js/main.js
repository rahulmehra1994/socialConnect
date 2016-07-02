function _(x){
	return document.getElementById(x);
}
//toggling function to change the profile photo
//you can toggle any element of your website by calling this function and passing the id of that element
function toggleElement(x){
	var x = _(x);
	if(x.style.display == 'block'){
		x.style.display = 'none';
	}else{
		x.style.display = 'block';
	}
}