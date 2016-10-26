var local = {
	lifetime: 86400000,
	get available() {
		return typeof localStorage === 'object' && localStorage !== null;
	},
	serialize: function(data, lifetime, expiration) {
		return JSON.stringify({
			value: data,
			timestamp: (new Date).getTime(),
			lifetime: typeof lifetime == 'number' ? lifetime : this.lifetime,
			expiration: typeof expiration == 'number' ? expiration : null
		});
	},
	unserialize: function(data) {
		var unserialized = null;

		if(typeof data == 'string') {
			try {
				unserialized = JSON.parse(data);
			}
			catch(e) {}
		}

		return unserialized;
	},
	set: function(key, value, lifetime) {
		return localStorage.setItem(key, this.serialize(value, lifetime));
	},
	get: function(key) {
		var data = this.data(key);
		return this.update(data)
	},
	data: function(key) {
		return this.unserialize(localStorage.getItem(key));
	},
	update: function(data) {
		if(data === null) return null;

		if(this.expired(data)) {
			this.remove(key);
			return null;
		}
		else return data.value;
	},
	remove: function(key) {
		return localStorage.removeItem(key);
	},
	expired: function(data) {
		if(typeof data != 'object' || data === null) return true;

		if(data.expiration === null) return (new Date).getTime() - data.timestamp >= data.lifetime;
		else return (new Date).getTime() >= data.expiration;
	},
	clean: function() {
		var length = localStorage.length;
		for(var i = 0; i < length; i++) {
			var key = localStorage.key(i);
			var data = this.data(key);

			if(this.expired(data)) this.remove(key);
		}
	},
	init: function() {
		if(!this.available) {
			window.localStorage = {
				getItem: function() {},
				setitem: function() {},
				length: 0
			};
		}

		this.clean();
	}
};

local.init();
