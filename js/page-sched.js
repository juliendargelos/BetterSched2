var eventsManager={on:function(n,e,t){t.addEventListener(n,e,!1)},no:function(n,e,t){t.removeEventListener(n,e)},apply:function(n){n.on=function(n,e){window.eventsManager.on(n,e,this)},n.no=function(n,e){window.eventsManager.no(n,e,this)}},init:function(){this.apply(window),this.apply(Node.prototype)}};eventsManager.init();var Request=function(t,e,n,s){var i=this;this.url=t,n="string"==typeof n?n.toUpperCase():"GET",this.data="object"==typeof e?e:{},this.method="GET"!=n&&"POST"!=n?"GET":n,this.xhr=new XMLHttpRequest,this.json=!!s,this.onNotInitialized,this.onConnectionEstablished,this.onRecieved,this.onProcessing,this.onFinished,this.onSuccess,this.onError,this.notInitialized=function(t){return this.onNotInitialized=t,this},this.connectionEstablished=function(t){return this.onConnectionEstablished=t,this},this.recieved=function(t){return this.onRecieved=t,this},this.processing=function(t){return this.onProcessing=t,this},this.finished=function(t){return this.onFinished=t,this},this.success=function(t){return this.onSuccess=t,this},this.error=function(t){return this.onError=t,this},Object.defineProperties(this,{state:{get:function(){return this.xhr.readyState}},status:{get:function(){return this.xhr.status}},response:{get:function(){return this.xhr.responseText}},params:{get:function(){return Request.encodeData(this.data)}}}),this.xhr.onreadystatechange=function(){switch(i.state){case Request.notInitialized:"function"==typeof i.onNotInitialized&&i.onNotInitialized();break;case Request.connectionEstablished:"function"==typeof i.onConnectionEstablished&&i.onConnectionEstablished();break;case Request.recieved:"function"==typeof i.onRecieved&&i.onRecieved();break;case Request.processing:"function"==typeof i.onProcessing&&i.onProcessing();break;case Request.finished:var t=i.response;if(i.json&&"string"==typeof t)try{var t=JSON.parse(t)}catch(e){var t=i.response}"function"==typeof i.onFinished&&i.onFinished(i.status,t),200==i.status?"function"==typeof i.onSuccess&&i.onSuccess(t):"function"==typeof i.onError&&i.onError(i.status)}},this.send=function(t){"object"==typeof t&&null!==t&&(this.data=t),this.xhr.open(this.method,this.url,!0),"POST"==this.method.toUpperCase()?this.xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded"):this.xhr.setRequestHeader("Content-type",""),this.xhr.send(this.params)}};Request.notInitialized=0,Request.connectionEstablished=1,Request.recieved=2,Request.processing=3,Request.finished=4,Request.to=function(t,e,n){return new Request(t,e,n)},Request.json=function(t,e,n){var s=new Request(t,e,n);return s.json=!0,s},Request.encodeData=function(t,e){"string"!=typeof e&&(e="");var n="";for(var s in t)"string"==typeof t[s]||"number"==typeof t[s]||"boolean"==typeof t[s]?n+=e+(""==e?s:"["+s+"]")+"="+encodeURIComponent(t[s])+"&":"object"==typeof t[s]&&(n+=this.dataEncode(t[s],e+(""==e?s:"["+s+"]")));return n.replace(/&$/,"")};var result={element:null,p:null,stack:[],last:0,duration:4e3,get message(){return this.p.innerText},set message(e){this.p.innerHTML="",this.p.appendChild(document.createTextNode(e))},get delta(){return(new Date).getTime()-this.last-this.duration},set:function(e){var t=this,n=this.delta;n>0?(this.last=(new Date).getTime(),this.message=e,document.body.appendChild(this.element),setTimeout(function(){if(t.element.parentNode&&t.element.parentNode.removeChild(t.element),t.stack.length>0){var e=t.stack[0];t.stack=t.stack.slice(1),t.set(e)}},this.duration)):this.stack.push(e)},init:function(){this.element=document.createElement("div"),this.p=document.createElement("p");var e=document.createElement("div");e.className="wrapper",this.element.id="result",this.element.appendChild(e),e.appendChild(this.p)}};result.init();var sched={element:document.getElementsByClassName("sched")[0].getElementsByClassName("days")[0],url:"/api/sched",request:new Request,urlFor:function(e,t,n,a){return a=void 0!==a&&""!=a&&"Aucun"!=a&&a,this.url+"/"+e+"/"+t+"/"+n+(a?"/"+a:"")},get:function(e,t,n,a,r){this.request.url=this.urlFor(e,t,n,a),this.request.success(function(e){e.status?r(e.status,e.sched):r(e.status,e)}).error(function(){r(response.status,"Erreur réseau")}).send()},compute:{duration:function(e){var t=60*(e.timeslot.end.hour-e.timeslot.begin.hour);return t-=e.timeslot.begin.minute,t+=e.timeslot.end.minute,t/=api.minuteInterval}},constructor:{insert:function(e){for(var t=0,n=0;n<api.days.length;n++){var a=api.days[n];Array.isArray(e[a])&&(sched.element.appendChild(this.day(e[a],a)),t++)}sched.element.setAttribute("data-week-length",t)},day:function(e,t){var n=document.createElement("article"),a=document.createElement("span"),r=document.createElement("div");n.className="day",a.className="name",r.className="courses",n.appendChild(a),n.appendChild(r),a.appendChild(document.createTextNode(t));for(var s={hour:api.hourBegin,minute:0},i=0;i<e.length;i++){var o=e[i];if(s.minute!=o.timeslot.begin.minute)if(s.hour==o.timeslot.begin.hour)for(var u=s.minute;u<o.timeslot.begin.minute;u+=api.minuteInterval)r.appendChild(this.hour(s.hour,u));else{for(var u=s.minute;u<60;u+=api.minuteInterval)r.appendChild(this.hour(s.hour,u));s.hour++}for(s.hour;s.hour<o.timeslot.begin.hour;s.hour++)for(var u=0;u<60;u+=api.minuteInterval)r.appendChild(this.hour(s.hour,u));r.appendChild(this.course(o)),s.hour=o.timeslot.end.hour,s.minute=o.timeslot.end.minute}return n},hour:function(e,t){var n=document.createElement("div");return n.className="hour",0!=t&&(n.className+=" minute"),t==api.middleMinute&&(n.className+=" middle"),n},course:function(e){var t=document.createElement("div"),n=document.createElement("div"),a=document.createElement("p"),r=document.createElement("span");if(t.className="course",t.setAttribute("data-parallel-courses",e.parallelCourses),t.setAttribute("data-parallel-factor",e.parallelFactor),t.setAttribute("data-duration",sched.compute.duration(e)),t.setAttribute("data-negative",e.negative),0!=e.timeslot.begin.minute&&e.timeslot.begin.minute!=api.middleMinute||t.setAttribute("data-begin-hour",!0),n.className="content",n.style.background=e.color,a.className="name",a.appendChild(r),r.appendChild(document.createTextNode(e.name)),t.appendChild(n),n.appendChild(a),e.professors.length>0||null!==e.classroom){var s=document.createElement("div");if(s.className="infos",n.appendChild(s),e.professors.length>0){var i=document.createElement("div");i.className="professors",s.appendChild(i);for(var o=0;o<e.professors.length;o++){var u=document.createElement("span");u.appendChild(document.createTextNode(e.professors[o])),i.appendChild(u)}}if(null!==e.classroom){var l=document.createElement("div");l.className="classroom",s.appendChild(l),l.appendChild(document.createTextNode(e.classroom))}}var d=e.timeslot.begin.hour+"h"+e.timeslot.begin.minute;return d+=" - "+e.timeslot.end.hour+"h"+e.timeslot.end.minute,t.setAttribute("data-dev-timeslot",d),t}},init:function(){this.request.json=!0}};sched.init();var Spinner=function(e){var n=document.createElement("div");n.className="spinner init";var t=!0;Object.defineProperties(this,{element:{get:function(){return n}},center:{get:function(){return t},set:function(e){e=!!e,e!=t&&(t=e,this.element.className+=" center")}},hidden:{get:function(){return this.element.className.match(/\bhidden\b/)}}}),this.hide=function(){var e=this;this.element.className="spinner"+(this.center?" center":"")+" hidden",setTimeout(function(){e.remove()},Spinner.duration)},this.show=function(){this.element.className="spinner"+(this.center?" center":""),this.append()},this.append=function(e){e instanceof Node||(e=document.body),e.appendChild(this.element)},this.remove=function(){this.element.parentNode&&this.element.parentNode.removeChild(this.element)}};Spinner.transition=200;var scroll={get top(){return document.body.scrollTop||document.documentElement.scrollTop},set top(o){document.body.scrollTop=document.documentElement.scrollTop=o}};var header={onscroll:function(){scroll.top<=0&&"scroll-top"!=document.body.className?document.body.className="scroll-top":scroll.top>0&&"scroll-top"==document.body.className&&(document.body.className="")},init:function(){on("scroll",this.onscroll)}};header.init();var Swipe=function(t){var i=this;Object.defineProperties(this,{width:{get:function(){return this.element.offsetWidth}},pageWidth:{get:function(){var t=this.firstElement;return t?t.offsetWidth:0}},elements:{get:function(){return this.element.childNodes}},firstElement:{get:function(){var t=this.elements;return t.length>0&&t[0]}},position:{set:function(t){var i=this.firstElement;i&&(i.style.marginLeft=t+"px")},get:function(){var t=this.firstElement;if(t){var i=parseInt(t.style.marginLeft);return"number"!=typeof i||isNaN(i)?0:i}return 0}},page:{set:function(t){var i=this.elements.length-1;t=Math.round(t),t=t<0?0:t>i?i:t,this.position=-this.pageWidth*t},get:function(){return this.position/this.pageWidth}}}),this.element=t,this.transition=200,this.touchInit=0,this.currentPage=0,this.type="touch",this.starting=!1,this.swipping=function(t){i.move(t)},this.start=function(t){this.touchInit={x:"touch"==this.type?t.pageX:t.clientX,y:"touch"==this.type?t.pageY:t.clientY},this.currentPosition=this.position,this.starting=!0,on("touch"==this.type?"touchmove":"mousemove",this.swipping)},this.move=function(t){this.starting&&"touch"==this.type&&Math.abs(t.pageY-this.touchInit.y)>Math.abs(t.pageX-this.touchInit.x)?this.end():(this.position=this.currentPosition+(("touch"==this.type?t.pageX:t.clientX)-this.touchInit.x),this.starting=!1,t.preventDefault())},this.end=function(){var t=this;no("touch"==this.type?"touchmove":"mousemove",this.swipping);var i=-this.position/this.pageWidth;this.page=i,i!=Math.round(i)&&(this.element.className.match(/\btransition\b/)||(this.element.className+=" transition",setTimeout(function(){t.element.className=t.element.className.replace(/\btransition\b/,"")},this.transition)))},this.init=function(){this.element&&(this.element.on("touchstart",function(t){i.type="touch",i.start(t)}),this.element.on("mousedown",function(t){i.type="mouse",i.start(t)}),on("touchend",function(){"touch"==i.type&&i.end()}),on("touchcancel",function(){"touch"==i.type&&i.end()}),on("mouseup",function(){"mouse"==i.type&&i.end()}))},this.init()};var pageSched={spinner:new Spinner,swipe:new Swipe,days:document.getElementsByClassName("days")[0],breakpoint:1060,message:{element:document.createElement("span"),all:["Remerciez Satellys pour la latence...","Non, les cours ne sont pas annulés...","Ne partez pas ça devrait arriver..."],get random(){return this.all[Math.floor(Math.random()*(this.all.length-1))]},set:function(){this.element.innerHTML="",this.element.appendChild(document.createTextNode(this.random))},init:function(){pageSched.spinner.element.appendChild(this.element)}},set loading(e){this.days.className=e?"days hidden":"days",this.message.set(),e?this.spinner.show():this.spinner.hide()},update:function(){this.form.update(),this.get(this.form.year,this.form.week,this.form.group,this.form.filter)},clear:function(){this.days.innerHTML=""},form:{element:document.getElementsByTagName("form")[0],filters:document.getElementsByTagName("form")[0].getElementsByClassName("filters")[0],inputs:{get filter(){return pageSched.form.filters.getElementsByClassName("visible")[0].getElementsByTagName("select")[0]}},get group(){return this.inputs.group.value},get year(){return this.inputs.year.value},get week(){return this.inputs.week.value},get filter(){return this.inputs.filter.value},update:function(){for(var e=this.filters.getElementsByClassName("visible"),t=0;t<e.length;t++)e[t].className="";var n=document.getElementById("sched-filter-"+api.filters[this.group]);n||(n=document.getElementById("sched-filter-none")),n.parentNode.className="visible"},onchange:function(){pageSched.update()},init:function(){for(var e=this.element.getElementsByTagName("select"),t=0;t<e.length;t++){var n=e[t];"sched-filter-"!=n.id.substring(0,13)&&(this.inputs[n.id.substr(6)]=n),n.on("change",this.onchange)}}},get:function(e,t,n,i){var s=this;this.loading=!0,sched.get(e,t,n,i,function(e,t){e?(s.clear(),sched.constructor.insert(t.days)):result.set(t.message),s.loading=!1})},onresize:function(){window.innerWidth>this.breakpoint&&0!=this.swipe.position&&(this.swipe.position=0)},init:function(){var e=this;on("resize",function(){e.onresize()}),this.swipe.element=this.days,this.swipe.init(),this.message.init(),this.form.init(),this.update()}};pageSched.init();