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
				return firstElement ? firstElement.offsetWidth : 1;
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
					var currentPosition = this.currentPage*this.pageWidth;
					var currentDelta = v - currentPosition;

					if(currentDelta > this.pageWidth) v = currentPosition+this.pageWidth;
					else if(currentDelta < -this.pageWidth) v = currentPosition-this.pageWidth;

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
		delta: {
			get: function() {
				return this.position - this.lastPosition;
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
				return Math.round(this.position/this.pageWidth);
			}
		},
		speed: {
			get: function() {
				return this.delta/this.pageWidth*this.speedFactor;
			}
		},
		delta: {
			get: function() {
				return this.position - this.lastPosition;
			}
		},
		currentPage: {
			get: function() {
				return this.currentPosition/this.pageWidth;
			}
		}
	});

	this.element = element;
	this.transition = 200;
	this.speedFactor = 20000;
	this.touchInit = 0;
	this.currentPage = 0;
	this.type = 'touch';
	this.starting = false;
	this.lastPosition = 0;
	this.disabled = false;
	this.scrolling = false;

	this.coordinates = function(event) {
		if(this.type == 'touch') {
			if(event.pageX !== undefined && event.pageY !== undefined) {
				return {
					x: event.pageX,
					y: event.pageY
				};
			}
			else if(event.touches !== undefined && event.touches.length > 0) {
				return {
					x: event.touches[0].pageX,
					y: event.touches[0].pageY
				};
			}
			else if(event.changedTouches !== undefined && event.changedTouches.length > 0) {
				return {
					x: event.changedTouches[0].pageX,
					y: event.changedTouches[0].pageY
				};
			}
			else {
				return {
					x: 0,
					y: 0
				};
			}
		}
		else {
			return {
				x: event.pageX,
				y: event.pageY
			};
		}
	};

	this.swipping = function(event) {
		self.move(event);
	};

	this.start = function(event) {
		if(!this.disabled) {
			this.scrolling = false;
			this.touchInit = this.coordinates(event);

			this.currentPosition = this.lastPosition = this.position;
			this.starting = true;

			on(this.type == 'touch' ? 'touchmove' : 'mousemove', this.swipping);

			this.enableEndListener();
		}
	};

	this.move = function(event) {
		if(this.scrolling) return;

		var coordinates = this.coordinates(event);

		if(this.starting && this.type == 'touch' && Math.abs(coordinates.y - this.touchInit.y) > Math.abs(coordinates.x - this.touchInit.x)) {
			this.scrolling = true;
			this.end(event, true);
		}
		else {
			this.lastPosition = this.position;
			this.position = this.currentPosition + (coordinates.x - this.touchInit.x);
			this.starting = false;
			event.preventDefault();
		}
	};

	this.end = function(event, move) {
		if(!this.disabled) {
			var self = this;

			if(move !== true) {
				this.move(event);

				var page = -(this.position+this.speed)/this.pageWidth;
				this.page = page;

				if(page != Math.round(page)) {
					if(!this.element.className.match(/\btransition\b/)) {
						this.element.className += ' transition';
						setTimeout(function() {
							self.element.className = self.element.className.replace(/\btransition\b/, '');
						}, this.transition);
					}
				}
			}

			no(this.type == 'touch' ? 'touchmove' : 'mousemove', this.swipping);

			this.disableEndListener();
		}
	};

	this.onresize = function() {
		this.page = -this.position/this.pageWidth;
	};

	this.endListener = function(event) {
		self.end(event);
	};

	this.enableEndListener = function() {
		var self = this;

		on('touchend', self.endListener);
		on('touchcancel', self.endListener);
		on('mouseup', self.endListener);
	};

	this.disableEndListener = function() {
		var self = this;

		no('touchend', self.endListener);
		no('touchcancel', self.endListener);
		no('mouseup', self.endListener);
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

			on('resize', function() {
				if(!self.disabled) self.onresize();
			});
		}
	};

	this.init();
};
