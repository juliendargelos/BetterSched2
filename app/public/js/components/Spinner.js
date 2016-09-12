var Spinner = function(parent) {
	var element = document.createElement('div');
	element.className = 'spinner init';
	var center = true;

	Object.defineProperties(this, {
		element: {
			get: function() {return element;}
		},
		center: {
			get: function() {return center;},
			set: function(v) {
				v = v ? true : false;
				if(v != center) {
					center = v;
					this.element.className += ' center';
				}
			}
		},
		hidden: {
			get: function() {
				return this.element.className.match(/\bhidden\b/);
			}
		}
	});

	this.hide = function() {
		var self = this;

		this.element.className = 'spinner'+(this.center ? ' center' : '')+' hidden';
		setTimeout(function() {
			self.remove();
		}, Spinner.duration);
	};

	this.show = function() {
		this.element.className = 'spinner'+(this.center ? ' center' : '');

		this.append();
	};

	this.append = function(parent) {
		if(!(parent instanceof Node)) parent = document.body;
		parent.appendChild(this.element);
	};

	this.remove = function() {
		if(this.element.parentNode) this.element.parentNode.removeChild(this.element);
	};
};

Spinner.transition = 200;
