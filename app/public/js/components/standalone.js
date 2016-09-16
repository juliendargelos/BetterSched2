var standalone = {
	get support() {
		return 'standalone' in window.navigator;
	},
	get is() {
		return this.support ? window.navigator.standalone == true : false;
	},
	links: {
		elements: document.getElementsByTagName('a'),
		stop: /^(a|html)$/i,
		relative: /^[a-z\+\.\-]+:/i,
		url: document.location.protocol+'//'+document.location.host,
		onclick: function(element) {
			var self = this;

			return function(event) {
				event.preventDefault();
				var href = element.href;
				if(href.replace(document.location.href, '').indexOf('#')) {
					if(href.match(self.relative) || href.indexOf(self.url)) {
						event.preventDefault();
						document.location.href = element.href;
					}
				}
			};
		},
		init: function() {
			for(var i = 0; i < this.elements.length; i++) {
				var element = this.elements[i];
				element.on('click', this.onclick(element));
				element.on('touchstart', this.onclick(element));
			}
		}
	},
	init: function() {
		if(this.is || true) {
			document.body.setAttribute('data-standalone', true);
			this.links.init();
		}
	}
};

standalone.init();
