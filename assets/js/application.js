//Create a root in the scope chain
var app = app || {};

app.do_request = function() {
	page_num = $('#page_num').val();
	url = "http://localhost/lib/get.php?page_num=".concat(page_num);
	$.ajax(url, {
		dataType: 'text',
		success: function(page){
			app.scrape(page);
		}, 
		error: function(){
			console.log("Could not get " + url);
		}
	})
}

app.scrape = function(page) {
	data = [];
	episodes = $('.post', page);
	for(i = 0; i < episodes.length; i++){
		episode_tags = episodes[i].children; 
		title = episode_tags[0].innerText;
		date = episode_tags[1].innerText;
		setlist = episode_tags[2].innerText;
	  download = $('a.download_link',episode_tags).attr('href');
		if(!download || download.typeof === 'undefined' ){
			// download = $('p:nth-child(3) > a',episode_tags).attr('href');
			download = $("a[title='MP3 Download']",episode_tags).attr('href');
		} 

		item = {
			"title": title,
			"date": date,
			"setlist": setlist,
			"download": download,  
		};
		data.push(item);
	}
	app.validates(data);
}

app.validates = function(data){
	data.forEach(function(item){
		if(!item.title || item.title.typeof === 'undefined' ){
			console.log('miss title ' + item.title);
		} 
		else if((!item.date || item.date.typeof === 'undefined' )){
			console.log('miss date ' + item.title);
		}
		else if((!item.download || item.download.typeof === 'undefined' )){
			console.log('miss download ' + item.title);
		}
		else if((!item.setlist || item.setlist.typeof === 'undefined' )){
			console.log('miss setlist ' + item.title);
		} else {
			console.log('OK!');
		}
		
	});
}	

//Doing this way you polute the global scope
// do_request = function(page){
// 	var xhr = new XmlHttpRequest();
// 	xhr.open("get", "./get.php?page={$page}");
// 	xrh.send;
// }