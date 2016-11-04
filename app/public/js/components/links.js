var links = {
	elements: document.getElementsByTagName('a'),
	stop: /^(a|html)$/i,
	relative: /^[a-z\+\.\-]+:/i,
	transition: 300,
	url: document.location.protocol+'//'+document.location.host,
	set loading(v) {
		if(v) document.body.setAttribute('data-loadpage', true);
		else document.body.removeAttribute('data-loadpage');
	},
	onclick: function(element) {
		var self = this;

		return function(event) {
			event.preventDefault();
			var href = element.href;
			if(href.replace(document.location.href, '').indexOf('#')) {
				if(href.match(self.relative) || href.indexOf(self.url)) {
					event.preventDefault();
					self.loading = true;
					setTimeout(function() {
						document.location.href = element.href;
					}, self.transition);
				}
			}
		};
	},
	init: function() {
		for(var i = 0; i < this.elements.length; i++) {
			var element = this.elements[i];
			if(document.getAttribute('data-external') === null) {
				element.on('click', this.onclick(element));
				element.on('touchstart', this.onclick(element));
			}
		}
	}
};

links.init();
