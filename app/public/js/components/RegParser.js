var RegParser = function(string, delimiter) {
	this.delimiter = typeof delimiter == 'string' ? delimiter : '/';
	this.string = typeof string == 'string' ? string : '';

	Object.defineProperties(this, {
		parsePattern: {
			get: function() {
				var d = this.delimiter;
				return new RegExp('^'+d+'(.*)'+d+'([a-z]*)$', 'i');
			}
		},
		pattern: {
			get: function() {
				return this.string.replace(this.parsePattern, '$1');
			}
		},
		flags: {
			get: function() {
				return this.string.replace(this.parsePattern, '$2');
			}
		},
		compiled: {
			get: function() {
				return new RegExp(this.pattern, this.flags);
			}
		}
	});
};

RegParser.compile = function(string, delimiter) {
	return (new RegParser(string, delimiter)).compiled;
};
