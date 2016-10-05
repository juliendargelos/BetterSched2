var result = {
	element: null,
	p: null,
	stack: [],
	last: 0,
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
	get delta() {
		return (new Date).getTime() - this.last - this.duration;
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
		if(this.stack.length > 0) {
			var message = this.stack[0];
			this.stack = this.stack.slice(1);
			this.set(message);
		}
	},
	set: function(message) {
		var self = this;

		var delta = this.delta;
		if(delta > 0) {
			this.last = (new Date).getTime();
			this.message = message;
			this.element.className = '';
			document.body.appendChild(this.element);
			this.timeout = setTimeout(function() {
				self.out();
			}, this.duration);
		}
		else this.stack.push(message);
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
