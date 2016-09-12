var eventsManager = {
	on: function(event, callback, element) {
		element.addEventListener(event, callback, false);
	},
	no: function(event, callback, element) {
		element.removeEventListener(event, callback);
	},
	apply: function(element) {
		element.on = function(event, callback) {window.eventsManager.on(event, callback, this);};
		element.no = function(event, callback) {window.eventsManager.no(event, callback, this);};
	},
	init: function() {
		this.apply(window);
		this.apply(Node.prototype);
	}
};

eventsManager.init();
