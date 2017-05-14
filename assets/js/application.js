//Create a root in the scope chain
var app = app || {};

app.do_request_old = function() {
	var page_num = $('#page_num').val();
	var xhr = new XMLHttpRequest();
	xhr.open("get", "http://localhost/lib/get.php?page_num=".concat(page_num));
	xhr.send();
}

app.do_request = function() {
	var page_num = $('#page_num').val();
	var url = "http://localhost/lib/get.php?page_num=".concat(page_num);
	$.ajax(url, {
		success: function(page){
			$doc = jQuery.parseHTML(page);
		} 
		error: function(){
			console.log("Could not get " + url.);
		}
	})
}


//Doing this way you polute the global scope
// do_request = function(page){
// 	var xhr = new XmlHttpRequest();
// 	xhr.open("get", "./get.php?page={$page}");
// 	xrh.send;
// }