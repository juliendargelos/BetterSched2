// @import components/eventsManager
// @import components/Request
// @import components/result
// @import components/sched
// @import components/Spinner
// @import components/scroll
// @import components/Swipe
// @import components/standalone
// @import components/links

var pageSched = {
	spinner: new Spinner,
	swipe: new Swipe,
	days: document.getElementsByClassName('days')[0],
	breakpoint: 1060,
	mobile: false,
	defaultDaySet: false,
	message: {
		element: document.createElement('span'),
		all: [
			'Remerciez Satellys pour la latence...',
			'Non, les cours ne sont pas annulés...',
			'Ne partez pas ça devrait arriver...'
		],
		get random() {
			return this.all[Math.floor((Math.random() * (this.all.length - 1)))];
		},
		set: function() {
			this.element.innerHTML = '';
			this.element.appendChild(document.createTextNode(this.random));
		},
		init: function() {
			pageSched.spinner.element.appendChild(this.element);
		}
	},
	set loading(v) {
		document.body.className = v ? 'loading' : '';
		this.message.set();
		if(v) this.spinner.show();
		else this.spinner.hide();
	},
	update: function(callback) {
		this.form.update();
		this.get(this.form.year, this.form.week, this.form.group, this.form.filter, callback);
	},
	clear: function() {
		this.days.innerHTML = '';
	},
	form: {
		element: document.getElementsByTagName('form')[0],
		filters: document.getElementsByTagName('form')[0].getElementsByClassName('filters')[0],
		inputs: {
			get filter() {
				return pageSched.form.filters.getElementsByClassName('visible')[0].getElementsByTagName('select')[0];
			}
		},
		get group() {
			return this.inputs.group.value;
		},
		get year() {
			return this.inputs.year.value;
		},
		get week() {
			return this.inputs.week.value;
		},
		get filter() {
			return this.inputs.filter.value;
		},
		update: function() {
			var visibleFilters = this.filters.getElementsByClassName('visible');

			for(var i = 0; i < visibleFilters.length; i++) visibleFilters[i].className = '';

			var filter = document.getElementById('sched-filter-'+api.filters[this.group]);
			if(!filter) filter = document.getElementById('sched-filter-none');

			filter.parentNode.className = 'visible';
		},
		onchange: function() {
			pageSched.update();
		},
		init: function() {
			var selects = this.element.getElementsByTagName('select');
			for(var i = 0; i < selects.length; i++) {
				var select = selects[i];
				if(select.id.substring(0, 13) != 'sched-filter-') this.inputs[select.id.substr(6)] = select;
				select.on('change', this.onchange);
			}
		}
	},
	get: function(year, week, group, filter, callback) {
		var self = this;

		this.loading = true;
		if(typeof callback != 'function') callback = function(){};

		sched.get(year, week, group, filter, function(status, schedule) {
			if(status) {
				self.clear();
				sched.constructor.insert(schedule.days);
				callback();
			}
			else result.set(schedule.message);

			self.loading = false;
		});
	},
	onresize: function(init) {
		if(window.innerWidth > this.breakpoint && (this.mobile || init === true)) {
			this.swipe.page = 0;
			this.swipe.disabled = true;
			this.mobile = false;
		}
		else if(window.innerWidth < this.breakpoint && !this.mobile) {
			if(!this.defaultDaySet) {
				this.swipe.page = (new Date()).getDay()-1;
				this.defaultDaySet = true;
			}
			this.mobile = true;
			this.swipe.disabled = false;
		}
	},
	init: function() {
		var self = this;

		on('resize', function() {
			self.onresize();
		});

		this.swipe.element = this.days;
		this.swipe.init();

		this.message.init();
		this.form.init();

		this.update(function() {
			self.onresize(true);
		});
	}
};

pageSched.init();
