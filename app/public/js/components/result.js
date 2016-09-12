var result = {
	element: null,
	p: null,
	stack: [],
	last: 0,
	duration: 4000,
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
	set: function(message) {
		var self = this;

		var delta = this.delta;
		if(delta > 0) {
			this.last = (new Date).getTime();
			this.message = message;
			document.body.appendChild(this.element);
			setTimeout(function() {
				if(self.element.parentNode) self.element.parentNode.removeChild(self.element);
				if(self.stack.length > 0) {
					var message = self.stack[0];
					self.stack = self.stack.slice(1);
					self.set(message);
				}
			}, this.duration);
		}
		else this.stack.push(message)
	},
	init: function() {
		this.element = document.createElement('div');
		this.p = document.createElement('p');

		var wrapper = document.createElement('div');
		wrapper.className = 'wrapper';

		this.element.id = 'result';
		this.element.appendChild(wrapper);
		wrapper.appendChild(this.p);
	}
};

result.init();
