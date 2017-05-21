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
		if(!download || download.typeof === 'undefined' ){;
			download = $("a[title='MP3 Download']",episode_tags).attr('href');
			if (!download || download.typeof === 'undefined' ){
				download = $('div.aplayer-panel', episode_tags).attr('data-uri');
			}
		} 

		item = {
			"title": title,
			"date": date,
			"setlist": setlist,
			"download": download,  
		};
		data.push(item);
	}
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

app.post_data = function(data){
		$.ajax('http://localhost/lib/post.php',{
			type: 'post',
			dataType: 'json',
			data: {
				//"page_num": page_num,
				"page_num": page_num,
				"data": data,
			},
			success: function(){
				console.log('JSON POSTed to php!');
			},
			error: function(ts){
				console.log(ts.responseText);
			},
		});
}