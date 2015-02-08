$(window).ready(function() {
	var loadingDiv = $("#loading");
	var ackPar = $("<p id='ack'>Please confirm order.</p>");
	$("#review-submit").click(function(e){
		if($("#agree_terms").is(":checked")) {
			if(navigator.userAgent.match(/(iPod|iPhone|iPad)/)){
				loadingDiv.css("background-image", "none");
				$('body').append('<p id="ios-loading">Processing order...</p>');
				$("#ios-loading").css('left', (($(document).width() - $('#ios-loading').width()) / 2).toString() + 'px');
			}
			loadingDiv.css({
				"height": $(document).height(),
				"z-index": "100",
				"opacity": "0.5"
			});
		}else {
			$("#review_form").append(ackPar);
			e.preventDefault();
			return false;
		}
	});
});