var Request = function(url, data, method, json) {
	var self = this;

	this.url = url;
	method = typeof method == 'string' ? method.toUpperCase() : 'GET';
	this.data = typeof data == 'object' ? data : {};
	this.method = method != 'GET' && method != 'POST' ? 'GET' : method;
	this.xhr = new XMLHttpRequest();
	this.json = json ? true : false;

	this.onNotInitialized;
	this.onConnectionEstablished;
	this.onRecieved;
	this.onProcessing;
	this.onFinished;

	this.onSuccess;
	this.onError;

	this.notInitialized = function(c){this.onNotInitialized = c;return this;};
	this.connectionEstablished = function(c){this.onConnectionEstablished = c;return this;};
	this.recieved = function(c){this.onRecieved = c;return this;};
	this.processing = function(c){this.onProcessing = c;return this;};
	this.finished = function(c){this.onFinished = c;return this;};

	this.success = function(c){this.onSuccess = c;return this;};
	this.error = function(c){this.onError = c;return this;};

	Object.defineProperties(this, {
		state: {get: function(){return this.xhr.readyState;}},
		status: {get: function(){return this.xhr.status;}},
		response: {get: function(){return this.xhr.responseText;}},
		params: {get: function(){return Request.encodeData(this.data);}}
	});

	this.xhr.onreadystatechange = function() {
		switch(self.state) {
			case Request.notInitialized:
				if(typeof self.onNotInitialized == 'function') self.onNotInitialized();
				break;
			case Request.connectionEstablished:
				if(typeof self.onConnectionEstablished == 'function') self.onConnectionEstablished();
				break;
			case Request.recieved:
				if(typeof self.onRecieved == 'function') self.onRecieved();
				break;
			case Request.processing:
				if(typeof self.onProcessing == 'function') self.onProcessing();
				break;
			case Request.finished:
				var response = self.response;
				if(self.json && typeof response == 'string') {
					try {var response = JSON.parse(response);}
					catch(e) {var response = self.response;}
				}
				if(typeof self.onFinished == 'function') self.onFinished(self.status, response);
				if(self.status == 200) {
					if(typeof self.onSuccess == 'function') self.onSuccess(response);
				}
				else {
					if(typeof self.onError == 'function') self.onError(self.status);
				}
				break;
		}
	}

	this.send = function(data) {
		if(typeof data == 'object' && data !== null) this.data = data;

		this.xhr.open(this.method, this.url, true);

		if(this.method.toUpperCase() == 'POST') {
			this.xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		}
		else {
			this.xhr.setRequestHeader('Content-type', '');
		}

		this.xhr.send(this.params);
	};
};

Request.notInitialized = 0;
Request.connectionEstablished = 1;
Request.recieved = 2;
Request.processing = 3;
Request.finished = 4;

Request.to = function(url, data, method) {
	return new Request(url, data, method);
};

Request.json = function(url, data, method) {
	var request = new Request(url, data, method);
	request.json = true;
	return request;
};

Request.encodeData = function(data, tree) {
	if(typeof tree != 'string') tree = '';
	var params = '';
	for(var p in data) {
		if(typeof data[p] == 'string' || typeof data[p] == 'number' || typeof data[p] == 'boolean') {
			params += tree+(tree == ''? p : '['+p+']')+'='+encodeURIComponent(data[p])+'&';
		}
		else if(typeof data[p] == 'object') {
			params += this.dataEncode(data[p], tree+(tree == '' ? p : '['+p+']'));
		}
	}
	return params.replace(/&$/, '');
};
