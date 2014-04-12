function askForUsername() {
	$('#usernameAsk').modal('show');
}

function shrekThem() {
	clicked = true; //Prevent link click if they somehow get out
	$("#comebackLater").modal('show');
}

var username;
var spunMap;
var clicked = false;

function spin(data, count, total) {
	if (count <= 0) {
		spunMap = data["maps"][Math.floor(Math.random() * (data["maps"].length - 1))];
		$("#lwheel").text(spunMap.source + " (" + spunMap.artist + ") - " + spunMap.title);
		$("#download").fadeIn(444);
		$("#download").click(function() {
			window.location.href = "dlredirect.html#" + spunMap.beatmapset_id;
		});
		$("#preview").fadeIn(444);
		var song = new Sound("http://b.ppy.sh/preview/" + spunMap.beatmapset_id + ".mp3", 45, false);
		$("#preview").click(function() {
			song.start();
		});
		return;
	}
	
	count--;
	var time = ((count / total) * 2) - (((count / total) - 1) * 600);
	var map = data["maps"][Math.floor(Math.random() * (data["maps"].length - 1))];
	$("#lwheel").text(map.source + " (" + map.artist + ") - " + map.title);
	setTimeout(function() {
		spin(data, count, total);
	}, time);
}

$(document).ready(function() {
	$("#username_button").click(function() {
		if ($("#username").val() == "") return;
		username = $("#username").val();
		if (typeof(Storage)!=="undefined") {
			localStorage.setItem("user", username); 
		}
		
		$.get("http://hypereddie.com/osubb/bag.php?action=canOpen&user=" + username, function(data) {
			if (data == "false") {
				shrekThem();
			}
		});
		
		$("#usernameAsk").trigger('reveal:close');
	});
	
	$("#github_div").fadeTo(1, 0.3);
	$("#github_div").hover(
		function() {
			$(this).fadeTo(333, 1);
		},
		function() {
			$(this).fadeTo(333, 0.3);
		}
	);

	$("#wheel_link").click(function() {
		if (clicked) return;
		$("#lwheel").text("Loading...");
		clicked = true;
		$.get("http://hypereddie.com/osubb/bag.php?action=open&user=" + username, function(data) {
			var list = JSON.parse(data);
			var num = list["selected"];
			var count = Math.random() * (80 - 35) + 35;
			setTimeout(function() {
				spin(list, count, count);
			}, 2);
		});
	});

	if (typeof(Storage)!=="undefined") {
		if (localStorage.getItem("user") !== null) {
			username = localStorage.getItem("user");
			$.get("http://hypereddie.com/osubb/bag.php?action=canOpen&user=" + username, function(data) {
				if (data == "false") {
					shrekThem();
				}
			});
		} else {
			askForUsername();
		}
	} else {
		askForUsername();
	}
	
});

function Sound(source,volume,loop)
{
    this.source=source;
    this.volume=volume;
    this.loop=loop;
    var son;
    this.son=son;
    this.finish=false;
    this.stop=function()
    {
        document.body.removeChild(this.son);
    }
    this.start=function()
    {
        if(this.finish)return false;
        this.son=document.createElement("embed");
        this.son.setAttribute("src",this.source);
        this.son.setAttribute("hidden","true");
        this.son.setAttribute("volume",this.volume);
        this.son.setAttribute("autostart","true");
        this.son.setAttribute("loop",this.loop);
		this.son.setAttribute("style", "display: block"); //Stupid bootstrap..
        document.body.appendChild(this.son);
    }
    this.remove=function()
    {
        document.body.removeChild(this.son);
        this.finish=true;
    }
    this.init=function(volume,loop)
    {
        this.finish=false;
        this.volume=volume;
        this.loop=loop;
    }
}