var toggler = {
	toggleClass: function(className, element) {
		var reg = new RegExp('\b'+className+'\b');
		if(element.className.match(reg)) element.className = element.className.replace(reg, '');
		else element.className += ' '+className;

		element.className = element.className.replace(/\s+/, ' ');
		while(element.className[0] == ' ') element.className = element.className.substr(1);
		while(element.className[element.className.length - 1] == ' ') element.className = element.className.substr(0, -1);
	},
	apply: function(element) {
		element.toggleClass = function(className) {window.toggler.toggleClass(className, this);};
	},
	init: function() {
		this.apply(Node.prototype);
	}
};

toggler.init();
