var sched = {
	element: document.getElementsByClassName('sched')[0].getElementsByClassName('days')[0],
	url: '/api/sched',
	request: new Request,
	urlFor: function(year, week, group, filter) {
		filter = filter === undefined || filter == '' || filter == 'Aucun' ? false : filter;

		return this.url+'/'+year+'/'+week+'/'+group+(filter ? '/'+filter : '');
	},
	get: function(year, week, group, filter, callback) {
		this.request.url = this.urlFor(year, week, group, filter);
		
		this.request.success(function(response) {
			if(response.status) callback(response.status, response.sched);
			else callback(response.status, response);
		}).error(function() {
			callback(response.status, 'Erreur réseau');
		}).send();
	},
	compute: {
		duration: function(course) {
			var duration = (course.timeslot.end.hour - course.timeslot.begin.hour)*60;
			duration -= course.timeslot.begin.minute;
			duration += course.timeslot.end.minute;
			duration /=  api.minuteInterval;

			return duration;
		}
	},
	constructor: {
		insert: function(days) {
			var weekLength = 0;

			for(var i = 0; i < api.days.length; i++) {
				var name = api.days[i];
				if(Array.isArray(days[name])) {
					sched.element.appendChild(this.day(days[name], name));
					weekLength++;
				}
			}

			sched.element.setAttribute('data-week-length', weekLength);
		},
		day: function(day, name) {
			var element = document.createElement('article');
			var title = document.createElement('span');
			var courses = document.createElement('div');

			element.className = 'day';
			title.className = 'name';
			courses.className = 'courses';

			element.appendChild(title);
			element.appendChild(courses);
			title.appendChild(document.createTextNode(name));

			var last = null;

			var current = {
				hour: api.hourBegin,
				minute: 0
			};

			for(var i = 0; i < day.length; i++) {
				var course = day[i];

				if(current.minute != course.timeslot.begin.minute) {
					if(current.hour == course.timeslot.begin.hour) {
						for(var minute = current.minute; minute < course.timeslot.begin.minute; minute += api.minuteInterval) {
							courses.appendChild(this.hour(current.hour, minute));
						}
					}
					else {
						for(var minute = current.minute; minute < 60; minute += api.minuteInterval) {
							courses.appendChild(this.hour(current.hour, minute));
						}
						current.hour++;
					}
				}

				for(current.hour; current.hour < course.timeslot.begin.hour; current.hour++) {
					for(var minute = 0; minute < 60; minute += api.minuteInterval) {
						courses.appendChild(this.hour(current.hour, minute));
					}
				}

				courses.appendChild(this.course(course));

				current.hour = course.timeslot.end.hour;
				current.minute = course.timeslot.end.minute;
			}

			return element;
		},
		hour: function(hour, minute) {
			var element = document.createElement('div');
			element.className = 'hour';

			if(minute != 0) element.className += ' minute';
			if(minute == api.middleMinute) element.className += ' middle';

			return element;
		},
		course: function(course) {
			var element = document.createElement('div');
			var content = document.createElement('div');
			var name = document.createElement('p');
			var spanName = document.createElement('span');

			element.className = 'course';
			element.setAttribute('data-parallel-courses', course.parallelCourses);
			element.setAttribute('data-parallel-factor', course.parallelFactor);
			element.setAttribute('data-duration', sched.compute.duration(course));
			element.setAttribute('data-negative', course.negative);

			if(course.timeslot.begin.minute == 0 || course.timeslot.begin.minute == api.middleMinute) {
				element.setAttribute('data-begin-hour', true);
			}

			content.className = 'content';
			content.style.background = course.color;

			name.className = 'name';

			name.appendChild(spanName);
			spanName.appendChild(document.createTextNode(course.name));

			element.appendChild(content);
			content.appendChild(name);

			if(course.professors.length > 0 || course.classroom !== null) {
				var infos = document.createElement('div');
				infos.className = 'infos';

				content.appendChild(infos);

				if(course.professors.length > 0) {
					var professors = document.createElement('div');
					professors.className = 'professors';

					infos.appendChild(professors);

					for(var i = 0; i < course.professors.length; i++) {
						var span = document.createElement('span');
						span.appendChild(document.createTextNode(course.professors[i]));
						professors.appendChild(span);
					}
				}

				if(course.classroom !== null) {
					var classroom = document.createElement('div');
					classroom.className = 'classroom';

					infos.appendChild(classroom);

					classroom.appendChild(document.createTextNode(course.classroom));
				}
			}

			var timeslot = course.timeslot.begin.hour+'h'+course.timeslot.begin.minute;
			timeslot += ' - '+course.timeslot.end.hour+'h'+course.timeslot.end.minute;
			element.setAttribute('data-dev-timeslot', timeslot);

			return element;
		}
	},
	init: function() {
		this.request.json = true;
	}
};

sched.init();
