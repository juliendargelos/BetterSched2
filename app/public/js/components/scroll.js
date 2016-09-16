var scroll = {
	get top() {
		return document.body.scrollTop || document.documentElement.scrollTop;
	},
    set top(value) {
		document.body.scrollTop = document.documentElement.scrollTop = value;
	}
};
