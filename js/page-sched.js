var eventsManager={on:function(n,e,t,i){t.addEventListener(n,e,void 0!==i&&i)},no:function(n,e,t){t.removeEventListener(n,e)},apply:function(n){n.on=function(n,e){window.eventsManager.on(n,e,this)},n.no=function(n,e){window.eventsManager.no(n,e,this)}},init:function(){this.apply(window),this.apply(Node.prototype)}};eventsManager.init();var Request=function(t,e,n,s){var i=this;this.url=t,n="string"==typeof n?n.toUpperCase():"GET",this.data="object"==typeof e?e:{},this.method="GET"!=n&&"POST"!=n?"GET":n,this.xhr=new XMLHttpRequest,this.json=!!s,this.onNotInitialized,this.onConnectionEstablished,this.onRecieved,this.onProcessing,this.onFinished,this.onSuccess,this.onError,this.notInitialized=function(t){return this.onNotInitialized=t,this},this.connectionEstablished=function(t){return this.onConnectionEstablished=t,this},this.recieved=function(t){return this.onRecieved=t,this},this.processing=function(t){return this.onProcessing=t,this},this.finished=function(t){return this.onFinished=t,this},this.success=function(t){return this.onSuccess=t,this},this.error=function(t){return this.onError=t,this},Object.defineProperties(this,{state:{get:function(){return this.xhr.readyState}},status:{get:function(){return this.xhr.status}},response:{get:function(){return this.xhr.responseText}},params:{get:function(){return Request.encodeData(this.data)}}}),this.xhr.onreadystatechange=function(){switch(i.state){case Request.notInitialized:"function"==typeof i.onNotInitialized&&i.onNotInitialized();break;case Request.connectionEstablished:"function"==typeof i.onConnectionEstablished&&i.onConnectionEstablished();break;case Request.recieved:"function"==typeof i.onRecieved&&i.onRecieved();break;case Request.processing:"function"==typeof i.onProcessing&&i.onProcessing();break;case Request.finished:var t=i.response;if(i.json&&"string"==typeof t)try{var t=JSON.parse(t)}catch(e){var t=i.response}"function"==typeof i.onFinished&&i.onFinished(i.status,t),200==i.status?"function"==typeof i.onSuccess&&i.onSuccess(t):"function"==typeof i.onError&&i.onError(i.status)}},this.send=function(t){"object"==typeof t&&null!==t&&(this.data=t),this.xhr.open(this.method,this.url,!0),"POST"==this.method.toUpperCase()?this.xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded"):this.xhr.setRequestHeader("Content-type",""),this.xhr.send(this.params)}};Request.notInitialized=0,Request.connectionEstablished=1,Request.recieved=2,Request.processing=3,Request.finished=4,Request.to=function(t,e,n){return new Request(t,e,n)},Request.json=function(t,e,n){var s=new Request(t,e,n);return s.json=!0,s},Request.encodeData=function(t,e){"string"!=typeof e&&(e="");var n="";for(var s in t)"string"==typeof t[s]||"number"==typeof t[s]||"boolean"==typeof t[s]?n+=e+(""==e?s:"["+s+"]")+"="+encodeURIComponent(t[s])+"&":"object"==typeof t[s]&&(n+=this.dataEncode(t[s],e+(""==e?s:"["+s+"]")));return n.replace(/&$/,"")};var result={element:null,p:null,duration:4e3,transition:250,timeout:null,get message(){return this.p.innerText},set message(t){this.p.innerHTML="",this.p.appendChild(document.createTextNode(t))},onclick:function(){var t=this;this.element.className="out",clearTimeout(this.timeout),this.timeout=setTimeout(function(){t.out()},this.transition)},out:function(){this.element.parentNode&&this.element.parentNode.removeChild(this.element)},set:function(t){var e=this;this.element.parentNode?(this.element.className="out",clearTimeout(this.timeout),this.timeout=setTimeout(function(){e.out(),e.set(t)},this.transition)):(this.message=t,this.element.className="",document.body.appendChild(this.element),this.timeout=setTimeout(function(){e.out()},this.duration))},init:function(){var t=this;this.element=document.createElement("div"),this.p=document.createElement("p");var e=document.createElement("div");e.className="wrapper",this.element.id="result",this.element.appendChild(e),this.element.on("click",function(){t.onclick()}),this.element.on("touchstart",function(){t.onclick()}),e.appendChild(this.p)}};result.init();var sched={element:document.getElementsByClassName("sched")[0].getElementsByClassName("days")[0],url:"/api/sched",request:new Request,current:null,currentParams:{year:null,week:null,group:null},equalsCurrent:function(e,t,r){return e==this.currentParams.year&&t==this.currentParams.week&&r==this.currentParams.group},urlFor:function(e,t,r){return this.url+"/"+e+"/"+t+"/"+r},get:function(e,t,r,a,n){var s=this;if(this.equalsCurrent(e,t,r)){var i=JSON.parse(JSON.stringify(this.current));i.days=s.filter(i.days,a),n(!0,i)}else{this.currentParams={year:e,week:t,group:r};var u=this.stored(this.currentParams);u&&s.manageResponse({status:!0,sched:u},a,n),this.request.url=this.urlFor(e,t,r),this.request.success(function(e){e.status?(s.manageResponse(e,a,n),s.store(s.currentParams,e.sched)):n(e.status,e,null!==u)}).error(function(e){n(e.status,{message:"Erreur réseau"},null!==u)}).send()}},manageResponse:function(e,t,r){this.current=e.sched;var a=JSON.parse(JSON.stringify(e.sched));a.days=this.filter(a.days,t),r(e.status,a)},key:function(e){return"sched:"+api.institute+","+e.year+","+e.week+","+e.group},stored:function(e){var t=this.key(e);return local.get(t)},store:function(e,t){var r=this.key(e);return local.set(r,t)},compute:{duration:function(e){var t=60*(e.timeslot.end.hour-e.timeslot.begin.hour);return t-=e.timeslot.begin.minute,t+=e.timeslot.end.minute,t/=api.minuteInterval},maxNegative:function(e){var t=0;for(var r in e.negative){var a=e.negative[r];a>t&&(t=a)}return t}},filter:function(e,t){if(!Array.isArray(t))return e;if(0==t.length)return e;var r=[];for(var a in e){var n=e[a];r[a]=[];for(var s=0;s<n.length;s++)for(var i=n[s],u=!1,o=0;o<t.length;o++){var l=t[o],d=i[l.test];if(l.contain(d)&&l.dontContain(d)){if(!d.match(l.value)){u&&(r[a]=r[a].slice(0,-1));for(var c=0;c<i.parallels.length;c++)for(var m=0;m<n.length;m++){var h=n[m];h.id==i.parallels[c]&&(h.parallelCourses--,h.negative[i.id]=0)}break}u||(r[a].push(i),u=!0)}else u||(r[a].push(i),u=!0)}}return r},constructor:{insert:function(e){for(var t=0,r=0;r<api.days.length;r++){var a=api.days[r];Array.isArray(e[a])&&(sched.element.appendChild(this.day(e[a],a)),t++)}sched.element.setAttribute("data-week-length",t)},day:function(e,t){var r=document.createElement("article"),a=document.createElement("span"),n=document.createElement("div");r.className="day",a.className="name",n.className="courses",r.appendChild(a),r.appendChild(n),a.appendChild(document.createTextNode(t));for(var s={hour:api.hourBegin,minute:0},i=0;i<e.length;i++){var u=e[i];if(s.minute!=u.timeslot.begin.minute)if(s.hour==u.timeslot.begin.hour)for(var o=s.minute;o<u.timeslot.begin.minute;o+=api.minuteInterval)n.appendChild(this.hour(s.hour,o));else{for(var o=s.minute;o<60;o+=api.minuteInterval)n.appendChild(this.hour(s.hour,o));s.hour++}for(s.hour;s.hour<u.timeslot.begin.hour;s.hour++)for(var o=0;o<60;o+=api.minuteInterval)n.appendChild(this.hour(s.hour,o));n.appendChild(this.course(u)),s.hour=u.timeslot.end.hour,s.minute=u.timeslot.end.minute}return r},hour:function(e,t){var r=document.createElement("div");return r.className="hour",0!=t&&(r.className+=" minute"),t==api.middleMinute&&(r.className+=" middle"),r},course:function(e){var t=document.createElement("div"),r=document.createElement("div"),a=document.createElement("p"),n=document.createElement("span");if(t.className="course",t.setAttribute("data-parallel-courses",e.parallelCourses),t.setAttribute("data-parallel-factor",e.parallelFactor),t.setAttribute("data-duration",sched.compute.duration(e)),t.setAttribute("data-negative",sched.compute.maxNegative(e)),0!=e.timeslot.begin.minute&&e.timeslot.begin.minute!=api.middleMinute||t.setAttribute("data-begin-hour",!0),r.className="content",r.style.background=e.color,a.className="name",a.appendChild(n),n.appendChild(document.createTextNode(e.name)),t.appendChild(r),r.appendChild(a),e.professors.length>0||null!==e.classroom){var s=document.createElement("div");if(s.className="infos",r.appendChild(s),e.professors.length>0){var i=document.createElement("div");i.className="professors",s.appendChild(i);for(var u=0;u<e.professors.length;u++){var o=document.createElement("span");o.appendChild(document.createTextNode(e.professors[u])),i.appendChild(o)}}if(null!==e.classroom){var l=document.createElement("div");l.className="classroom",s.appendChild(l),l.appendChild(document.createTextNode(e.classroom))}}return t}},init:function(){this.request.json=!0}};sched.init();var Spinner=function(e){var n=document.createElement("div");n.className="spinner init";var t=!0;Object.defineProperties(this,{element:{get:function(){return n}},center:{get:function(){return t},set:function(e){e=!!e,e!=t&&(t=e,this.element.className+=" center")}},hidden:{get:function(){return this.element.className.match(/\bhidden\b/)}}}),this.hide=function(){var e=this;this.element.className="spinner"+(this.center?" center":"")+" hidden",setTimeout(function(){e.remove()},Spinner.duration)},this.show=function(){this.element.className="spinner"+(this.center?" center":""),this.append()},this.append=function(e){e instanceof Node||(e=document.body),e.appendChild(this.element)},this.remove=function(){this.element.parentNode&&this.element.parentNode.removeChild(this.element)}};Spinner.transition=200;var scroll={get top(){return document.body.scrollTop||document.documentElement.scrollTop},set top(o){document.body.scrollTop=document.documentElement.scrollTop=o}};var Swipe=function(t){var i=this;Object.defineProperties(this,{width:{get:function(){return this.element.offsetWidth}},pageWidth:{get:function(){var t=this.firstElement;return t?t.offsetWidth:1}},elements:{get:function(){return this.element.childNodes}},firstElement:{get:function(){var t=this.elements;return t.length>0&&t[0]}},position:{set:function(t){var i=this.firstElement;if(i){var e=this.currentPage*this.pageWidth,s=t-e;s>this.pageWidth?t=e+this.pageWidth:s<-this.pageWidth&&(t=e-this.pageWidth),i.style.marginLeft=t+"px"}},get:function(){var t=this.firstElement;if(t){var i=parseInt(t.style.marginLeft);return"number"!=typeof i||isNaN(i)?0:i}return 0}},delta:{get:function(){return this.position-this.lastPosition}},page:{set:function(t){var i=this.elements.length-1;t=Math.round(t),t=t<0?0:t>i?i:t,this.position=-this.pageWidth*t},get:function(){return Math.round(this.position/this.pageWidth)}},speed:{get:function(){return this.delta/this.pageWidth*this.speedFactor}},delta:{get:function(){return this.position-this.lastPosition}},currentPage:{get:function(){return this.currentPosition/this.pageWidth}}}),this.element=t,this.transition=200,this.speedFactor=2e4,this.touchInit=0,this.currentPage=0,this.type="touch",this.starting=!1,this.lastPosition=0,this.disabled=!1,this.scrolling=!1,this.coordinates=function(t){return"touch"==this.type?void 0!==t.pageX&&void 0!==t.pageY?{x:t.pageX,y:t.pageY}:void 0!==t.touches&&t.touches.length>0?{x:t.touches[0].pageX,y:t.touches[0].pageY}:void 0!==t.changedTouches&&t.changedTouches.length>0?{x:t.changedTouches[0].pageX,y:t.changedTouches[0].pageY}:{x:0,y:0}:{x:t.pageX,y:t.pageY}},this.swipping=function(t){i.move(t)},this.start=function(t){this.disabled||(this.scrolling=!1,this.touchInit=this.coordinates(t),this.currentPosition=this.lastPosition=this.position,this.starting=!0,on("touch"==this.type?"touchmove":"mousemove",this.swipping),this.enableEndListener())},this.move=function(t){if(!this.scrolling){var i=this.coordinates(t);this.starting&&"touch"==this.type&&Math.abs(i.y-this.touchInit.y)>Math.abs(i.x-this.touchInit.x)?(this.scrolling=!0,this.end(t,!0)):(this.lastPosition=this.position,this.position=this.currentPosition+(i.x-this.touchInit.x),this.starting=!1,t.preventDefault())}},this.end=function(t,i){if(!this.disabled){var e=this;if(i!==!0){this.move(t);var s=-(this.position+this.speed)/this.pageWidth;this.page=s,s!=Math.round(s)&&(this.element.className.match(/\btransition\b/)||(this.element.className+=" transition",setTimeout(function(){e.element.className=e.element.className.replace(/\btransition\b/,"")},this.transition)))}no("touch"==this.type?"touchmove":"mousemove",this.swipping),this.disableEndListener()}},this.onresize=function(){this.page=-this.position/this.pageWidth},this.endListener=function(t){i.end(t)},this.enableEndListener=function(){var t=this;on("touchend",t.endListener),on("touchcancel",t.endListener),on("mouseup",t.endListener)},this.disableEndListener=function(){var t=this;no("touchend",t.endListener),no("touchcancel",t.endListener),no("mouseup",t.endListener)},this.init=function(){this.element&&(this.element.on("touchstart",function(t){i.type="touch",i.start(t)}),this.element.on("mousedown",function(t){i.type="mouse",i.start(t)}),on("resize",function(){i.disabled||i.onresize()}))},this.init()};var standalone={get support(){return"standalone"in window.navigator},get is(){return!!this.support&&1==window.navigator.standalone},init:function(){this.is&&document.body.setAttribute("data-standalone",!0)}};standalone.init();var links={elements:document.getElementsByTagName("a"),stop:/^(a|html)$/i,relative:/^[a-z\+\.\-]+:/i,transition:300,url:document.location.protocol+"//"+document.location.host,set loading(t){t?document.body.setAttribute("data-loadpage",!0):document.body.removeAttribute("data-loadpage")},onclick:function(t){var e=this;return function(n){n.preventDefault();var o=t.href;o.replace(document.location.href,"").indexOf("#")&&(o.match(e.relative)||o.indexOf(e.url))&&(n.preventDefault(),e.loading=!0,setTimeout(function(){document.location.href=t.href},e.transition))}},init:function(){for(var t=0;t<this.elements.length;t++){var e=this.elements[t];e.on("click",this.onclick(e)),e.on("touchstart",this.onclick(e))}}};links.init();var RegParser=function(e,t){this.delimiter="string"==typeof t?t:"/",this.string="string"==typeof e?e:"",Object.defineProperties(this,{parsePattern:{get:function(){var e=this.delimiter;return new RegExp("^"+e+"(.*)"+e+"([a-z]*)$","i")}},pattern:{get:function(){return this.string.replace(this.parsePattern,"$1")}},flags:{get:function(){return this.string.replace(this.parsePattern,"$2")}},compiled:{get:function(){return new RegExp(this.pattern,this.flags)}}})};RegParser.compile=function(e,t){return new RegParser(e,t).compiled};var local={lifetime:864e5,get available(){return"object"==typeof localStorage&&null!==localStorage},serialize:function(e,t,i){return JSON.stringify({value:e,timestamp:(new Date).getTime(),lifetime:"number"==typeof t?t:this.lifetime,expiration:"number"==typeof i?i:null})},unserialize:function(e){var t=null;if("string"==typeof e)try{t=JSON.parse(e)}catch(i){}return t},set:function(e,t,i){return localStorage.setItem(e,this.serialize(t,i))},get:function(e){var t=this.data(e);return this.update(t)},data:function(e){return this.unserialize(localStorage.getItem(e))},update:function(e){return null===e?null:this.expired(e)?(this.remove(key),null):e.value},remove:function(e){return localStorage.removeItem(e)},expired:function(e){return"object"!=typeof e||null===e||(null===e.expiration?(new Date).getTime()-e.timestamp>=e.lifetime:(new Date).getTime()>=e.expiration)},clean:function(){for(var e=localStorage.length,t=0;t<e;t++){var i=localStorage.key(t),n=this.data(i);this.expired(n)&&this.remove(i)}},init:function(){this.available||(window.localStorage={getItem:function(){},setitem:function(){},length:0}),this.clean()}};local.init();var pageSched={spinner:new Spinner,swipe:new Swipe,days:document.getElementsByClassName("days")[0],breakpoint:1060,mobile:!1,weekParamLifetime:3e5,message:{element:document.createElement("span"),all:["Remerciez Satellys pour la latence...","Non, les cours ne sont pas annulés...","Ne partez pas ça devrait arriver...","Profitez-en pour vous servir un café...","C'est l'histoire de Jano Lapin...","L'temps passe, j'vois l'soleil s'lever, s'coucher...","Currently trying to make the world a better place...","Attention derrière toi...","L'accès Satellys j'l'ai pas loué..."],get random(){return this.all[Math.floor(Math.random()*this.all.length)]},set:function(){this.element.innerHTML="",this.element.appendChild(document.createTextNode(this.random))},init:function(){pageSched.spinner.element.appendChild(this.element)}},set loading(e){document.body.className=e?"loading":"",this.message.set(),e?this.spinner.show():this.spinner.hide()},get filters(){var e=this.form.groupFilters[this.form.group];if(void 0!==e&&null!==e)for(var t=this.form.filters,n=[],i=0;i<t.length;i++){var r=t[i];if(""!=r.value){var s=this.form.filtersData[e][r.name];n.push({test:s.test,contain:this.matcher(s.match),dontContain:this.matcher(s.dontMatch,!0),value:RegParser.compile(s.list[r.value])})}}return n},matcher:function(e,t){if("string"!=typeof e)return function(){return!0};var e=RegParser.compile(e);return t===!0?function(t){return null===t.match(e)}:function(t){return null!==t.match(e)}},update:function(e){this.form.update(),this.get(this.form.year,this.form.week,this.form.group,e)},clear:function(){this.days.innerHTML=""},form:{filtersData:api.filters,currentGroupFilters:null,groupFilters:api.groupFilters,element:document.getElementsByTagName("form")[0],filtersField:document.getElementsByTagName("form")[0].getElementsByClassName("filters")[0],inputs:{filters:document.getElementsByTagName("form")[0].getElementsByClassName("filters")[0].getElementsByTagName("select")},get group(){return this.inputs.group.value},get year(){return this.inputs.year.value},get week(){return this.inputs.week.value},get filters(){return Array.prototype.slice.call(this.inputs.filters).map(function(e){return{name:e.name,value:e.value}})},idForFilter:function(e){return"filter-"+e},update:function(){var e=this.groupFilters[this.group];if(e!=this.currentGroupFilters)if(this.currentGroupFilters=e,this.filtersField.innerHTML="",null!==e&&void 0!==e){var t=this.filtersData[e];if(void 0!==t){this.filtersField.removeAttribute("data-empty");for(var n in t){var i=t[n],r=document.createElement("div"),s=document.createElement("label"),a=document.createElement("select"),l=this.idForFilter(n);r.className="filter",s.appendChild(document.createTextNode(n)),s.setAttribute("for",l),a.name=n,a.id=l,a.on("change",this.onchange);var o=document.createElement("option");o.value="",o.appendChild(document.createTextNode("Aucun")),a.appendChild(o);for(var u in i.list){var o=document.createElement("option");o.value=u,o.appendChild(document.createTextNode(u)),a.appendChild(o)}r.appendChild(s),r.appendChild(a),this.filtersField.appendChild(r)}}}else{this.filtersField.setAttribute("data-empty",!0);var c=document.createElement("p");c.appendChild(document.createTextNode("Aucun filtre disponible")),this.filtersField.appendChild(c)}else this.saveFilters()},onchange:function(){pageSched.update()},saveFilters:function(){var e=this.groupFilters[this.group];if(null!==e&&void 0!==e){var t=this.filtersData[e];if(void 0!==t){var n={};for(var i in t)n[i]=document.getElementById(this.idForFilter(i)).value;local.set("filters",JSON.stringify(n))}}},loadFilters:function(){try{var e=JSON.parse(local.get("filters"))}catch(t){}if("object"==typeof e&&null!==e)for(var n in e){var i=document.getElementById(this.idForFilter(n));if(null!==i)for(var r=i.getElementsByTagName("option"),s=!1,a=0;a<r.length&&!s;a++){var l=r[a];l.getAttribute("value")==e[n]&&(l.setAttribute("selected",!0),s=!0)}}},init:function(){for(var e=this.element.getElementsByTagName("select"),t=0;t<e.length;t++){var n=e[t];"filter"!=n.parentNode.className&&(this.inputs[n.id.substr(6)]=n),n.on("change",this.onchange)}var i=local.get("week");if(null!==i)for(var r=this.inputs.week.getElementsByTagName("option"),t=0;t<r.length;t++){var s=r[t];s.value==i?s.setAttribute("selected",!0):s.removeAttribute("selected")}this.update(),this.loadFilters()}},quote:{element:null,sibling:document.getElementsByClassName("sched")[0],request:new Request("/quote/current"),get parent(){return this.sibling.parentNode},get expiration(){var e=new Date;return e.setDate(e.getDate()+1),e.getTime()},get:function(e){var t=local.get("quote");null!==t&&e(t,!0),this.request.success(function(t){t.status&&(null!==t.quote?(e(t.quote),local.set("quote",t.quote,null,this.expiration)):local.remove("quote"))}).send()},append:function(e,t){null==this.element?(this.element=this.create(e,t),this.parent.insertBefore(this.element,this.sibling)):(this.element.innerHTML="",this.element.appendChild(this.content(e)))},create:function(e,t){var n=document.createElement("div");return n.className="quote"+(t?" static":""),n.appendChild(this.content(e)),n},content:function(e){var t=document.createElement("div");t.className="wrapper";var n=document.createElement("span");n.className="content";var i=document.createElement("span");return i.className="author",n.innerHTML=this.cleaner.htmlFor(e.content),i.innerHTML=null===e.author?"Anonyme":this.cleaner.htmlFor(e.author),t.appendChild(n),t.innerHTML+="&nbsp;—&nbsp;",t.appendChild(i),t},cleaner:{escape:function(e){return String(e).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;")},unbreakPunctuation:function(e){return e=e.replace(/\s([\?!:;])/g,"&nbsp;$1"),e=e.replace(/\s-/g,"&nbsp;-"),e=e.replace(/-\s/g,"-&nbsp;"),e=e.replace(/-/g,"&#8209;")},htmlFor:function(e){return e=this.escape(e),e=this.unbreakPunctuation(e)}},init:function(){var e=this;this.request.json=!0,this.get(function(t,n){e.append(t,n)})}},get:function(e,t,n,i){var r=this;this.loading=!0,"function"!=typeof i&&(i=function(){}),sched.get(e,t,n,this.filters,function(e,t,n){e?(r.clear(),sched.constructor.insert(t.days),r.onresize(),r.form.week==api.defaultWeek&&r.mobile&&(r.swipe.page=api.defaultDay-1),local.set("week",r.form.week,r.weekParamLifetime),i()):(result.set(t.message),n||r.clear()),r.loading=!1})},onresize:function(e){if(window.innerWidth>this.breakpoint&&(this.mobile||e===!0)){this.swipe.disabled=!0;var t=this.swipe.firstElement;t&&(t.style.marginLeft="0px"),this.mobile=!1}else window.innerWidth<=this.breakpoint&&(!this.mobile||e===!0)&&(this.mobile=!0,this.swipe.disabled=!1)},init:function(){var e=this;on("resize",function(){e.onresize()}),this.swipe.element=this.days,this.swipe.init(),this.message.init(),this.form.init(),this.quote.init(),this.onresize(!0),this.update()}};pageSched.init();