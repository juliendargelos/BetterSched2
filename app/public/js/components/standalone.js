var standalone = {
	get is() {
		return window.navigator.standalone == true;
	},
	init: function() {
		if(this.is) document.body.setAttribute('data-standalone', true);
	}
};

standalone.init();
