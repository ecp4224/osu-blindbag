function askForUsername() {
	$("#usernameAsk").reveal({
		animation: 'fade',
		animationspeed: 450,
		closeonbackgroundclick: false,
	});
}

$(document).ready(function() {
	$("#username_button").click(function() {
		$("#usernameAsk").trigger('reveal:close');
	});
	
	$("#github_div").fadeTo(1, 0.3);
	$("#github_div").hover(
		function() {
			$(this).fadeTo(320, 1);
		},
		function() {
			$(this).fadeTo(320, 0.3);
		}
	);

	$("#wheel_link").click(function() {
		alert("This feature is not done yet.");
	});

	if (typeof(Storage)!=="undefined") {
		if (localStorage.getItem("user") !== null) {
			var username = localStorage.getItem("user");
			$.get("http://hypereddie.com/osubb/bag.php?action=canOpen&user=" + username, function(data) {
				if (data == "false") {
					askForUsername();
				}
			});
		} else {
			askForUsername();
		}
	} else {
		askForUsername();
	}
	
});