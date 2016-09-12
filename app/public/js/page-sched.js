// @import components/eventsManager
// @import components/Request
// @import components/result
// @import components/sched
// @import components/Spinner
// @import components/scroll

var pageSched = {
	spinner: new Spinner,
	days: document.getElementsByClassName('days')[0],
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
		this.days.className = v ? 'days hidden' : 'days';
		this.message.set();
		if(v) this.spinner.show();
		else this.spinner.hide();
	},
	update: function() {
		this.form.update();
		this.get(this.form.year, this.form.week, this.form.group, this.form.filter);
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
	get: function(year, week, group, filter) {
		var self = this;

		this.loading = true;

		sched.get(year, week, group, filter, function(status, schedule) {
			if(status) {
				self.clear();
				sched.constructor.insert(schedule.days);
			}
			else result.set(schedule.message);

			self.loading = false;
		});
	},
	onscroll: function() {
		if(scroll.top <= 0 && document.body.className != 'scroll-top') document.body.className = 'scroll-top';
		else if(scroll.top > 0 && document.body.className == 'scroll-top') document.body.className = '';
	},
	init: function() {
		var self = this;

		on('scroll', self.onscroll);

		this.message.init();
		this.form.init();

		this.update();
	}
};

pageSched.init();
