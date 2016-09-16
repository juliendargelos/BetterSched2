// @import components/eventsManager
// @import components/Request
// @import components/result
// @import components/Spinner
// @import components/standalone

var pageLogin = {
	form: {
		element: document.getElementById('login'),
		spinner: new Spinner,
		request: new Request('/api/login'),
		get data() {
			return {
				institute: document.getElementById('login-institute').value,
				username: document.getElementById('login-username').value,
				password: document.getElementById('login-password').value,
			};
		},
		set logging(v) {
			this.element.className = v ? 'logging' : '';

			if(v) this.spinner.show();
			else this.spinner.hide();

			document.getElementById('login-institute').blur();
			document.getElementById('login-username').blur();
			document.getElementById('login-password').blur();
		},
		clear: function() {
			document.getElementById('login-username').value = '';
			document.getElementById('login-password').value = '';
			document.getElementById('login-username').focus();
		},
		success: function(response) {
			if(response.status) window.location.reload();
			else {
				pageLogin.form.logging = false;
				pageLogin.form.clear();
				result.set(response.message);
			}
		},
		error: function(response) {
			result.set('Erreur réseau');
		},
		send: function() {
			this.logging = true;
			this.request.success(this.success).error(this.error).send(this.data);
		},
		submit: {
			element: document.getElementById('login-submit'),
			init: function() {
				var self = this;

				this.element.on('touchstart', function() {
					self.element.className = 'touch';
				});

				this.element.on('touchend', function() {
					self.element.className = '';
				});
			}
		},
		init: function() {
			var self = this;

			this.request.method = 'post';
			this.request.json = true;

			this.element.on('submit', function(event) {
				event.preventDefault();
				self.send();
				event.preventDefault();
			});

			this.submit.init();
		}
	},
	init: function() {
		this.form.init();
	}
};

pageLogin.init();
