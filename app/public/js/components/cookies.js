var cookies = {
	set: function(name, value, lifetime) {
		if(lifetime === undefined) lifetime = 315360000;

	    var date = new Date();
	    date.setTime(date.getTime()+lifetime);
	    document.cookie = name+'='+value+'; '+'expires='+date.toUTCString();
	},
	get: function(name) {
	    name += '=';

	    var cookies = document.cookie.split(';');
	    for(var i = 0; i < cookies.length; i++) {
	        var cookie = cookies[i];
	        while(cookie.charAt(0) == ' ') cookie = cookie.substring(1);
	        if(cookie.indexOf(name) == 0) return cookie.substring(name.length, cookie.length);
	    }
	    return '';
	}
};
