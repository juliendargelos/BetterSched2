var result = {
	element: null,
	p: null,
	duration: 4000,
	transition: 250,
	timeout: null,
	get message() {
		return this.p.innerText;
	},
	set message(v) {
		this.p.innerHTML = '';
		this.p.appendChild(document.createTextNode(v));
	},
	onclick: function() {
		var self = this;
		this.element.className = 'out';
		clearTimeout(this.timeout);
		this.timeout = setTimeout(function() {
			self.out();
		}, this.transition);
	},
	out: function() {
		if(this.element.parentNode) this.element.parentNode.removeChild(this.element);
	},
	set: function(message) {
		var self = this;

		if(this.element.parentNode) {

			this.element.className = 'out';
			clearTimeout(this.timeout);
			this.timeout = setTimeout(function() {
				self.out();
				self.set(message);
			}, this.transition);
		}
		else {
			this.message = message;
			this.element.className = '';
			document.body.appendChild(this.element);
			this.timeout = setTimeout(function() {
				self.out();
			}, this.duration);
		}
	},
	init: function() {
		var self = this;

		this.element = document.createElement('div');
		this.p = document.createElement('p');

		var wrapper = document.createElement('div');
		wrapper.className = 'wrapper';

		this.element.id = 'result';
		this.element.appendChild(wrapper);

		this.element.on('click', function() {
			self.onclick();
		});

		this.element.on('touchstart', function() {
			self.onclick();
		});

		wrapper.appendChild(this.p);
	}
};

result.init();
