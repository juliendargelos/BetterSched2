var Swipe = function(element) {
	var self = this;

	Object.defineProperties(this, {
		width: {
			get: function() {
				return this.element.offsetWidth;
			}
		},
		pageWidth: {
			get: function() {
				var firstElement = this.firstElement;
				return firstElement ? firstElement.offsetWidth : 0;
			}
		},
		elements: {
			get: function() {
				return this.element.childNodes;
			}
		},
		firstElement: {
			get: function() {
				var elements = this.elements;
				return elements.length > 0 ? elements[0] : false;
			}
		},
		position: {
			set: function(v) {
				var firstElement = this.firstElement;

				if(firstElement) {
					firstElement.style.marginLeft = v+'px';
				}
			},
			get: function() {
				var firstElement = this.firstElement;

				if(firstElement) {
					var position = parseInt(firstElement.style.marginLeft);
					return typeof position == 'number' && !isNaN(position) ? position : 0;
				}
				else return 0;
			}
		},
		page: {
			set: function(v) {
				var max = this.elements.length-1;

				v = Math.round(v);
				v = v < 0 ? 0 : (v > max ? max : v);

				this.position = -this.pageWidth*v;
			},
			get: function() {
				return this.position/this.pageWidth;
			}
		}
	});

	this.element = element;
	this.transition = 200;
	this.touchInit = 0;
	this.currentPage = 0;
	this.type = 'touch';
	this.starting = false;

	this.swipping = function(event) {
		self.move(event);
	};

	this.start = function(event) {
		this.touchInit = {
			x: this.type == 'touch' ? event.pageX : event.clientX,
			y: this.type == 'touch' ? event.pageY : event.clientY
		};

		this.currentPosition = this.position;
		this.starting = true;

		on(this.type == 'touch' ? 'touchmove' : 'mousemove', this.swipping);
	};

	this.move = function(event) {
		if(this.starting && this.type == 'touch' && Math.abs(event.pageY-this.touchInit.y) > Math.abs(event.pageX-this.touchInit.x)) this.end();
		else {
			this.position = this.currentPosition+((this.type == 'touch' ? event.pageX : event.clientX)-this.touchInit.x);
			this.starting = false;

			event.preventDefault();
		}
	};

	this.end = function() {
		var self = this;

		no(this.type == 'touch' ? 'touchmove' : 'mousemove', this.swipping);

		var page = -this.position/this.pageWidth;
		this.page = page;

		if(page != Math.round(page)) {
			if(!this.element.className.match(/\btransition\b/)) {
				this.element.className += ' transition';
				setTimeout(function() {
					self.element.className = self.element.className.replace(/\btransition\b/, '');
				}, this.transition);
			}
		}
	};

	this.init = function() {
		if(this.element) {

			this.element.on('touchstart', function(event) {
				self.type = 'touch';
				self.start(event);
			});

			this.element.on('mousedown', function(event) {
				self.type = 'mouse';
				self.start(event);
			});

			on('touchend', function() {
				if(self.type == 'touch') self.end();
			});

			on('touchcancel', function() {
				if(self.type == 'touch') self.end();
			});

			on('mouseup', function() {
				if(self.type == 'mouse') self.end();
			});
		}
	};

	this.init();
};
