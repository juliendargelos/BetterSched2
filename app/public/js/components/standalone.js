var standalone = {
	get support() {
		return 'standalone' in window.navigator;
	},
	get is() {
		return this.support ? window.navigator.standalone == true : false;
	},
	init: function() {
		if(this.is) {
			document.body.setAttribute('data-standalone', true);
		}
	}
};

standalone.init();
