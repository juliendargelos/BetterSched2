var standalone = {
	get support() {
		return 'standalone' in window.navigator;
	},
	get is() {
		return this.support ? window.navigator.standalone == true : false;
	},
	links: {
		stop: /^(a|html)$/i,
		relative: /^[a-z\+\.\-]+:/i,
		url: document.location.protocol+'//'+document.location.host,
		onclick: function(event) {
			var element = event.target;
			while(!element.nodeName.match(self.stop)) element = element.parentNode;

			if('href' in element) {
				var href = element.href;
				if(href.replace(document.location.href, '').indexOf('#')) {
					if(!href.match(this.relative) || href.indexOf(this.url) === false) {
						event.preventDefault();
						document.location.href = element.href;
					}
				}
			}
		},
		init: function() {
			var self = this;

			on('click', function(event) {
				self.onclick(event);
			});
		}
	},
	init: function() {
		if(this.is) {
			document.body.setAttribute('data-standalone', true);
			this.links.init();
		}
	}
};

standalone.init();
