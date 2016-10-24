// @import components/eventsManager
// @import components/standalone
// @import components/links
// @import components/result
// @import components/Spinner
// @import components/Request

var pageQuote = {
	element: document.getElementsByTagName('form')[0],
	request: new Request(''),
	spinner: new Spinner,
	inputs: {},
	set sending(v) {
		this.element.className = v ? 'sending' : '';

		if(v) this.spinner.show();
		else this.spinner.hide();

		for(var name in this.inputs) this.inputs[name].blur();
	},
	clear: function() {
		for(var name in this.inputs) this.inputs[name].value = '';
	},
	send: function() {
		var self = this;
		this.sending = true;

		this.request.success(function(response) {
			self.sending = false;
			result.set(response.message);
			if(response.status) self.clear();
			this.sending = false;
		}).error(function(response) {
			self.sending = false;

			if(response) result.set(response.message);
			else result.set('Erreur du serveur');
			this.sending = false;
		}).send({
			quote: true,
			author: this.inputs.author.value,
			email: this.inputs.email.value,
			content: this.inputs.content.value
		});
	},
	onsubmit: function(event) {
		this.send();
		event.preventDefault();
	},
	init: function() {
		var self = this;

		this.request.json = true;
		this.request.method = 'post';

		var inputs = [
			this.element.getElementsByTagName('input'),
			this.element.getElementsByTagName('textarea')
		];

		for(var i = 0; i < inputs.length; i++) {
			var is = inputs[i];
			for(var j = 0; j < is.length; j++) {
				var input = is[j];
				if(input.type != 'submit') this.inputs[input.name] = input;
			}
		}

		this.element.on('submit', function(event) {
			self.onsubmit(event);
		});
	}
};

pageQuote.init();
