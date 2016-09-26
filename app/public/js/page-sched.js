// @import components/eventsManager
// @import components/Request
// @import components/result
// @import components/sched
// @import components/Spinner
// @import components/scroll
// @import components/Swipe
// @import components/standalone
// @import components/links
// @import components/RegParser
// @import components/cookies

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
			'Ne partez pas ça devrait arriver...',
			'Profitez-en pour vous servir un café...',
			'C\'est l\'histoire de Jano Lapin...',
			'L\'temps passe, j\'vois l\'soleil s\'lever, s\'coucher...',
			'Currently trying to make the world a better place...',
			'Attention derrière toi...',
			'L\'accès Satellys j\'l\'ai pas loué...'
		],
		get random() {
			return this.all[Math.floor((Math.random() * this.all.length))];
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
	get filters() {
		var groupFilters = this.form.groupFilters[this.form.group];
		if(groupFilters !== undefined && groupFilters !== null) {
			var filtersInput = this.form.filters;
			var filters = [];
			for(var i = 0; i < filtersInput.length; i++) {
				var filterInput = filtersInput[i];
				if(filterInput.value != '') {
					var filter = this.form.filtersData[groupFilters][filterInput.name];

					filters.push({
						test: filter.test,
						match: RegParser.compile(filter.match),
						value: RegParser.compile(filter.list[filterInput.value])
					});
				}
			}
		}

		return filters;
	},
	update: function(callback) {
		var self = this;

		this.form.update();
		this.get(this.form.year, this.form.week, this.form.group, callback);
	},
	clear: function() {
		this.days.innerHTML = '';
	},
	form: {
		filtersData: api.filters,
		currentGroupFilters: null,
		groupFilters: api.groupFilters,
		element: document.getElementsByTagName('form')[0],
		filtersField: document.getElementsByTagName('form')[0].getElementsByClassName('filters')[0],
		inputs: {
			filters: document.getElementsByTagName('form')[0].getElementsByClassName('filters')[0].getElementsByTagName('select')
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
		get filters() {
			return Array.prototype.slice.call(this.inputs.filters).map(function(filter) {
				return {
					name: filter.name,
					value: filter.value
				};
			});
		},
		idForFilter: function(name) {
			return 'filter-'+name;
		},
		update: function() {
			var groupFilters = this.groupFilters[this.group];

			if(groupFilters != this.currentGroupFilters) {
				this.currentGroupFilters = groupFilters;
				this.filtersField.innerHTML = '';

				if(groupFilters !== null && groupFilters !== undefined) {
					var filters = this.filtersData[groupFilters];
					if(filters !== undefined) {
						this.filtersField.removeAttribute('data-empty');

						for(var name in filters) {
							var filter = filters[name];

							var parent = document.createElement('div');
							var label = document.createElement('label');
							var select = document.createElement('select');

							var id = this.idForFilter(name);

							parent.className = 'filter';
							label.appendChild(document.createTextNode(name));
							label.setAttribute('for', id);
							select.name = name;
							select.id = id;
							select.on('change', this.onchange);

							var option = document.createElement('option');
							option.value = '';
							option.appendChild(document.createTextNode('Aucun'));
							select.appendChild(option);

							for(var value in filter.list) {
								var option = document.createElement('option');
								option.value = value;
								option.appendChild(document.createTextNode(value));
								select.appendChild(option);
							}

							parent.appendChild(label);
							parent.appendChild(select);

							this.filtersField.appendChild(parent);
						}
					}
				}
				else {
					this.filtersField.setAttribute('data-empty', true);
					var p = document.createElement('p');
					p.appendChild(document.createTextNode('Aucun filtre disponible'));
					this.filtersField.appendChild(p);
				}
			}
			else this.saveFilters();
		},
		onchange: function() {
			pageSched.update();
		},
		ontouchstart: function(event) {
			this.getElementsByTagName('select')[0].focus();
			event.stopPropagation();
		},
		saveFilters: function() {
			var groupFilters = this.groupFilters[this.group];

			if(groupFilters !== null && groupFilters !== undefined) {
				var filters = this.filtersData[groupFilters];
				if(filters !== undefined) {
					var cs = {};
					for(var name in filters) cs[name] = document.getElementById(this.idForFilter(name)).value;

					cookies.set('filters', JSON.stringify(cs));
				}
			}
		},
		loadFilters: function() {
			try {
				var filters = JSON.parse(cookies.get('filters'));
			}
			catch(e) {}

			if(typeof filters == 'object' && filters !== null) {
				for(var name in filters) {
					var element = document.getElementById(this.idForFilter(name));
					var options = element.getElementsByTagName('option');
					var selected = false;

					for(var i = 0; i < options.length && !selected; i++) {
						var option = options[i];

						if(option.getAttribute('value') == filters[name]) {
							option.setAttribute('selected', true);
							selected = true;
						}
					}
				}
			}
		},
		init: function() {
			var selects = this.element.getElementsByTagName('select');
			for(var i = 0; i < selects.length; i++) {
				var select = selects[i];
				if(select.parentNode.className != 'filter') {
					this.inputs[select.id.substr(6)] = select;
				}
				select.on('change', this.onchange);
				select.parentNode.on('touchstart', this.ontouchstart);
			}

			this.update();
			this.loadFilters();
		}
	},
	get: function(year, week, group, callback) {
		var self = this;

		this.loading = true;
		if(typeof callback != 'function') callback = function(){};

		sched.get(year, week, group, this.filters, function(status, schedule) {
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
				this.swipe.page = api.defaultDay - 1;
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
