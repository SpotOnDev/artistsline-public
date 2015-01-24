function init() {
	var thumb = document.getElementById('add-photos');
	var thumb_children = thumb.childNodes;
	var main_img = document.getElementById('img');
	for(i=0;i<thumb_children.length;i++){
		if(thumb_children[i].tagName == "A"){
			thumb_children[i].onclick = function(){
				var src = this.childNodes[0].getAttribute('src');
				var thumb_ext = src.indexOf('_thumb');
				src = src.substr(0, thumb_ext);
				src = src + '.jpg';
				main_img.setAttribute('src', src);
				return false;
			};
		}
	}
}

window.onload = init;