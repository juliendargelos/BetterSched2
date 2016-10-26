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
// @import components/local

var pageSched = {
	spinner: new Spinner,
	swipe: new Swipe,
	days: document.getElementsByClassName('days')[0],
	breakpoint: 1060,
	mobile: false,
	weekParamLifetime: 300000,
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
						contain: this.matcher(filter.match),
						dontContain: this.matcher(filter.dontMatch, true),
						value: RegParser.compile(filter.list[filterInput.value])
					});
				}
			}
		}

		return filters;
	},
	matcher: function(matcher, dont) {
		if(typeof matcher != 'string') {
			return function() {
				return true;
			};
		}
		else {
			var matcher = RegParser.compile(matcher);
			if(dont === true) {
				return function(test) {
					return test.match(matcher) === null;
				};
			}
			else {
				return function(test) {
					return test.match(matcher) !== null;
				};
			}
		}
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
		saveFilters: function() {
			var groupFilters = this.groupFilters[this.group];

			if(groupFilters !== null && groupFilters !== undefined) {
				var filters = this.filtersData[groupFilters];
				if(filters !== undefined) {
					var cs = {};
					for(var name in filters) cs[name] = document.getElementById(this.idForFilter(name)).value;

					local.set('filters', JSON.stringify(cs));
				}
			}
		},
		loadFilters: function() {
			try {
				var filters = JSON.parse(local.get('filters'));
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
			}

			var week = local.get('week');
			if(week !== null) {
				var weekOptions = this.inputs.week.getElementsByTagName('option');
				for(var i = 0; i < weekOptions.length; i++) {
					var weekOption = weekOptions[i];
					if(weekOption.value == week) weekOption.setAttribute('selected', true);
					else weekOption.removeAttribute('selected');
				}
			}

			this.update();
			this.loadFilters();
		}
	},
	get: function(year, week, group, callback) {
		var self = this;

		this.loading = true;
		if(typeof callback != 'function') callback = function(){};

		sched.get(year, week, group, this.filters, function(status, schedule, stored) {
			if(status) {
				self.clear();
				sched.constructor.insert(schedule.days);
				self.onresize();
				if(self.form.week == api.defaultWeek && self.mobile) self.swipe.page = api.defaultDay - 1;
				local.set('week', self.form.week, self.weekParamLifetime)
				callback();
			}
			else {
				result.set(schedule.message);
				if(!stored) self.clear();
			}

			self.loading = false;
		});
	},
	onresize: function(init) {
		var self = this;

		if(window.innerWidth > this.breakpoint && (this.mobile || init === true)) {
			this.swipe.disabled = true;
			var firstElement = this.swipe.firstElement;
			if(firstElement) firstElement.style.marginLeft = '0px';
			this.mobile = false;
		}
		else if(window.innerWidth <= this.breakpoint && (!this.mobile || init === true)) {
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

		this.onresize(true);
		this.update();
	}
};

pageSched.init();
