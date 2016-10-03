var local = {
	lifetime: 600000,
	available: function() {
		return typeof localStorage === 'object' && localStorage !== null;
	},
	serialize: function(data) {
		return JSON.stringify({
			value: data,
			timestamp: (new Date).getTime()
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
	set: function(key, value) {
		return localStorage.setItem(key, this.serialize(value));
	},
	get: function(key) {
		var data = this.unserialize(localStorage.getItem(key));
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
		return (new Date).getTime() - data.timestamp >= this.lifetime;
	},
	init: function() {
		if(!this.available()) {
			window.localStorage = {
				getItem: function() {},
				setitem: function() {}
			};
		}
	}
};

local.init();
