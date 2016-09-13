var header = {
	onscroll: function() {
		if(scroll.top <= 0 && document.body.className != 'scroll-top') document.body.className = 'scroll-top';
		else if(scroll.top > 0 && document.body.className == 'scroll-top') document.body.className = '';
	},
	init: function() {
		on('scroll', this.onscroll);
	}
};

header.init();
