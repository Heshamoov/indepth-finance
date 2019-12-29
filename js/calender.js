/*
 * Metro 4 Components Library v4.2.48 build 734 (https://metroui.org.ua)
 * Copyright 2018 Sergey Pimenov
 * Licensed under MIT
 */
 (function( factory ) {
    if ( typeof define === 'function' && define.amd ) {
        define([ 'jquery' ], factory );
    } else {
        factory( jQuery );
    }
}(function( jQuery ) {

'use strict';

var $ = jQuery;

'use strict';

var $ = jQuery;

if (typeof jQuery === 'undefined') {
    throw new Error('Metro 4 requires jQuery!');
}

if ('MutationObserver' in window === false) {
    throw new Error('Metro 4 requires MutationObserver!');
}

var meta_init = $("meta[name='metro4:init']").attr("content");
var meta_locale = $("meta[name='metro4:locale']").attr("content");
var meta_week_start = $("meta[name='metro4:week_start']").attr("content");
var meta_date_format = $("meta[name='metro4:date_format']").attr("content");
var meta_date_format_input = $("meta[name='metro4:date_format_input']").attr("content");
var meta_animation_duration = $("meta[name='metro4:animation_duration']").attr("content");
var meta_callback_timeout = $("meta[name='metro4:callback_timeout']").attr("content");
var meta_timeout = $("meta[name='metro4:timeout']").attr("content");
var meta_scroll_multiple = $("meta[name='metro4:scroll_multiple']").attr("content");
var meta_cloak = $("meta[name='metro4:cloak']").attr("content"); //default or fade
var meta_cloak_duration = $("meta[name='metro4:cloak_duration']").attr("content"); //100

/* Added by Ken Kitay https://github.com/kens-code*/
var meta_about = $("meta[name='metro4:about']").attr("content");
if (window.METRO_SHOW_ABOUT === undefined) {
    window.METRO_SHOW_ABOUT = meta_about !== undefined ? JSON.parse(meta_about) : true;
}
/* --- end ---*/

var meta_compile = $("meta[name='metro4:compile']").attr("content");
if (window.METRO_SHOW_COMPILE_TIME === undefined) {
    window.METRO_SHOW_COMPILE_TIME = meta_compile !== undefined ? JSON.parse(meta_compile) : true;
}

if (window.METRO_INIT === undefined) {
    window.METRO_INIT = meta_init !== undefined ? JSON.parse(meta_init) : true;
}
if (window.METRO_DEBUG === undefined) {window.METRO_DEBUG = true;}

if (window.METRO_WEEK_START === undefined) {
    window.METRO_WEEK_START = meta_week_start !== undefined ? parseInt(meta_week_start) : 0;
}
if (window.METRO_DATE_FORMAT === undefined) {
    window.METRO_DATE_FORMAT = meta_date_format !== undefined ? meta_date_format : "%Y-%m-%d";
}
if (window.METRO_DATE_FORMAT_INPUT === undefined) {
    window.METRO_DATE_FORMAT_INPUT = meta_date_format_input !== undefined ? meta_date_format_input : "%Y-%m-%d";
}
if (window.METRO_LOCALE === undefined) {
    window.METRO_LOCALE = meta_locale !== undefined ? meta_locale : 'en-US';
}
if (window.METRO_ANIMATION_DURATION === undefined) {
    window.METRO_ANIMATION_DURATION = meta_animation_duration !== undefined ? parseInt(meta_animation_duration) : 300;
}
if (window.METRO_CALLBACK_TIMEOUT === undefined) {
    window.METRO_CALLBACK_TIMEOUT = meta_callback_timeout !== undefined ? parseInt(meta_callback_timeout) : 500;
}
if (window.METRO_TIMEOUT === undefined) {
    window.METRO_TIMEOUT = meta_timeout !== undefined ? parseInt(meta_timeout) : 2000;
}
if (window.METRO_SCROLL_MULTIPLE === undefined) {
    window.METRO_SCROLL_MULTIPLE = meta_scroll_multiple !== undefined ? parseInt(meta_scroll_multiple) : 20;
}
if (window.METRO_CLOAK_REMOVE === undefined) {
    window.METRO_CLOAK_REMOVE = meta_cloak !== undefined ? (""+meta_cloak).toLowerCase() : "fade";
}
if (window.METRO_CLOAK_DURATION === undefined) {
    window.METRO_CLOAK_DURATION = meta_cloak_duration !== undefined ? parseInt(meta_cloak_duration) : 500;
}
if (window.METRO_HOTKEYS_FILTER_CONTENT_EDITABLE === undefined) {window.METRO_HOTKEYS_FILTER_CONTENT_EDITABLE = true;}
if (window.METRO_HOTKEYS_FILTER_INPUT_ACCEPTING_ELEMENTS === undefined) {window.METRO_HOTKEYS_FILTER_INPUT_ACCEPTING_ELEMENTS = true;}
if (window.METRO_HOTKEYS_FILTER_TEXT_INPUTS === undefined) {window.METRO_HOTKEYS_FILTER_TEXT_INPUTS = true;}
if (window.METRO_HOTKEYS_BUBBLE_UP === undefined) {window.METRO_HOTKEYS_BUBBLE_UP = false;}
if (window.METRO_THROWS === undefined) {window.METRO_THROWS = true;}

window.METRO_MEDIA = [];

if ( typeof Object.create !== 'function' ) {
    Object.create = function (o) {
        function F() {}
        F.prototype = o;
        return new F();
    };
}

if (typeof Object.values !== 'function') {
    Object.values = function(obj) {
        return Object.keys(obj).map(function(e) {
            return obj[e]
        });
    }
}

if (typeof window.setImmediate !== 'function') {
    window.setImmediate = function(fn){
        return setTimeout(fn, 0);
    }
}

var isTouch = (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));

var Metro = {

    version: "4.2.48",
    compileTime: "@@compile",
    buildNumber: "734",
    isTouchable: isTouch,
    fullScreenEnabled: document.fullscreenEnabled,
    sheet: null,

    controlsPosition: {
        INSIDE: "inside",
        OUTSIDE: "outside"
    },

    groupMode: {
        ONE: "one",
        MULTI: "multi"
    },

    aspectRatio: {
        HD: "hd",
        SD: "sd",
        CINEMA: "cinema"
    },

    fullScreenMode: {
        WINDOW: "window",
        DESKTOP: "desktop"
    },

    position: {
        TOP: "top",
        BOTTOM: "bottom",
        LEFT: "left",
        RIGHT: "right",
        TOP_RIGHT: "top-right",
        TOP_LEFT: "top-left",
        BOTTOM_LEFT: "bottom-left",
        BOTTOM_RIGHT: "bottom-right",
        LEFT_BOTTOM: "left-bottom",
        LEFT_TOP: "left-top",
        RIGHT_TOP: "right-top",
        RIGHT_BOTTOM: "right-bottom"
    },

    popoverEvents: {
        CLICK: "click",
        HOVER: "hover",
        FOCUS: "focus"
    },

    stepperView: {
        SQUARE: "square",
        CYCLE: "cycle",
        DIAMOND: "diamond"
    },

    listView: {
        LIST: "list",
        CONTENT: "content",
        ICONS: "icons",
        ICONS_MEDIUM: "icons-medium",
        ICONS_LARGE: "icons-large",
        TILES: "tiles",
        TABLE: "table"
    },

    events: {
        click: 'click.metro',
        start: isTouch ? 'touchstart.metro' : 'mousedown.metro',
        stop: isTouch ? 'touchend.metro' : 'mouseup.metro',
        move: isTouch ? 'touchmove.metro' : 'mousemove.metro',
        enter: isTouch ? 'touchstart.metro' : 'mouseenter.metro',

        startAll: 'mousedown.metro touchstart.metro',
        stopAll: 'mouseup.metro touchend.metro',
        moveAll: 'mousemove.metro touchmove.metro',

        leave: 'mouseleave.metro',
        focus: 'focus.metro',
        blur: 'blur.metro',
        resize: 'resize.metro',
        keyup: 'keyup.metro',
        keydown: 'keydown.metro',
        keypress: 'keypress.metro',
        dblclick: 'dblclick.metro',
        input: 'input.metro',
        change: 'change.metro',
        cut: 'cut.metro',
        paste: 'paste.metro',
        scroll: 'scroll.metro',
        mousewheel: 'mousewheel.metro',
        inputchange: "change.metro input.metro propertychange.metro cut.metro paste.metro copy.metro",
        dragstart: "dragstart.metro",
        dragend: "dragend.metro",
        dragenter: "dragenter.metro",
        dragover: "dragover.metro",
        dragleave: "dragleave.metro",
        drop: 'drop.metro',
        drag: 'drag.metro'
    },

    keyCode: {
        BACKSPACE: 8,
        TAB: 9,
        ENTER: 13,
        SHIFT: 16,
        CTRL: 17,
        ALT: 18,
        BREAK: 19,
        CAPS: 20,
        ESCAPE: 27,
        SPACE: 32,
        PAGEUP: 33,
        PAGEDOWN: 34,
        END: 35,
        HOME: 36,
        LEFT_ARROW: 37,
        UP_ARROW: 38,
        RIGHT_ARROW: 39,
        DOWN_ARROW: 40,
        COMMA: 188
    },

    media_queries: {
        FS: "(min-width: 0px)",
        XS: "(min-width: 360px)",
        SM: "(min-width: 576px)",
        MD: "(min-width: 768px)",
        LG: "(min-width: 992px)",
        XL: "(min-width: 1200px)",
        XXL: "(min-width: 1452px)"
    },

    media_sizes: {
        FS: 0,
        XS: 360,
        SM: 576,
        LD: 640,
        MD: 768,
        LG: 992,
        XL: 1200,
        XXL: 1452
    },

    media_mode: {
        FS: "fs",
        XS: "xs",
        SM: "sm",
        MD: "md",
        LG: "lg",
        XL: "xl",
        XXL: "xxl"
    },

    media_modes: ["fs","xs","sm","md","lg","xl","xxl"],

    actions: {
        REMOVE: 1,
        HIDE: 2
    },

    hotkeys: {},

    about: function(){
        console.log("Metro 4 - v" + Metro.version +". "+ Metro.showCompileTime());
    },

    showCompileTime: function(){
        return "Built at: " + Metro.compileTime;
    },

    aboutDlg: function(){
        alert("Metro 4 - v" + Metro.version +". "+ Metro.showCompileTime());
    },

    ver: function(){
        return Metro.version;
    },

    build: function(){
        return Metro.build;
    },

    compile: function(){
        return Metro.compileTime;
    },

    observe: function(){
        var observer, observerCallback;
        var observerConfig = {
            childList: true,
            attributes: true,
            subtree: true
        };
        observerCallback = function(mutations){
            mutations.map(function(mutation){

                if (mutation.type === 'attributes' && mutation.attributeName !== "data-role") {
                    if (mutation.attributeName === 'data-hotkey') {

                        Metro.initHotkeys([mutation.target], true);

                    } else {

                        var element = $(mutation.target);
                        var mc = element.data('metroComponent');

                        if (mc !== undefined) {
                            $.each(mc, function(){
                                var plug = element.data(this);
                                if (plug) plug.changeAttribute(mutation.attributeName);
                            });
                        }
                    }
                } else

                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    var i, widgets = [];
                    var $node, node, nodes = mutation.addedNodes;

                    if (nodes.length) {
                        for(i = 0; i < nodes.length; i++) {
                            node = nodes[i];
                            $node = $(node);

                            if ($node.attr("data-role") !== undefined) {
                                widgets.push(node);
                            }

                            $.each($node.find("[data-role]"), function(){
                                var o = this;
                                if (widgets.indexOf(o) !== -1) {
                                    return;
                                }
                                widgets.push(o);
                            });
                        }

                        if (widgets.length) Metro.initWidgets(widgets, "observe");
                    }

                } else  {
                    //console.log(mutation);
                }
            });
        };
        observer = new MutationObserver(observerCallback);
        observer.observe($("html")[0], observerConfig);
    },

    init: function(){
        var widgets = $("[data-role]");
        var hotkeys = $("[data-hotkey]");
        var html = $("html");

        if (isTouch === true) {
            html.addClass("metro-touch-device");
        } else {
            html.addClass("metro-no-touch-device");
        }

        Metro.sheet = Utils.newCssSheet();


        window.METRO_MEDIA = [];
        $.each(Metro.media_queries, function(key, query){
            if (Utils.media(query)) {
                METRO_MEDIA.push(Metro.media_mode[key]);
            }
        });

        Metro.observe();

        Metro.initHotkeys(hotkeys);
        Metro.initWidgets(widgets, "init");

        if (METRO_SHOW_ABOUT) Metro.about(true);

        if (METRO_CLOAK_REMOVE !== "fade") {
            $(".m4-cloak").removeClass("m4-cloak");
        } else {
            $(".m4-cloak").animate({
                opacity: 1
            }, METRO_CLOAK_REMOVE, function(){
                $(".m4-cloak").removeClass("m4-cloak");
            })
        }

        return Metro;
    },

    initHotkeys: function(hotkeys, redefine){
        $.each(hotkeys, function(){
            var element = $(this);
            var hotkey = element.attr('data-hotkey') ? element.attr('data-hotkey').toLowerCase() : false;
            var fn = element.attr('data-hotkey-func') ? element.attr('data-hotkey-func') : false;

            //console.log(element);

            if (hotkey === false) {
                return;
            }

            if (element.data('hotKeyBonded') === true && !Utils.bool(redefine)) {
                return;
            }

            Metro.hotkeys[hotkey] = [this, fn];

            element.data('hotKeyBonded', true);
        });
    },

    initWidgets: function(widgets) {
        $.each(widgets, function () {
            var $this = $(this);
            var roles = $this.data('role').split(/\s*,\s*/);
            roles.map(function (func) {
                if ($.fn[func] !== undefined && $this.attr("data-role-"+func) === undefined) {
                    try {
                        $.fn[func].call($this);
                        $this.attr("data-role-"+func, true);

                        var mc = $this.data('metroComponent');

                        if (mc === undefined) {
                            mc = [func];
                        } else {
                            mc.push(func);
                        }
                        $this.data('metroComponent', mc);
                    } catch (e) {
                        console.log(e.message + " in " + e.stack);
                        throw e;
                    }
                }
            });
        });
    },

    plugin: function(name, object){
        $.fn[name] = function( options ) {
            return this.each(function() {
                $.data( this, name, Object.create(object).init(options, this ));
            });
        };
    },

    destroyPlugin: function(element, name){
        var p, mc;
        var el = $(element);

        p = el.data(name);

        if (!Utils.isValue(p)) {
            throw new Error("Component can not be destroyed: the element is not a Metro 4 component.");
        }

        if (!Utils.isFunc(p['destroy'])) {
            throw new Error("Component can not be destroyed: method destroy not found.");
        }

        p['destroy']();
        mc = el.data("metroComponent");
        Utils.arrayDelete(mc, name);
        el.data("metroComponent", mc);
        $.removeData(el[0], name);
        el.removeAttr("data-role-"+name);
    },

    destroyPluginAll: function(element){
        var el = $(element);
        var mc = el.data("metroComponent");

        if (mc !== undefined && mc.length > 0) $.each(mc, function(){
            Metro.destroyPlugin(el[0], this);
        });
    },

    initPlugin: function(element, name){
        element = $(element);
        try {
            if ($.fn[name] !== undefined && element.attr("data-role-"+name) === undefined) {
                $.fn[name].call(element);
                element.attr("data-role-"+name, true);

                var mc = element.data('metroComponent');

                if (mc === undefined) {
                    mc = [name];
                } else {
                    mc.push(name);
                }
                element.data('metroComponent', mc);
            }
        } catch (e) {
            console.log(e.message, e.stack);
        }
    },

    reinitPlugin: function(element, name){
        this.destroyPlugin(element, name);
        this.initPlugin(element, name);
    },

    reinitPluginAll: function(element){
        var mc = $(element).data("metroComponent");

        if (mc !== undefined && mc.length > 0) $.each(mc, function(){
            'use strict';
            Metro.reinitPlugin(element, this);
        });
    },

    noop: function(){},
    noop_true: function(){return true;},
    noop_false: function(){return false;},

    stop: function(e){
        e.stopPropagation();
        e.preventDefault();
    },

    requestFullScreen: function(element){
        if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullScreen) {
            element.webkitRequestFullScreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        } else {
            element.requestFullscreen();
        }
    },

    exitFullScreen: function(){
        if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        }
        else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
        else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        } else {
            document.exitFullscreen();
        }
    },

    inFullScreen: function(){
        var fsm = (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
        return fsm !== undefined;
    },

    makeRuntime: function(el, role){
        var element = $(el);
        element.attr("data-role-"+role, true);
        var mc = element.data('metroComponent');

        if (mc === undefined) {
            mc = [role];
        } else {
            mc.push(role);
        }
        element.data('metroComponent', mc);
    }
};

window['Metro'] = Metro;

$(window).on(Metro.events.resize, function(){
    window.METRO_MEDIA = [];
    $.each(Metro.media_queries, function(key, query){
        if (Utils.media(query)) {
            METRO_MEDIA.push(Metro.media_mode[key]);
        }
    });
});



$.fn.extend({
    toggleAttr: function(a, v){
        return this.each(function(){
            var el = $(this);
            if (v !== undefined) {
                el.attr(a, v);
            } else {
                if (el.attr(a) !== undefined) {
                    el.removeAttr(a);
                } else {
                    el.attr(a, ""+a);
                }
            }
        });
    },

    clearClasses: function(){
        return this.each(function(){
            this.className = "";
        });
    },

    fire: function(eventName, data){
        return this.each(function(){
            var el = this;
            var e = document.createEvent('Events');
            e.detail = data;
            e.initEvent(eventName, true, false);
            el.dispatchEvent(e);
        });
    }
});

Array.prototype.shuffle = function () {
    var currentIndex = this.length, temporaryValue, randomIndex;

    while (0 !== currentIndex) {

        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        temporaryValue = this[currentIndex];
        this[currentIndex] = this[randomIndex];
        this[randomIndex] = temporaryValue;
    }

    return this;
};

Array.prototype.clone = function () {
    return this.slice(0);
};

Array.prototype.unique = function () {
    var a = this.concat();
    for (var i = 0; i < a.length; ++i) {
        for (var j = i + 1; j < a.length; ++j) {
            if (a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
};

if (!Array.from) {
    Array.from = function(val) {
        var i, a = [];

        if (val.length === undefined && typeof val === "object") {
            return Object.values(val);
        }

        if (val.length !== undefined) {
            for(i = 0; i < val.length; i++) {
                a.push(val[i]);
            }
            return a;
        }

        throw new Error("Value can not be converted to array");
    };
}

if (typeof Array.contains !== "function") {
    Array.prototype.contains = function(val, from){
        return this.indexOf(val, from) > -1;
    }
}

/**
 * Number.prototype.format(n, x, s, c)
 *
 * @param  n: length of decimal
 * @param  x: length of whole part
 * @param  s: sections delimiter
 * @param  c: decimal delimiter
 */
Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.contains = function() {
    return !!~String.prototype.indexOf.apply(this, arguments);
};

String.prototype.toDate = function(format, locale) {
    var result;
    var normalized, normalizedFormat, formatItems, dateItems, checkValue;
    var monthIndex, dayIndex, yearIndex, hourIndex, minutesIndex, secondsIndex;
    var year, month, day, hour, minute, second;
    var parsedMonth;

    locale = locale || "en-US";

    var monthNameToNumber = function(month){
        var d, months, index, i;

        month = month.substr(0, 3);

        if (
               locale !== undefined
            && locale !== "en-US"
            && Locales !== undefined
            && Locales[locale] !== undefined
            && Locales[locale]['calendar'] !== undefined
            && Locales[locale]['calendar']['months'] !== undefined
        ) {
            months = Locales[locale]['calendar']['months'];
            for(i = 12; i < months.length; i++) {
                if (months[i].toLowerCase() === month.toLowerCase()) {
                    index = i - 12;
                    break;
                }
            }
            month = Locales["en-US"]['calendar']['months'][index];
        }

        d = Date.parse(month + " 1, 1972");
        if(!isNaN(d)){
            return new Date(d).getMonth() + 1;
        }
        return -1;
    };

    if (format === undefined || format === null || format === "") {
        return new Date(this);
    }

    // normalized      = this.replace(/[^a-zA-Z0-9%]/g, '-');
    normalized      = this.replace(/[\/,.:\s]/g, '-');
    normalizedFormat= format.toLowerCase().replace(/[^a-zA-Z0-9%]/g, '-');
    formatItems     = normalizedFormat.split('-');
    dateItems       = normalized.split('-');
    checkValue      = normalized.replace(/\-/g,"");

    if (checkValue.trim() === "") {
        return "Invalid Date";
    }

    monthIndex  = formatItems.indexOf("mm") > -1 ? formatItems.indexOf("mm") : formatItems.indexOf("%m");
    dayIndex    = formatItems.indexOf("dd") > -1 ? formatItems.indexOf("dd") : formatItems.indexOf("%d");
    yearIndex   = formatItems.indexOf("yyyy") > -1 ? formatItems.indexOf("yyyy") : formatItems.indexOf("yy") > -1 ? formatItems.indexOf("yy") : formatItems.indexOf("%y");
    hourIndex     = formatItems.indexOf("hh") > -1 ? formatItems.indexOf("hh") : formatItems.indexOf("%h");
    minutesIndex  = formatItems.indexOf("ii") > -1 ? formatItems.indexOf("ii") : formatItems.indexOf("mi") > -1 ? formatItems.indexOf("mi") : formatItems.indexOf("%i");
    secondsIndex  = formatItems.indexOf("ss") > -1 ? formatItems.indexOf("ss") : formatItems.indexOf("%s");

    if (monthIndex > -1 && dateItems[monthIndex] !== "") {
        if (isNaN(parseInt(dateItems[monthIndex]))) {
            dateItems[monthIndex] = monthNameToNumber(dateItems[monthIndex]);
            if (dateItems[monthIndex] === -1) {
                return "Invalid Date";
            }
        } else {
            parsedMonth = parseInt(dateItems[monthIndex]);
            if (parsedMonth < 1 || parsedMonth > 12) {
                return "Invalid Date";
            }
        }
    } else {
        return "Invalid Date";
    }

    year  = yearIndex >-1 && dateItems[yearIndex] !== "" ? dateItems[yearIndex] : null;
    month = monthIndex >-1 && dateItems[monthIndex] !== "" ? dateItems[monthIndex] : null;
    day   = dayIndex >-1 && dateItems[dayIndex] !== "" ? dateItems[dayIndex] : null;

    hour    = hourIndex >-1 && dateItems[hourIndex] !== "" ? dateItems[hourIndex] : null;
    minute  = minutesIndex>-1 && dateItems[minutesIndex] !== "" ? dateItems[minutesIndex] : null;
    second  = secondsIndex>-1 && dateItems[secondsIndex] !== "" ? dateItems[secondsIndex] : null;

    result = new Date(year,month-1,day,hour,minute,second);

    return result;
};

String.prototype.toArray = function(delimiter, type, format){
    var str = this;
    var a;

    type = type || "string";
    delimiter = delimiter || ",";
    format = format === undefined || format === null ? false : format;

    a = (""+str).split(delimiter);

    return a.map(function(s){
        var result;

        switch (type) {
            case "int":
            case "integer": result = parseInt(s); break;
            case "number":
            case "float": result = parseFloat(s); break;
            case "date": result = !format ? new Date(s) : s.toDate(format); break;
            default: result = s.trim();
        }

        return result;
    });
};

Date.prototype.getWeek = function (dowOffset) {
    var nYear, nday, newYear, day, daynum, weeknum;

    dowOffset = !Utils.isValue(dowOffset) ? METRO_WEEK_START : typeof dowOffset === 'number' ? parseInt(dowOffset) : 0;
    newYear = new Date(this.getFullYear(),0,1);
    day = newYear.getDay() - dowOffset;
    day = (day >= 0 ? day : day + 7);
    daynum = Math.floor((this.getTime() - newYear.getTime() -
        (this.getTimezoneOffset()-newYear.getTimezoneOffset())*60000)/86400000) + 1;

    if(day < 4) {
        weeknum = Math.floor((daynum+day-1)/7) + 1;
        if(weeknum > 52) {
            nYear = new Date(this.getFullYear() + 1,0,1);
            nday = nYear.getDay() - dowOffset;
            nday = nday >= 0 ? nday : nday + 7;
            weeknum = nday < 4 ? 1 : 53;
        }
    }
    else {
        weeknum = Math.floor((daynum+day-1)/7);
    }
    return weeknum;
};

Date.prototype.getYear = function(){
    return this.getFullYear().toString().substr(-2);
};

Date.prototype.format = function(format, locale){

    if (locale === undefined) {
        locale = "en-US";
    }

    var cal = (Metro.locales !== undefined && Metro.locales[locale] !== undefined ? Metro.locales[locale] : Metro.locales["en-US"])['calendar'];

    var date = this;
    var nDay = date.getDay(),
        nDate = date.getDate(),
        nMonth = date.getMonth(),
        nYear = date.getFullYear(),
        nHour = date.getHours(),
        aDays = cal['days'],
        aMonths = cal['months'],
        aDayCount = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334],
        isLeapYear = function() {
            return (nYear%4===0 && nYear%100!==0) || nYear%400===0;
        },
        getThursday = function() {
            var target = new Date(date);
            target.setDate(nDate - ((nDay+6)%7) + 3);
            return target;
        },
        zeroPad = function(nNum, nPad) {
            return ('' + (Math.pow(10, nPad) + nNum)).slice(1);
        };
    return format.replace(/(%[a-z])/gi, function(sMatch) {
        return {
            '%a': aDays[nDay].slice(0,3),
            '%A': aDays[nDay],
            '%b': aMonths[nMonth].slice(0,3),
            '%B': aMonths[nMonth],
            '%c': date.toUTCString(),
            '%C': Math.floor(nYear/100),
            '%d': zeroPad(nDate, 2),
            'dd': zeroPad(nDate, 2),
            '%e': nDate,
            '%F': date.toISOString().slice(0,10),
            '%G': getThursday().getFullYear(),
            '%g': ('' + getThursday().getFullYear()).slice(2),
            '%H': zeroPad(nHour, 2),
            // 'HH': zeroPad(nHour, 2),
            '%I': zeroPad((nHour+11)%12 + 1, 2),
            '%j': zeroPad(aDayCount[nMonth] + nDate + ((nMonth>1 && isLeapYear()) ? 1 : 0), 3),
            '%k': '' + nHour,
            '%l': (nHour+11)%12 + 1,
            '%m': zeroPad(nMonth + 1, 2),
            // 'mm': zeroPad(nMonth + 1, 2),
            '%M': zeroPad(date.getMinutes(), 2),
            // 'MM': zeroPad(date.getMinutes(), 2),
            '%p': (nHour<12) ? 'AM' : 'PM',
            '%P': (nHour<12) ? 'am' : 'pm',
            '%s': Math.round(date.getTime()/1000),
            // 'ss': Math.round(date.getTime()/1000),
            '%S': zeroPad(date.getSeconds(), 2),
            // 'SS': zeroPad(date.getSeconds(), 2),
            '%u': nDay || 7,
            '%V': (function() {
                var target = getThursday(),
                    n1stThu = target.valueOf();
                target.setMonth(0, 1);
                var nJan1 = target.getDay();
                if (nJan1!==4) target.setMonth(0, 1 + ((4-nJan1)+7)%7);
                return zeroPad(1 + Math.ceil((n1stThu-target)/604800000), 2);
            })(),
            '%w': '' + nDay,
            '%x': date.toLocaleDateString(),
            '%X': date.toLocaleTimeString(),
            '%y': ('' + nYear).slice(2),
            // 'yy': ('' + nYear).slice(2),
            '%Y': nYear,
            // 'YYYY': nYear,
            '%z': date.toTimeString().replace(/.+GMT([+-]\d+).+/, '$1'),
            '%Z': date.toTimeString().replace(/.+\((.+?)\)$/, '$1')
        }[sMatch] || sMatch;
    });
};

Date.prototype.addHours = function(n) {
    this.setTime(this.getTime() + (n*60*60*1000));
    return this;
};

Date.prototype.addDays = function(n) {
    this.setDate(this.getDate() + (n));
    return this;
};

Date.prototype.addMonths = function(n) {
    this.setMonth(this.getMonth() + (n));
    return this;
};

Date.prototype.addYears = function(n) {
    this.setFullYear(this.getFullYear() + (n));
    return this;
};


var Animation = {

    duration: METRO_ANIMATION_DURATION,
    func: "swing",

    switch: function(current, next){
        current.hide();
        next.css({top: 0, left: 0}).show();
    },

    slideUp: function(current, next, duration, func){
        var h = current.parent().outerHeight(true);
        if (duration === undefined) {duration = this.duration;}
        if (func === undefined) {func = this.func;}
        current.css("z-index", 1).animate({
            top: -h,
            opacity: 0
        }, duration, func);

        next.css({
            top: h,
            left: 0,
            zIndex: 2
        }).animate({
            top: 0,
            opacity: 1
        }, duration, func);
    },

    slideDown: function(current, next, duration, func){
        var h = current.parent().outerHeight(true);
        if (duration === undefined) {duration = this.duration;}
        if (func === undefined) {func = this.func;}
        current.css("z-index", 1).animate({
            top: h,
            opacity: 0
        }, duration, func);

        next.css({
            left: 0,
            top: -h,
            zIndex: 2
        }).animate({
            top: 0,
            opacity: 1
        }, duration, func);
    },

    slideLeft: function(current, next, duration, func){
        var w = current.parent().outerWidth(true);
        if (duration === undefined) {duration = this.duration;}
        if (func === undefined) {func = this.func;}
        current.css("z-index", 1).animate({
            left: -w,
            opacity: 0
        }, duration, func);

        next.css({
            left: w,
            zIndex: 2
        }).animate({
            left: 0,
            opacity: 1
        }, duration, func);
    },

    slideRight: function(current, next, duration, func){
        var w = current.parent().outerWidth(true);
        if (duration === undefined) {duration = this.duration;}
        if (func === undefined) {func = this.func;}
        current.css("z-index", 1).animate({
            left: w,
            opacity: 0
        }, duration, func);

        next.css({
            left: -w,
            zIndex: 2
        }).animate({
            left: 0,
            opacity: 1
        }, duration, func);
    },

    fade: function(current, next, duration){
        if (duration === undefined) {duration = this.duration;}
        current.animate({
            opacity: 0
        }, duration);
        next.css({
            top: 0,
            left: 0
        }).animate({
            opacity: 1
        }, duration);
    }

};

Metro['animation'] = Animation;

function RGB(r, g, b){
    this.r = r || 0;
    this.g = g || 0;
    this.g = b || 0;
}

function RGBA(r, g, b, a){
    this.r = r || 0;
    this.g = g || 0;
    this.g = b || 0;
    this.a = a || 1;
}

function HSV(h, s, v){
    this.h = h || 0;
    this.s = s || 0;
    this.v = v || 0;
}

function HSL(h, s, l){
    this.h = h || 0;
    this.s = s || 0;
    this.l = l || 0;
}

function HSLA(h, s, l, a){
    this.h = h || 0;
    this.s = s || 0;
    this.l = l || 0;
    this.a = a || 1;
}

function CMYK(c, m, y, k){
    this.c = c || 0;
    this.m = m || 0;
    this.y = y || 0;
    this.k = k || 0;
}

var Colors = {

    TYPES: {
        HEX: "hex",
        RGB: "rgb",
        RGBA: "rgba",
        HSV: "hsv",
        HSL: "hsl",
        CMYK: "cmyk",
        UNKNOWN: "unknown"
    },

    PALETTES: {
        ALL: "colorList",
        METRO: "colorListMetro",
        STANDARD: "colorListStandard"
    },

    colorListMetro: {
        lime: '#a4c400',
        green: '#60a917',
        emerald: '#008a00',
        blue: '#00AFF0',
        teal: '#00aba9',
        cyan: '#1ba1e2',
        cobalt: '#0050ef',
        indigo: '#6a00ff',
        violet: '#aa00ff',
        pink: '#dc4fad',
        magenta: '#d80073',
        crimson: '#a20025',
        red: '#CE352C',
        orange: '#fa6800',
        amber: '#f0a30a',
        yellow: '#fff000',
        brown: '#825a2c',
        olive: '#6d8764',
        steel: '#647687',
        mauve: '#76608a',
        taupe: '#87794e'
    },

    colorListStandard: {
        aliceBlue: "#f0f8ff",
        antiqueWhite: "#faebd7",
        aqua: "#00ffff",
        aquamarine: "#7fffd4",
        azure: "#f0ffff",
        beige: "#f5f5dc",
        bisque: "#ffe4c4",
        black: "#000000",
        blanchedAlmond: "#ffebcd",
        blue: "#0000ff",
        blueViolet: "#8a2be2",
        brown: "#a52a2a",
        burlyWood: "#deb887",
        cadetBlue: "#5f9ea0",
        chartreuse: "#7fff00",
        chocolate: "#d2691e",
        coral: "#ff7f50",
        cornflowerBlue: "#6495ed",
        cornsilk: "#fff8dc",
        crimson: "#dc143c",
        cyan: "#00ffff",
        darkBlue: "#00008b",
        darkCyan: "#008b8b",
        darkGoldenRod: "#b8860b",
        darkGray: "#a9a9a9",
        darkGreen: "#006400",
        darkKhaki: "#bdb76b",
        darkMagenta: "#8b008b",
        darkOliveGreen: "#556b2f",
        darkOrange: "#ff8c00",
        darkOrchid: "#9932cc",
        darkRed: "#8b0000",
        darkSalmon: "#e9967a",
        darkSeaGreen: "#8fbc8f",
        darkSlateBlue: "#483d8b",
        darkSlateGray: "#2f4f4f",
        darkTurquoise: "#00ced1",
        darkViolet: "#9400d3",
        deepPink: "#ff1493",
        deepSkyBlue: "#00bfff",
        dimGray: "#696969",
        dodgerBlue: "#1e90ff",
        fireBrick: "#b22222",
        floralWhite: "#fffaf0",
        forestGreen: "#228b22",
        fuchsia: "#ff00ff",
        gainsboro: "#DCDCDC",
        ghostWhite: "#F8F8FF",
        gold: "#ffd700",
        goldenRod: "#daa520",
        gray: "#808080",
        green: "#008000",
        greenYellow: "#adff2f",
        honeyDew: "#f0fff0",
        hotPink: "#ff69b4",
        indianRed: "#cd5c5c",
        indigo: "#4b0082",
        ivory: "#fffff0",
        khaki: "#f0e68c",
        lavender: "#e6e6fa",
        lavenderBlush: "#fff0f5",
        lawnGreen: "#7cfc00",
        lemonChiffon: "#fffacd",
        lightBlue: "#add8e6",
        lightCoral: "#f08080",
        lightCyan: "#e0ffff",
        lightGoldenRodYellow: "#fafad2",
        lightGray: "#d3d3d3",
        lightGreen: "#90ee90",
        lightPink: "#ffb6c1",
        lightSalmon: "#ffa07a",
        lightSeaGreen: "#20b2aa",
        lightSkyBlue: "#87cefa",
        lightSlateGray: "#778899",
        lightSteelBlue: "#b0c4de",
        lightYellow: "#ffffe0",
        lime: "#00ff00",
        limeGreen: "#32dc32",
        linen: "#faf0e6",
        magenta: "#ff00ff",
        maroon: "#800000",
        mediumAquaMarine: "#66cdaa",
        mediumBlue: "#0000cd",
        mediumOrchid: "#ba55d3",
        mediumPurple: "#9370db",
        mediumSeaGreen: "#3cb371",
        mediumSlateBlue: "#7b68ee",
        mediumSpringGreen: "#00fa9a",
        mediumTurquoise: "#48d1cc",
        mediumVioletRed: "#c71585",
        midnightBlue: "#191970",
        mintCream: "#f5fffa",
        mistyRose: "#ffe4e1",
        moccasin: "#ffe4b5",
        navajoWhite: "#ffdead",
        navy: "#000080",
        oldLace: "#fdd5e6",
        olive: "#808000",
        oliveDrab: "#6b8e23",
        orange: "#ffa500",
        orangeRed: "#ff4500",
        orchid: "#da70d6",
        paleGoldenRod: "#eee8aa",
        paleGreen: "#98fb98",
        paleTurquoise: "#afeeee",
        paleVioletRed: "#db7093",
        papayaWhip: "#ffefd5",
        peachPuff: "#ffdab9",
        peru: "#cd853f",
        pink: "#ffc0cb",
        plum: "#dda0dd",
        powderBlue: "#b0e0e6",
        purple: "#800080",
        rebeccaPurple: "#663399",
        red: "#ff0000",
        rosyBrown: "#bc8f8f",
        royalBlue: "#4169e1",
        saddleBrown: "#8b4513",
        salmon: "#fa8072",
        sandyBrown: "#f4a460",
        seaGreen: "#2e8b57",
        seaShell: "#fff5ee",
        sienna: "#a0522d",
        silver: "#c0c0c0",
        slyBlue: "#87ceeb",
        slateBlue: "#6a5acd",
        slateGray: "#708090",
        snow: "#fffafa",
        springGreen: "#00ff7f",
        steelBlue: "#4682b4",
        tan: "#d2b48c",
        teal: "#008080",
        thistle: "#d8bfd8",
        tomato: "#ff6347",
        turquoise: "#40e0d0",
        violet: "#ee82ee",
        wheat: "#f5deb3",
        white: "#ffffff",
        whiteSmoke: "#f5f5f5",
        yellow: "#ffff00",
        yellowGreen: "#9acd32"
    },

    colorList: {},

    options: {
        angle: 30,
        algorithm: 1,
        step: .1,
        distance: 5,
        tint1: .8,
        tint2: .4,
        shade1: .6,
        shade2: .3,
        alpha: 1
    },

    init: function(){
        this.colorList = $.extend( {}, this.colorListStandard, this.colorListMetro );
        return this;
    },

    setup: function(options){
        this.options = $.extend( {}, this.options, options );
    },

    color: function(name, palette){
        palette = palette || this.PALETTES.ALL;
        return this[palette][name] !== undefined ? this[palette][name] : false;
    },

    palette: function(palette){
        palette = palette || this.PALETTES.ALL;
        return Object.keys(this[palette]);
    },

    colors: function(palette){
        var c = [];
        palette = palette || this.PALETTES.ALL;
        $.each(this[palette], function(){
            c.push(this);
        });
        return c;
    },

    hex2rgb: function(hex){
        var regex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace( regex, function( m, r, g, b ) {
            return r + r + g + g + b + b;
        });
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );
        return result ? {
            r: parseInt( result[1], 16 ),
            g: parseInt( result[2], 16 ),
            b: parseInt( result[3], 16 )
        } : null;
    },

    rgb2hex: function(rgb){
        return "#" +
            (( 1 << 24 ) + ( rgb.r << 16 ) + ( rgb.g << 8 ) + rgb.b )
                .toString( 16 ).slice( 1 );
    },

    rgb2hsv: function(rgb){
        var hsv = new HSV();
        var h, s, v;
        var r = rgb.r / 255, g = rgb.g / 255, b = rgb.b / 255;
        var max = Math.max(r, g, b);
        var min = Math.min(r, g, b);
        var delta = max - min;

        v = max;

        if (max === 0) {
            s = 0;
        } else {
            s = 1 - min / max;
        }

        if (max === min) {
            h = 0;
        } else if (max === r && g >= b) {
            h = 60 * ( (g - b) / delta );
        } else if (max === r && g < b) {
            h = 60 * ( (g - b) / delta) + 360
        } else if (max === g) {
            h = 60 * ( (b - r) / delta) + 120
        } else if (max === b) {
            h = 60 * ( (r - g) / delta) + 240
        } else {
            h = 0;
        }

        hsv.h = h;
        hsv.s = s;
        hsv.v = v;

        return hsv;
    },

    hsv2rgb: function(hsv){
        var r, g, b;
        var h = hsv.h, s = hsv.s * 100, v = hsv.v * 100;
        var Hi = Math.floor(h / 60);
        var Vmin = (100 - s) * v / 100;
        var alpha = (v - Vmin) * ( (h % 60) / 60 );
        var Vinc = Vmin + alpha;
        var Vdec = v - alpha;

        switch (Hi) {
            case 0: r = v; g = Vinc; b = Vmin; break;
            case 1: r = Vdec; g = v; b = Vmin; break;
            case 2: r = Vmin; g = v; b = Vinc; break;
            case 3: r = Vmin; g = Vdec; b = v; break;
            case 4: r = Vinc; g = Vmin; b = v; break;
            case 5: r = v; g = Vmin; b = Vdec; break;
        }

        return {
            r: Math.round(r * 255 / 100),
            g: Math.round(g * 255 / 100),
            b: Math.round(b * 255 / 100)
        }
    },

    hsv2hex: function(hsv){
        return this.rgb2hex(this.hsv2rgb(hsv));
    },

    hex2hsv: function(hex){
        return this.rgb2hsv(this.hex2rgb(hex));
    },

    rgb2cmyk: function(rgb){
        var cmyk = new CMYK();

        var r = rgb.r / 255;
        var g = rgb.g / 255;
        var b = rgb.b / 255;

        cmyk.k = Math.min( 1 - r, 1 - g, 1 - b );
        cmyk.c = ( 1 - r - cmyk.k ) / ( 1 - cmyk.k );
        cmyk.m = ( 1 - g - cmyk.k ) / ( 1 - cmyk.k );
        cmyk.y = ( 1 - b - cmyk.k ) / ( 1 - cmyk.k );

        cmyk.c = Math.round( cmyk.c * 100 );
        cmyk.m = Math.round( cmyk.m * 100 );
        cmyk.y = Math.round( cmyk.y * 100 );
        cmyk.k = Math.round( cmyk.k * 100 );

        return cmyk;
    },

    cmyk2rgb: function(cmyk){
        var rgb = new RGB();

        var c = cmyk.c / 100;
        var m = cmyk.m / 100;
        var y = cmyk.y / 100;
        var k = cmyk.k / 100;

        rgb.r = 1 - Math.min( 1, c * ( 1 - k ) + k );
        rgb.g = 1 - Math.min( 1, m * ( 1 - k ) + k );
        rgb.b = 1 - Math.min( 1, y * ( 1 - k ) + k );

        rgb.r = Math.round( rgb.r * 255 );
        rgb.g = Math.round( rgb.g * 255 );
        rgb.b = Math.round( rgb.b * 255 );

        return rgb;
    },

    hsv2hsl: function(hsv){
        var h, s, l;
        h = hsv.h;
        l = (2 - hsv.s) * hsv.v;
        s = hsv.s * hsv.v;
        s /= (l <= 1) ? l : 2 - l;
        l /= 2;
        return {h: h, s: s, l: l}
    },

    hsl2hsv: function(hsl){
        var h, s, v, l;
        h = hsl.h;
        l = hsl.l * 2;
        s = hsl.s * (l <= 1 ? l : 2 - l);
        v = (l + s) / 2;
        s = (2 * s) / (l + s);
        return {h: h, s: s, l: v}
    },

    rgb2websafe: function(rgb){
        return {
            r: Math.round(rgb.r / 51) * 51,
            g: Math.round(rgb.g / 51) * 51,
            b: Math.round(rgb.b / 51) * 51
        }
    },

    rgba2websafe: function(rgba){
        return {
            r: Math.round(rgba.r / 51) * 51,
            g: Math.round(rgba.g / 51) * 51,
            b: Math.round(rgba.b / 51) * 51,
            a: rgba.a
        }
    },

    hex2websafe: function(hex){
        return this.rgb2hex(this.rgb2websafe(this.toRGB(hex)));
    },

    hsv2websafe: function(hsv){
        return this.rgb2hsv(this.rgb2websafe(this.toRGB(hsv)));
    },

    hsl2websafe: function(hsl){
        return this.hsv2hsl(this.rgb2hsv(this.rgb2websafe(this.toRGB(hsl))));
    },

    cmyk2websafe: function(cmyk){
        return this.rgb2cmyk(this.rgb2websafe(this.cmyk2rgb(cmyk)));
    },

    websafe: function(color){
        if (this.isHEX(color)) return this.hex2websafe(color);
        if (this.isRGB(color)) return this.rgb2websafe(color);
        if (this.isRGBA(color)) return this.rgba2websafe(color);
        if (this.isHSV(color)) return this.hsv2websafe(color);
        if (this.isHSL(color)) return this.hsl2websafe(color);
        if (this.isCMYK(color)) return this.cmyk2websafe(color);

        return color;
    },

    is: function(color){
        if (this.isHEX(color)) return this.TYPES.HEX;
        if (this.isRGB(color)) return this.TYPES.RGB;
        if (this.isRGBA(color)) return this.TYPES.RGBA;
        if (this.isHSV(color)) return this.TYPES.HSV;
        if (this.isHSL(color)) return this.TYPES.HSL;
        if (this.isCMYK(color)) return this.TYPES.CMYK;

        return this.TYPES.UNKNOWN;
    },

    toRGB: function(color){
        if (this.isHSV(color)) return this.hsv2rgb(color);
        if (this.isHSL(color)) return this.hsv2rgb(this.hsl2hsv(color));
        if (this.isRGB(color)) return color;
        if (this.isHEX(color)) return this.hex2rgb(color);
        if (this.isCMYK(color)) return this.cmyk2rgb(color);

        throw new Error("Unknown color format!");
    },

    toRGBA: function(color, alpha){
        var result = this.toRGB(color);
        result.a = alpha || 1;
        return result;
    },

    toHSV: function(color){
        return this.rgb2hsv(this.toRGB(color));
    },

    toHSL: function(color){
        return this.hsv2hsl(this.rgb2hsv(this.toRGB(color)));
    },

    toHSLA: function(color, alpha){
        var hsla;
        hsla = this.hsv2hsl(this.rgb2hsv(this.toRGB(color)));
        hsla.a = alpha || this.options.alpha;
        return hsla;
    },

    toHEX: function(color){
        return this.rgb2hex(this.toRGB(color));
    },

    toCMYK: function(color){
        return this.rgb2cmyk(this.toRGB(color));
    },

    toHexString: function(color){
        return this.toHEX(color);
    },

    toHsvString: function(color){
        var hsv = this.toHSV(color);
        return "hsv("+[hsv.h, hsv.s, hsv.v].join(",")+")";
    },

    toHslString: function(color){
        var hsl = this.toHSL(color);
        return "hsl("+[Math.round(hsl.h), Math.round(hsl.s * 100) + "%" , Math.round(hsl.l * 100) + "%"].join(",")+")";
    },

    toHslaString: function(color){
        var hsl = this.toHSLA(color);
        return "hsl("+[Math.round(hsl.h), Math.round(hsl.s * 100) + "%" , Math.round(hsl.l * 100) + "%", hsl.a].join(",")+")";
    },

    toCmykString: function(color){
        var cmyk = this.toCMYK(color);
        return "cmyk("+[cmyk.c, cmyk.m, cmyk.y, cmyk.k].join(",")+")";
    },

    toRgbString: function(color){
        var rgb = this.toRGB(color);
        return "rgb("+[rgb.r, rgb.g, rgb.b].join(",")+")";
    },

    toRgbaString: function(color){
        var rgb = this.toRGBA(color);
        return "rgba("+[rgb.r, rgb.g, rgb.b, rgb.a].join(",")+")";
    },

    toString: function(color){
        if (this.isHEX(color)) return this.toHexString(color);
        if (this.isRGB(color)) return this.toRgbString(color);
        if (this.isRGBA(color)) return this.toRgbaString(color);
        if (this.isHSV(color)) return this.toHsvString(color);
        if (this.isHSL(color)) return this.toHslString(color);
        if (this.isHSLA(color)) return this.toHslaString(color);
        if (this.isCMYK(color)) return this.toCmykString(color);

        throw new Error("Unknown color format!");
    },

    grayscale: function(color, output){
        output = output || "hex";
        var rgb = this.toRGB(color);
        var gray = Math.round(rgb.r * .2125 + rgb.g * .7154 + rgb.b * .0721);
        var mono = {
            r: gray,
            g: gray,
            b: gray
        };
        return this["rgb2"+output](mono);
    },

    darken: function(color, amount){
        if (amount === undefined) {
            amount = 10;
        }
        return this.lighten(color, -1 * Math.abs(amount));
    },

    lighten: function(color, amount){
        var type, res, alpha = 1, ring = amount > 0;

        var calc = function(_color, _amount){
            var col = _color.slice(1);

            var num = parseInt(col, 16);
            var r = (num >> 16) + _amount;

            if (r > 255) r = 255;
            else if  (r < 0) r = 0;

            var b = ((num >> 8) & 0x00FF) + _amount;

            if (b > 255) b = 255;
            else if  (b < 0) b = 0;

            var g = (num & 0x0000FF) + _amount;

            if (g > 255) g = 255;
            else if (g < 0) g = 0;

            res = "#" + (g | (b << 8) | (r << 16)).toString(16);
            return res;
        };

        if (amount === undefined) {
            amount = 10;
        }

        type = this.is(color);

        if (type === this.TYPES.RGBA) {
            alpha = color.a;
        }

        do {
            res = calc(this.toHEX(color), amount);
            ring ? amount-- : amount++;
        } while (res.length < 7);

        switch (type) {
            case "rgb": return this.toRGB(res);
            case "rgba": return this.toRGBA(res, alpha);
            case "hsv": return this.toHSV(res);
            case "hsl": return this.toHSL(res);
            case "cmyk": return this.toCMYK(res);
            default: return res;
        }
    },

    isDark: function(color){
        var rgb = this.toRGB(color);
        var YIQ = (
            ( rgb.r * 299 ) +
            ( rgb.g * 587 ) +
            ( rgb.b * 114 )
        ) / 1000;
        return ( YIQ < 128 )
    },

    isLight: function(hex){
        return !this.isDark(hex);
    },

    isHSV: function(val){
        return Utils.isObject(val) && "h" in val && "s" in val && "v" in val;
    },

    isHSL: function(val){
        return Utils.isObject(val) && "h" in val && "s" in val && "l" in val;
    },

    isHSLA: function(val){
        return Utils.isObject(val) && "h" in val && "s" in val && "l" in val && "a" in val;
    },

    isRGB: function(val){
        return Utils.isObject(val) && "r" in val && "g" in val && "b" in val;
    },

    isRGBA: function(val){
        return Utils.isObject(val) && "r" in val && "g" in val && "b" in val && "a" in val;
    },

    isCMYK: function(val){
        return Utils.isObject(val) && "c" in val && "m" in val && "y" in val && "k" in val;
    },

    isHEX: function(val){
        return /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(val);
    },

    isColor: function(color){
        return this.isHEX(color) || this.isRGB(color) || this.isRGBA(color) || this.isHSV(color) || this.isHSL(color) || this.isCMYK(color);
    },

    hueShift: function(h, s){
        h+=s;
        while (h >= 360.0) h -= 360.0;
        while (h < 0.0) h += 360.0;
        return h;
    },

    getScheme: function(color, name, format, options){
        this.options = $.extend( {}, this.options, options );

        var i;
        var scheme = [];
        var hsv;
        var that = this;

        hsv = this.toHSV(color);

        if (this.isHSV(hsv) === false) {
            console.log("The value is a not supported color format!");
            return false;
        }

        function convert(source, format) {
            var result = [];
            var o = that.options;
            switch (format) {
                case "hex": result = source.map(function(v){return Colors.toHEX(v);}); break;
                case "rgb": result = source.map(function(v){return Colors.toRGB(v);}); break;
                case "rgba": result = source.map(function(v){return Colors.toRGBA(v, o.alpha);}); break;
                case "hsl": result = source.map(function(v){return Colors.toHSL(v);}); break;
                case "cmyk": result = source.map(function(v){return Colors.toCMYK(v);}); break;
                default: result = source;
            }

            return result;
        }

        function clamp( num, min, max ){
            return Math.max( min, Math.min( num, max ));
        }

        function toRange(a, b, c){
            return a < b ? b : ( a > c ? c : a);
        }

        var rgb, h = hsv.h, s = hsv.s, v = hsv.v;
        var o = this.options;

        switch (name) {
            case "monochromatic":
            case "mono":
                if (o.algorithm === 1) {

                    rgb = this.hsv2rgb(hsv);
                    rgb.r = toRange(Math.round(rgb.r + (255 - rgb.r) * o.tint1), 0, 255);
                    rgb.g = toRange(Math.round(rgb.g + (255 - rgb.g) * o.tint1), 0, 255);
                    rgb.b = toRange(Math.round(rgb.b + (255 - rgb.b) * o.tint1), 0, 255);
                    scheme.push(this.rgb2hsv(rgb));

                    rgb = this.hsv2rgb(hsv);
                    rgb.r = toRange(Math.round(rgb.r + (255 - rgb.r) * o.tint2), 0, 255);
                    rgb.g = toRange(Math.round(rgb.g + (255 - rgb.g) * o.tint2), 0, 255);
                    rgb.b = toRange(Math.round(rgb.b + (255 - rgb.b) * o.tint2), 0, 255);
                    scheme.push(this.rgb2hsv(rgb));

                    scheme.push(hsv);

                    rgb = this.hsv2rgb(hsv);
                    rgb.r = toRange(Math.round(rgb.r * o.shade1), 0, 255);
                    rgb.g = toRange(Math.round(rgb.g * o.shade1), 0, 255);
                    rgb.b = toRange(Math.round(rgb.b * o.shade1), 0, 255);
                    scheme.push(this.rgb2hsv(rgb));

                    rgb = this.hsv2rgb(hsv);
                    rgb.r = toRange(Math.round(rgb.r * o.shade2), 0, 255);
                    rgb.g = toRange(Math.round(rgb.g * o.shade2), 0, 255);
                    rgb.b = toRange(Math.round(rgb.b * o.shade2), 0, 255);
                    scheme.push(this.rgb2hsv(rgb));
                } else if (o.algorithm === 2) {
                    scheme.push(hsv);
                    for(i = 1; i <= o.distance; i++) {
                        v = clamp(v - o.step, 0, 1);
                        s = clamp(s - o.step, 0, 1);
                        scheme.push({h: h, s: s, v: v});
                    }
                } else if (o.algorithm === 3) {
                    scheme.push(hsv);
                    for(i = 1; i <= o.distance; i++) {
                        v = clamp(v - o.step, 0, 1);
                        scheme.push({h: h, s: s, v: v});
                    }
                } else {
                    v = clamp(hsv.v + o.step * 2, 0, 1);
                    scheme.push({h: h, s: s, v: v});

                    v = clamp(hsv.v + o.step, 0, 1);
                    scheme.push({h: h, s: s, v: v});

                    scheme.push(hsv); s = hsv.s; v = hsv.v;

                    v = clamp(hsv.v - o.step, 0, 1);
                    scheme.push({h: h, s: s, v: v});

                    v = clamp(hsv.v - o.step * 2, 0, 1);
                    scheme.push({h: h, s: s, v: v});
                }
                break;

            case 'complementary':
            case 'complement':
            case 'comp':
                scheme.push(hsv);

                h = this.hueShift(hsv.h, 180.0);
                scheme.push({h: h, s: s, v: v});
                break;

            case 'double-complementary':
            case 'double-complement':
            case 'double':
                scheme.push(hsv);

                h = this.hueShift(h, 180.0);
                scheme.push({h: h, s: s, v: v});

                h = this.hueShift(h, o.angle);
                scheme.push({h: h, s: s, v: v});

                h = this.hueShift(h, 180.0);
                scheme.push({h: h, s: s, v: v});

                break;

            case 'analogous':
            case 'analog':

                h = this.hueShift(h, o.angle);
                scheme.push({h: h, s: s, v: v});

                scheme.push(hsv);

                h = this.hueShift(hsv.h, 0.0 - o.angle);
                scheme.push({h: h, s: s, v: v});

                break;

            case 'triadic':
            case 'triad':
                scheme.push(hsv);
                for ( i = 1; i < 3; i++ ) {
                    h = this.hueShift(h, 120.0);
                    scheme.push({h: h, s: s, v: v});
                }
                break;

            case 'tetradic':
            case 'tetra':
                scheme.push(hsv);

                h = this.hueShift(hsv.h, 180.0);
                scheme.push({h: h, s: s, v: v});

                h = this.hueShift(hsv.h, -1 * o.angle);
                scheme.push({h: h, s: s, v: v});

                h = this.hueShift(h, 180.0);
                scheme.push({h: h, s: s, v: v});

                break;

            case 'square':
                scheme.push(hsv);
                for ( i = 1; i < 4; i++ ) {
                    h = this.hueShift(h, 90.0);
                    scheme.push({h: h, s: s, v: v});
                }
                break;

            case 'split-complementary':
            case 'split-complement':
            case 'split':
                h = this.hueShift(h, 180.0 - o.angle);
                scheme.push({h: h, s: s, v: v});

                scheme.push(hsv);

                h = this.hueShift(hsv.h, 180.0 + o.angle);
                scheme.push({h: h, s: s, v: v});
                break;

            default: console.log("Unknown scheme name");
        }

        return convert(scheme, format);
    }
};

Metro['colors'] = Colors.init();

var Locales = {
    'en-US': {
        "calendar": {
            "months": [
                "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December",
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ],
            "days": [
                "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday",
                "Su", "Mo", "Tu", "We", "Th", "Fr", "Sa",
                "Sun", "Mon", "Tus", "Wen", "Thu", "Fri", "Sat"
            ],
            "time": {
                "days": "DAYS",
                "hours": "HOURS",
                "minutes": "MINS",
                "seconds": "SECS",
                "month": "MON",
                "day": "DAY",
                "year": "YEAR"
            }
        },
        "buttons": {
            "ok": "OK",
            "cancel": "Cancel",
            "done": "Done",
            "today": "Today",
            "now": "Now",
            "clear": "Clear",
            "help": "Help",
            "yes": "Yes",
            "no": "No",
            "random": "Random",
            "save": "Save",
            "reset": "Reset"
        }
    },
    
    'cn-ZH': {
        "calendar": {
            "months": [
                "一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月",
                "1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"
            ],
            "days": [
                "星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六",
                "日", "一", "二", "三", "四", "五", "六",
                "周日", "周一", "周二", "周三", "周四", "周五", "周六"
            ],
            "time": {
                "days": "天",
                "hours": "时",
                "minutes": "分",
                "seconds": "秒",
                "month": "月",
                "day": "日",
                "year": "年"
            }
        },
        "buttons": {
            "ok": "确认",
            "cancel": "取消",
            "done": "完成",
            "today": "今天",
            "now": "现在",
            "clear": "清除",
            "help": "帮助",
            "yes": "是",
            "no": "否",
            "random": "随机",
            "save": "保存",
            "reset": "重啟"
        }
    },
    
    
    'de-DE': {
        "calendar": {
            "months": [
                "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember",
                "Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"
            ],
            "days": [
                "Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag",
                "Sn", "Mn", "Di", "Mi", "Do", "Fr", "Sa",
                "Son", "Mon", "Die", "Mit", "Don", "Fre", "Sam"
            ],
            "time": {
                "days": "TAGE",
                "hours": "UHR",
                "minutes": "MIN",
                "seconds": "SEK"
            }
        },
        "buttons": {
            "ok": "OK",
            "cancel": "Abbrechen",
            "done": "Fertig",
            "today": "Heute",
            "now": "Jetzt",
            "clear": "Reinigen",
            "help": "Hilfe",
            "yes": "Ja",
            "no": "Nein",
            "random": "Zufällig",
            "save": "Sparen",
            "reset": "Zurücksetzen"
        }
    },

    'hu-HU': {
        "calendar": {
            "months": [
                'Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December',
                'Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún', 'Júl', 'Aug', 'Szep', 'Okt', 'Nov', 'Dec'
            ],
            "days": [
                'Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat',
                'V', 'H', 'K', 'Sz', 'Cs', 'P', 'Sz',
                'Vas', 'Hét', 'Ke', 'Sze', 'Csü', 'Pén', 'Szom'
            ],
            "time": {
                "days": "NAP",
                "hours": "ÓRA",
                "minutes": "PERC",
                "seconds": "MP"
            }
        },
        "buttons": {
            "ok": "OK",
            "cancel": "Mégse",
            "done": "Kész",
            "today": "Ma",
            "now": "Most",
            "clear": "Törlés",
            "help": "Segítség",
            "yes": "Igen",
            "no": "Nem",
            "random": "Véletlen",
            "save": "Mentés",
            "reset": "Visszaállítás"
        }
    },

    'ru-RU': {
        "calendar": {
            "months": [
                "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь",
                "Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"
            ],
            "days": [
                "Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота",
                "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб",
                "Вос", "Пон", "Вто", "Сре", "Чет", "Пят", "Суб"
            ],
            "time": {
                "days": "ДНИ",
                "hours": "ЧАСЫ",
                "minutes": "МИН",
                "seconds": "СЕК"
            }
        },
        "buttons": {
            "ok": "ОК",
            "cancel": "Отмена",
            "done": "Готово",
            "today": "Сегодня",
            "now": "Сейчас",
            "clear": "Очистить",
            "help": "Помощь",
            "yes": "Да",
            "no": "Нет",
            "random": "Случайно",
            "save": "Сохранить",
            "reset": "Сброс"
        }
    },

    'uk-UA': {
        "calendar": {
            "months": [
                "Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень",
                "Січ", "Лют", "Бер", "Кві", "Тра", "Чер", "Лип", "Сер", "Вер", "Жов", "Лис", "Гру"
            ],
            "days": [
                "Неділя", "Понеділок", "Вівторок", "Середа", "Четвер", "П’ятниця", "Субота",
                "Нд", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб",
                "Нед", "Пон", "Вiв", "Сер", "Чет", "Пят", "Суб"
            ],
            "time": {
                "days": "ДНІ",
                "hours": "ГОД",
                "minutes": "ХВИЛ",
                "seconds": "СЕК"
            }
        },
        "buttons": {
            "ok": "ОК",
            "cancel": "Відміна",
            "done": "Готово",
            "today": "Сьогодні",
            "now": "Зараз",
            "clear": "Очистити",
            "help": "Допомога",
            "yes": "Так",
            "no": "Ні",
            "random": "Випадково",
            "save": "Зберегти",
            "reset": "Скинути"
        }
    },

    'es-MX': {
        "calendar": {
            "months": [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre",
                "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"
            ],
            "days": [
                "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado",
                "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa",
                "Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"
            ],
            "time": {
                "days": "DÍAS",
                "hours": "HORAS",
                "minutes": "MINS",
                "seconds": "SEGS",
                "month": "MES",
                "day": "DÍA",
                "year": "AÑO"
            }
        },
        "buttons": {
            "ok": "Aceptar",
            "cancel": "Cancelar",
            "done": "Hecho",
            "today": "Hoy",
            "now": "Ahora",
            "clear": "Limpiar",
            "help": "Ayuda",
            "yes": "Si",
            "no": "No",
            "random": "Aleatorio",
            "save": "Salvar",
            "reset": "Reiniciar"
        }
    },

    'fr-FR': {
        "calendar": {
            "months": [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre",
                "Janv", "Févr", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"
            ],
            "days": [
                "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi",
                "De", "Du", "Ma", "Me", "Je", "Ve", "Sa",
                "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"
            ],
            "time": {
                "days": "JOURS",
                "hours": "HEURES",
                "minutes": "MINS",
                "seconds": "SECS",
                "month": "MOIS",
                "day": "JOUR",
                "year": "ANNEE"
            }
        },
        "buttons": {
            "ok": "OK",
            "cancel": "Annulé",
            "done": "Fait",
            "today": "Aujourd'hui",
            "now": "Maintenant",
            "clear": "Effacé",
            "help": "Aide",
            "yes": "Oui",
            "no": "Non",
            "random": "Aléatoire",
            "save": "Sauvegarder",
            "reset": "Réinitialiser"
        }
    },

    'it-IT': {
        "calendar": {
            "months": [
                "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre",
                "Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"
            ],
            "days": [
                "Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato",
                "Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa",
                "Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"
            ],
            "time": {
                "days": "GIORNI",
                "hours": "ORE",
                "minutes": "MIN",
                "seconds": "SEC",
                "month": "MESE",
                "day": "GIORNO",
                "year": "ANNO"
            }
        },
        "buttons": {
            "ok": "OK",
            "cancel": "Annulla",
            "done": "Fatto",
            "today": "Oggi",
            "now": "Adesso",
            "clear": "Cancella",
            "help": "Aiuto",
            "yes": "Sì",
            "no": "No",
            "random": "Random",
            "save": "Salvare",
            "reset": "Reset"
        }
    }
};

Metro['locales'] = Locales;


var hexcase = 0;
/* hex output format. 0 - lowercase; 1 - uppercase        */
var b64pad = "";
/* base-64 pad character. "=" for strict RFC compliance   */

function hex_md5(s) {
    return rstr2hex(rstr_md5(str2rstr_utf8(s)));
}
function b64_md5(s) {
    return rstr2b64(rstr_md5(str2rstr_utf8(s)));
}
function any_md5(s, e) {
    return rstr2any(rstr_md5(str2rstr_utf8(s)), e);
}
function hex_hmac_md5(k, d) {
    return rstr2hex(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d)));
}
function b64_hmac_md5(k, d) {
    return rstr2b64(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d)));
}
function any_hmac_md5(k, d, e) {
    return rstr2any(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d)), e);
}


/*
 * Calculate the MD5 of a raw string
 */
function rstr_md5(s) {
    return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
}

/*
 * Calculate the HMAC-MD5, of a key and some data (raw strings)
 */
function rstr_hmac_md5(key, data) {
    var bkey = rstr2binl(key);
    if (bkey.length > 16) bkey = binl_md5(bkey, key.length * 8);

    var ipad = new Array(16), opad = new Array(16);
    for (var i = 0; i < 16; i++) {
        ipad[i] = bkey[i] ^ 0x36363636;
        opad[i] = bkey[i] ^ 0x5C5C5C5C;
    }

    var hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
    return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
}

/*
 * Convert a raw string to a hex string
 */
function rstr2hex(input) {
    var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
    var output = "";
    var x;
    for (var i = 0; i < input.length; i++) {
        x = input.charCodeAt(i);
        output += hex_tab.charAt((x >>> 4) & 0x0F)
            + hex_tab.charAt(x & 0x0F);
    }
    return output;
}

/*
 * Convert a raw string to a base-64 string
 */
function rstr2b64(input) {
    var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
    var output = "";
    var len = input.length;
    for (var i = 0; i < len; i += 3) {
        var triplet = (input.charCodeAt(i) << 16)
            | (i + 1 < len ? input.charCodeAt(i + 1) << 8 : 0)
            | (i + 2 < len ? input.charCodeAt(i + 2) : 0);
        for (var j = 0; j < 4; j++) {
            if (i * 8 + j * 6 > input.length * 8) output += b64pad;
            else output += tab.charAt((triplet >>> 6 * (3 - j)) & 0x3F);
        }
    }
    return output;
}

/*
 * Convert a raw string to an arbitrary string encoding
 */
function rstr2any(input, encoding) {
    var divisor = encoding.length;
    var i, j, q, x, quotient;

    /* Convert to an array of 16-bit big-endian values, forming the dividend */
    var dividend = new Array(Math.ceil(input.length / 2));
    for (i = 0; i < dividend.length; i++) {
        dividend[i] = (input.charCodeAt(i * 2) << 8) | input.charCodeAt(i * 2 + 1);
    }

    /*
     * Repeatedly perform a long division. The binary array forms the dividend,
     * the length of the encoding is the divisor. Once computed, the quotient
     * forms the dividend for the next step. All remainders are stored for later
     * use.
     */
    var full_length = Math.ceil(input.length * 8 /
        (Math.log(encoding.length) / Math.log(2)));
    var remainders = new Array(full_length);
    for (j = 0; j < full_length; j++) {
        quotient = [];
        x = 0;
        for (i = 0; i < dividend.length; i++) {
            x = (x << 16) + dividend[i];
            q = Math.floor(x / divisor);
            x -= q * divisor;
            if (quotient.length > 0 || q > 0)
                quotient[quotient.length] = q;
        }
        remainders[j] = x;
        dividend = quotient;
    }

    /* Convert the remainders to the output string */
    var output = "";
    for (i = remainders.length - 1; i >= 0; i--)
        output += encoding.charAt(remainders[i]);

    return output;
}

/*
 * Encode a string as utf-8.
 * For efficiency, this assumes the input is valid utf-16.
 */
function str2rstr_utf8(input) {
    var output = "";
    var i = -1;
    var x, y;

    while (++i < input.length) {
        /* Decode utf-16 surrogate pairs */
        x = input.charCodeAt(i);
        y = i + 1 < input.length ? input.charCodeAt(i + 1) : 0;
        if (0xD800 <= x && x <= 0xDBFF && 0xDC00 <= y && y <= 0xDFFF) {
            x = 0x10000 + ((x & 0x03FF) << 10) + (y & 0x03FF);
            i++;
        }

        /* Encode output as utf-8 */
        if (x <= 0x7F)
            output += String.fromCharCode(x);
        else if (x <= 0x7FF)
            output += String.fromCharCode(0xC0 | ((x >>> 6 ) & 0x1F),
                0x80 | ( x & 0x3F));
        else if (x <= 0xFFFF)
            output += String.fromCharCode(0xE0 | ((x >>> 12) & 0x0F),
                0x80 | ((x >>> 6 ) & 0x3F),
                0x80 | ( x & 0x3F));
        else if (x <= 0x1FFFFF)
            output += String.fromCharCode(0xF0 | ((x >>> 18) & 0x07),
                0x80 | ((x >>> 12) & 0x3F),
                0x80 | ((x >>> 6 ) & 0x3F),
                0x80 | ( x & 0x3F));
    }
    return output;
}

/*
 * Convert a raw string to an array of little-endian words
 * Characters >255 have their high-byte silently ignored.
 */
function rstr2binl(input) {
    var i;
    var output = new Array(input.length >> 2);
    for (i = 0; i < output.length; i++)
        output[i] = 0;
    for (i = 0; i < input.length * 8; i += 8)
        output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32);
    return output;
}

/*
 * Convert an array of little-endian words to a string
 */
function binl2rstr(input) {
    var output = "";
    for (var i = 0; i < input.length * 32; i += 8)
        output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF);
    return output;
}

/*
 * Calculate the MD5 of an array of little-endian words, and a bit length.
 */
function binl_md5(x, len) {
    /* append padding */
    x[len >> 5] |= 0x80 << ((len) % 32);
    x[(((len + 64) >>> 9) << 4) + 14] = len;

    var a = 1732584193;
    var b = -271733879;
    var c = -1732584194;
    var d = 271733878;

    for (var i = 0; i < x.length; i += 16) {
        var olda = a;
        var oldb = b;
        var oldc = c;
        var oldd = d;

        a = md5_ff(a, b, c, d, x[i], 7, -680876936);
        d = md5_ff(d, a, b, c, x[i + 1], 12, -389564586);
        c = md5_ff(c, d, a, b, x[i + 2], 17, 606105819);
        b = md5_ff(b, c, d, a, x[i + 3], 22, -1044525330);
        a = md5_ff(a, b, c, d, x[i + 4], 7, -176418897);
        d = md5_ff(d, a, b, c, x[i + 5], 12, 1200080426);
        c = md5_ff(c, d, a, b, x[i + 6], 17, -1473231341);
        b = md5_ff(b, c, d, a, x[i + 7], 22, -45705983);
        a = md5_ff(a, b, c, d, x[i + 8], 7, 1770035416);
        d = md5_ff(d, a, b, c, x[i + 9], 12, -1958414417);
        c = md5_ff(c, d, a, b, x[i + 10], 17, -42063);
        b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162);
        a = md5_ff(a, b, c, d, x[i + 12], 7, 1804603682);
        d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101);
        c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290);
        b = md5_ff(b, c, d, a, x[i + 15], 22, 1236535329);

        a = md5_gg(a, b, c, d, x[i + 1], 5, -165796510);
        d = md5_gg(d, a, b, c, x[i + 6], 9, -1069501632);
        c = md5_gg(c, d, a, b, x[i + 11], 14, 643717713);
        b = md5_gg(b, c, d, a, x[i], 20, -373897302);
        a = md5_gg(a, b, c, d, x[i + 5], 5, -701558691);
        d = md5_gg(d, a, b, c, x[i + 10], 9, 38016083);
        c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335);
        b = md5_gg(b, c, d, a, x[i + 4], 20, -405537848);
        a = md5_gg(a, b, c, d, x[i + 9], 5, 568446438);
        d = md5_gg(d, a, b, c, x[i + 14], 9, -1019803690);
        c = md5_gg(c, d, a, b, x[i + 3], 14, -187363961);
        b = md5_gg(b, c, d, a, x[i + 8], 20, 1163531501);
        a = md5_gg(a, b, c, d, x[i + 13], 5, -1444681467);
        d = md5_gg(d, a, b, c, x[i + 2], 9, -51403784);
        c = md5_gg(c, d, a, b, x[i + 7], 14, 1735328473);
        b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734);

        a = md5_hh(a, b, c, d, x[i + 5], 4, -378558);
        d = md5_hh(d, a, b, c, x[i + 8], 11, -2022574463);
        c = md5_hh(c, d, a, b, x[i + 11], 16, 1839030562);
        b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556);
        a = md5_hh(a, b, c, d, x[i + 1], 4, -1530992060);
        d = md5_hh(d, a, b, c, x[i + 4], 11, 1272893353);
        c = md5_hh(c, d, a, b, x[i + 7], 16, -155497632);
        b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640);
        a = md5_hh(a, b, c, d, x[i + 13], 4, 681279174);
        d = md5_hh(d, a, b, c, x[i], 11, -358537222);
        c = md5_hh(c, d, a, b, x[i + 3], 16, -722521979);
        b = md5_hh(b, c, d, a, x[i + 6], 23, 76029189);
        a = md5_hh(a, b, c, d, x[i + 9], 4, -640364487);
        d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835);
        c = md5_hh(c, d, a, b, x[i + 15], 16, 530742520);
        b = md5_hh(b, c, d, a, x[i + 2], 23, -995338651);

        a = md5_ii(a, b, c, d, x[i], 6, -198630844);
        d = md5_ii(d, a, b, c, x[i + 7], 10, 1126891415);
        c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905);
        b = md5_ii(b, c, d, a, x[i + 5], 21, -57434055);
        a = md5_ii(a, b, c, d, x[i + 12], 6, 1700485571);
        d = md5_ii(d, a, b, c, x[i + 3], 10, -1894986606);
        c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523);
        b = md5_ii(b, c, d, a, x[i + 1], 21, -2054922799);
        a = md5_ii(a, b, c, d, x[i + 8], 6, 1873313359);
        d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744);
        c = md5_ii(c, d, a, b, x[i + 6], 15, -1560198380);
        b = md5_ii(b, c, d, a, x[i + 13], 21, 1309151649);
        a = md5_ii(a, b, c, d, x[i + 4], 6, -145523070);
        d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379);
        c = md5_ii(c, d, a, b, x[i + 2], 15, 718787259);
        b = md5_ii(b, c, d, a, x[i + 9], 21, -343485551);

        a = safe_add(a, olda);
        b = safe_add(b, oldb);
        c = safe_add(c, oldc);
        d = safe_add(d, oldd);
    }
    return [a, b, c, d];
}

/*
 * These functions implement the four basic operations the algorithm uses.
 */
function md5_cmn(q, a, b, x, s, t) {
    return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b);
}
function md5_ff(a, b, c, d, x, s, t) {
    return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
function md5_gg(a, b, c, d, x, s, t) {
    return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
function md5_hh(a, b, c, d, x, s, t) {
    return md5_cmn(b ^ c ^ d, a, b, x, s, t);
}
function md5_ii(a, b, c, d, x, s, t) {
    return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */
function safe_add(x, y) {
    var lsw = (x & 0xFFFF) + (y & 0xFFFF);
    var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
    return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left.
 */
function bit_rol(num, cnt) {
    return (num << cnt) | (num >>> (32 - cnt));
}


// window.md5 = {
//     hex: function(val){
//         return hex_md5(val);
//     },
//
//     b64: function(val){
//         return b64_md5(val);
//     },
//
//     any: function(s, e){
//         return any_md5(s, e);
//     },
//
//     hex_hmac: function(k, d){
//         return hex_hmac_md5(k, d);
//     },
//
//     b64_hmac: function(k, d){
//         return b64_hmac_md5(k, d);
//     },
//
//     any_hmac: function(k, d, e){
//         return any_hmac_md5(k, d, e);
//     }
// };

//$.Metro['md5'] = hex_md5;

var Utils = {
    isUrl: function (val) {
        return /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@\-\/]))?/.test(val);
    },

    isTag: function(val){
        return /^<\/?[\w\s="/.':;#-\/\?]+>/gi.test(val);
    },

    isColor: function (val) {
        return /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(val);
    },

    isEmbedObject: function(val){
        var embed = ["iframe", "object", "embed", "video"];
        var result = false;
        $.each(embed, function(i, v){
            'use strict';
            if (typeof val === "string" && val.toLowerCase() === v) {
                result = true;
            } else if (val.nodeType !== undefined && val.tagName.toLowerCase() === v) {
                result = true;
            }
        });
        return result;
    },

    isVideoUrl: function(val){
        return /youtu\.be|youtube|vimeo/gi.test(val);
    },

    isDate: function(val, format){
        var result;

        if (typeof val === "object" && Utils.isFunc(val['getMonth'])) {
            return true;
        }

        if (Utils.isValue(format)) {
            result = String(val).toDate(format);
        } else {
            result = String(new Date(val));
        }

        return result !== "Invalid Date";
    },

    isDateObject: function(v){
        return typeof v === 'object' && v['getMonth'] !== undefined;
    },

    isInt: function(n){
        return !isNaN(n) && +n % 1 === 0;
    },

    isFloat: function(n){
        return !isNaN(n) && +n % 1 !== 0;
    },

    isTouchDevice: function() {
        return (('ontouchstart' in window)
            || (navigator.MaxTouchPoints > 0)
            || (navigator.msMaxTouchPoints > 0));
    },

    isFunc: function(f){
        return Utils.isType(f, 'function');
    },

    isObject: function(o){
        return Utils.isType(o, 'object')
    },

    isArray: function(a){
        return Array.isArray(a);
    },

    isType: function(o, t){
        if (o === undefined || o === null) {
            return false;
        }

        if (typeof o === t) {
            return o;
        }

        if (Utils.isTag(o) || Utils.isUrl(o)) {
            return false;
        }

        if (typeof window[o] === t) {
            return window[o];
        }

        if (typeof o === 'string' && o.indexOf(".") === -1) {
            return false;
        }

        if (typeof o === 'string' && o.indexOf(" ") !== -1) {
            return false;
        }

        if (typeof o === 'string' && o.indexOf("(") !== -1) {
            return false;
        }

        if (typeof o === 'string' && o.indexOf("[") !== -1) {
            return false;
        }

        if (typeof o === "number" && t.toLowerCase() !== "number") {
            return false;
        }

        var ns = o.split(".");
        var i, context = window;

        for(i = 0; i < ns.length; i++) {
            context = context[ns[i]];
        }

        return typeof context === t ? context : false;
    },

    isMetroObject: function(el, type){
        var $el = $(el), el_obj = $el.data(type);
        if ($el.length === 0) {
            console.log(type + ' ' + el + ' not found!');
            return false;
        }

        if (el_obj === undefined) {
            console.log('Element not contain role '+ type +'! Please add attribute data-role="'+type+'" to element ' + el);
            return false;
        }

        return true;
    },

    isJQuery: function(el){
        return (typeof jQuery !== "undefined" && el instanceof jQuery);
    },

    isM4Q: function(el){
        return (typeof m4q !== "undefined" && el instanceof m4q);
    },

    isQ: function(el){
        return Utils.isJQuery(el) || Utils.isM4Q(el);
    },

    embedObject: function(val){
        return "<div class='embed-container'>" + $(val)[0].outerHTML + "</div>";
    },

    embedUrl: function(val){
        if (val.indexOf("youtu.be") !== -1) {
            val = "https://www.youtube.com/embed/" + val.split("/").pop();
        }
        return "<div class='embed-container'><iframe src='"+val+"'></iframe></div>";
    },

    secondsToTime: function(secs) {
        var hours = Math.floor(secs / (60 * 60));

        var divisor_for_minutes = secs % (60 * 60);
        var minutes = Math.floor(divisor_for_minutes / 60);

        var divisor_for_seconds = divisor_for_minutes % 60;
        var seconds = Math.ceil(divisor_for_seconds);

        return {
            "h": hours,
            "m": minutes,
            "s": seconds
        };
    },

    hex2rgba: function(hex, alpha){
        var c;
        alpha = isNaN(alpha) ? 1 : alpha;
        if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
            c= hex.substring(1).split('');
            if(c.length=== 3){
                c= [c[0], c[0], c[1], c[1], c[2], c[2]];
            }
            c= '0x'+c.join('');
            return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+alpha+')';
        }
        throw new Error('Hex2rgba error. Bad Hex value');
    },

    random: function(from, to){
        return Math.floor(Math.random()*(to-from+1)+from);
    },

    uniqueId: function () {
        "use strict";
        var d = new Date().getTime();
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    },

    elementId: function(prefix){
        return prefix+"-"+(new Date()).getTime()+Utils.random(1, 1000);
    },

    secondsToFormattedString: function(time){
        var sec_num = parseInt(time, 10);
        var hours   = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}

        return [hours, minutes, seconds].join(":");
    },

    callback: function(f, args, context){
        return Utils.exec(f, args, context);
    },

    func: function(f){
        return new Function("a", f);
    },

    exec: function(f, args, context){
        var result;
        if (f === undefined || f === null) {return false;}
        var func = Utils.isFunc(f);
        if (func === false) {
            func = Utils.func(f);
        }

        try {
            result = func.apply(context, args);
        } catch (err) {
            result = null;
            if (METRO_THROWS === true) {
                throw err;
            }
        }
        return result;
    },

    isOutsider: function(element) {
        var el = $(element);
        var rect;
        var clone = el.clone();

        clone.removeAttr("data-role").css({
            visibility: "hidden",
            position: "absolute",
            display: "block"
        });
        el.parent().append(clone);

        rect = clone[0].getBoundingClientRect();
        clone.remove();

        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    },

    inViewport: function(el){
        var rect = Utils.rect(el);

        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    },

    rect: function(el){
        if (typeof jQuery === "function" && el instanceof jQuery) {
            el = el[0];
        }

        return el.getBoundingClientRect();
    },

    getCursorPosition: function(el, e){
        var a = Utils.rect(el);
        return {
            x: Utils.pageXY(e).x - a.left - window.pageXOffset,
            y: Utils.pageXY(e).y - a.top - window.pageYOffset
        };
    },

    getCursorPositionX: function(el, e){
        return Utils.getCursorPosition(el, e).x;
    },

    getCursorPositionY: function(el, e){
        return Utils.getCursorPosition(el, e).y;
    },

    objectLength: function(obj){
        return Object.keys(obj).length;
    },

    percent: function(total, part, round_value){
        if (total === 0) {
            return 0;
        }
        var result = part * 100 / total;
        return round_value === true ? Math.round(result) : Math.round(result * 100) / 100;
    },

    camelCase: function(str){
        return str.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });
    },

    dashedName: function(str){
        return str.replace(/([A-Z])/g, function(u) { return "-" + u.toLowerCase(); });
    },

    objectShift: function(obj){
        var min = 0;
        $.each(obj, function(i){
            if (min === 0) {
                min = i;
            } else {
                if (min > i) {
                    min = i;
                }
            }
        });
        delete obj[min];

        return obj;
    },

    objectDelete: function(obj, key){
        if (obj[key] !== undefined) delete obj[key];
    },

    arrayDeleteByMultipleKeys: function(arr, keys){
        keys.forEach(function(ind){
            delete arr[ind];
        });
        return arr.filter(function(item){
            return item !== undefined;
        })
    },

    arrayDelete: function(arr, val){
        if (arr.indexOf(val) > -1) arr.splice(arr.indexOf(val), 1);
    },

    arrayDeleteByKey: function(arr, key){
        arr.splice(key, 1);
    },

    nvl: function(data, other){
        return data === undefined || data === null ? other : data;
    },

    objectClone: function(obj){
        var copy = {};
        for(var key in obj) {
            if (obj.hasOwnProperty(key)) {
                copy[key] = obj[key];
            }
        }
        return copy;
    },

    github: function(repo, callback){
        var that = this;
        $.ajax({
            url: 'https://api.github.com/repos/' + repo,
            dataType: 'jsonp'
        })
        .done(function(data){
            that.callback(callback, [data.data]);
        });
    },

    detectIE: function() {
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf('MSIE ');
        if (msie > 0) {
            // IE 10 or older => return version number
            return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
        }

        var trident = ua.indexOf('Trident/');
        if (trident > 0) {
            // IE 11 => return version number
            var rv = ua.indexOf('rv:');
            return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
        }

        var edge = ua.indexOf('Edge/');
        if (edge > 0) {
            // Edge (IE 12+) => return version number
            return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
        }

        // other browser
        return false;
    },

    detectChrome: function(){
        return /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
    },

    md5: function(s){
        return hex_md5(s);
    },

    encodeURI: function(str){
        return encodeURI(str).replace(/%5B/g, '[').replace(/%5D/g, ']');
    },

    pageHeight: function(){
        var body = document.body,
            html = document.documentElement;

        return Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
    },

    cleanPreCode: function(selector){
        var els = Array.prototype.slice.call(document.querySelectorAll(selector), 0);

        els.forEach(function(el){
            var txt = el.textContent
                .replace(/^[\r\n]+/, "")	// strip leading newline
                .replace(/\s+$/g, "");

            if (/^\S/gm.test(txt)) {
                el.textContent = txt;
                return;
            }

            var mat, str, re = /^[\t ]+/gm, len, min = 1e3;

            while (mat = re.exec(txt)) {
                len = mat[0].length;

                if (len < min) {
                    min = len;
                    str = mat[0];
                }
            }

            if (min === 1e3)
                return;

            el.textContent = txt.replace(new RegExp("^" + str, 'gm'), "");
        });
    },

    coords: function(element){
        var el = $(element)[0];
        var box = el.getBoundingClientRect();

        return {
            top: box.top + window.pageYOffset,
            left: box.left + window.pageXOffset
        };
    },

    positionXY: function(e, t){
        switch (t) {
            case 'client': return Utils.clientXY(e);
            case 'screen': return Utils.screenXY(e);
            case 'page': return Utils.pageXY(e);
            default: return {x: 0, y: 0}
        }
    },

    clientXY: function(e){
        return {
            x: e.changedTouches ? e.changedTouches[0].clientX : e.clientX,
            y: e.changedTouches ? e.changedTouches[0].clientY : e.clientY
        };
    },

    screenXY: function(e){
        return {
            x: e.changedTouches ? e.changedTouches[0].screenX : e.screenX,
            y: e.changedTouches ? e.changedTouches[0].screenY : e.screenY
        };
    },

    pageXY: function(e){
        return {
            x: e.changedTouches ? e.changedTouches[0].pageX : e.pageX,
            y: e.changedTouches ? e.changedTouches[0].pageY : e.pageY
        };
    },

    isRightMouse: function(e){
        return "which" in e ? e.which === 3 : "button" in e ? e.button === 2 : undefined;
    },

    hiddenElementSize: function(el, includeMargin){
        var clone = $(el).clone();
        clone.removeAttr("data-role").css({
            visibility: "hidden",
            position: "absolute",
            display: "block"
        });
        $("body").append(clone);

        if (includeMargin === undefined) {
            includeMargin = false;
        }

        var width = clone.outerWidth(includeMargin);
        var height = clone.outerHeight(includeMargin);
        clone.remove();
        return {
            width: width,
            height: height
        }
    },

    getStyle: function(element, pseudo){
        var el = $(element)[0];
        return window.getComputedStyle(el, pseudo);
    },

    getStyleOne: function(el, property){
        return Utils.getStyle(el).getPropertyValue(property);
    },

    getTransformMatrix: function(el, returnArray){
        var computedMatrix = Utils.getStyleOne(el, "transform");
        var a = computedMatrix
            .replace("matrix(", '')
            .slice(0, -1)
            .split(',');
        return returnArray !== true ? {
            a: a[0],
            b: a[1],
            c: a[2],
            d: a[3],
            tx: a[4],
            ty: a[5]
        } : a;
    },

    computedRgbToHex: function(rgb){
        var a = rgb.replace(/[^\d,]/g, '').split(',');
        var result = "#", i;

        for(i = 0; i < 3; i++) {
            var h = parseInt(a[i]).toString(16);
            result += h.length === 1 ? "0" + h : h;
        }

        return result;
    },

    computedRgbToRgba: function(rgb, alpha){
        var a = rgb.replace(/[^\d,]/g, '').split(',');
        if (alpha === undefined) {
            alpha = 1;
        }
        a.push(alpha);
        return "rgba("+a.join(",")+")";
    },

    computedRgbToArray: function(rgb){
        return rgb.replace(/[^\d,]/g, '').split(',');
    },

    hexColorToArray: function(hex){
        var c;
        if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
            c= hex.substring(1).split('');
            if(c.length === 3){
                c= [c[0], c[0], c[1], c[1], c[2], c[2]];
            }
            c= '0x'+c.join('');
            return [(c>>16)&255, (c>>8)&255, c&255];
        }
        return [0,0,0];
    },

    hexColorToRgbA: function(hex, alpha){
        var c;
        if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
            c= hex.substring(1).split('');
            if(c.length === 3){
                c= [c[0], c[0], c[1], c[1], c[2], c[2]];
            }
            c= '0x'+c.join('');
            return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255, alpha ? alpha : 1].join(',')+')';
        }
        return 'rgba(0,0,0,1)';
    },

    getInlineStyles: function(element){
        var i, l, styles = {}, el = $(element)[0];
        for (i = 0, l = el.style.length; i < l; i++) {
            var s = el.style[i];
            styles[s] = el.style[s];
        }

        return styles;
    },

    updateURIParameter: function(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        else {
            return uri + separator + key + "=" + value;
        }
    },

    getURIParameter: function(url, name){
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    },

    getLocales: function(){
        return Object.keys(Metro.locales);
    },

    addLocale: function(locale){
        Metro.locales = $.extend( {}, Metro.locales, locale );
    },

    strToArray: function(str, delimiter, type, format){
        var a;

        if (!Utils.isValue(delimiter)) {
            delimiter = ",";
        }

        if (!Utils.isValue(type)) {
            type = "string";
        }

        a = (""+str).split(delimiter);

        return a.map(function(s){
            var result;

            switch (type) {
                case "int":
                case "integer": result = parseInt(s); break;
                case "number":
                case "float": result = parseFloat(s); break;
                case "date": result = !Utils.isValue(format) ? new Date(s) : s.toDate(format); break;
                default: result = s.trim();
            }

            return result;
        })
    },

    aspectRatioH: function(width, a){
        if (a === "16/9") return width * 9 / 16;
        if (a === "21/9") return width * 9 / 21;
        if (a === "4/3") return width * 3 / 4;
    },

    aspectRatioW: function(height, a){
        if (a === "16/9") return height * 16 / 9;
        if (a === "21/9") return height * 21 / 9;
        if (a === "4/3") return height * 4 / 3;
    },

    valueInObject: function(obj, value){
        return Object.values(obj).indexOf(value) > -1;
    },

    keyInObject: function(obj, key){
        return Object.keys(obj).indexOf(key) > -1;
    },

    inObject: function(obj, key, val){
        return obj[key] !== undefined && obj[key] === val;
    },

    newCssSheet: function(media){
        var style = document.createElement("style");

        if (media !== undefined) {
            style.setAttribute("media", media);
        }

        style.appendChild(document.createTextNode(""));

        document.head.appendChild(style);

        return style.sheet;
    },

    addCssRule: function(sheet, selector, rules, index){
        if("insertRule" in sheet) {
            sheet.insertRule(selector + "{" + rules + "}", index);
        }
        else if("addRule" in sheet) {
            sheet.addRule(selector, rules, index);
        }
    },

    media: function(query){
        return window.matchMedia(query).matches
    },

    mediaModes: function(){
        return METRO_MEDIA;
    },

    mediaExist: function(media){
        return METRO_MEDIA.indexOf(media) > -1;
    },

    inMedia: function(media){
        return METRO_MEDIA.indexOf(media) > -1 && METRO_MEDIA.indexOf(media) === METRO_MEDIA.length - 1;
    },

    isValue: function(val){
        return val !== undefined && val !== null && val !== "";
    },

    isNull: function(val){
        return val === undefined || val === null;
    },

    isNegative: function(val){
        return parseFloat(val) < 0;
    },

    isPositive: function(val){
        return parseFloat(val) > 0;
    },

    isZero: function(val){
        return (parseFloat(val.toFixed(2))) === 0.00;
    },

    between: function(val, bottom, top, equals){
        return equals === true ? val >= bottom && val <= top : val > bottom && val < top;
    },

    parseMoney: function(val){
        return Number(parseFloat(val.replace(/[^0-9-.]/g, '')));
    },

    parseCard: function(val){
        return val.replace(/[^0-9]/g, '');
    },

    parsePhone: function(val){
        return Utils.parseCard(val);
    },

    isVisible: function(element){
        var el = $(element)[0];
        return Utils.getStyleOne(el, "display") !== "none" && Utils.getStyleOne(el, "visibility") !== "hidden" && el.offsetParent !== null;
    },

    parseNumber: function(val, thousand, decimal){
        return val.replace(new RegExp('\\'+thousand, "g"), "").replace(new RegExp('\\'+decimal, 'g'), ".");
    },

    nearest: function(val, precision, down){
        val /= precision;
        val = Math[down === true ? 'floor' : 'ceil'](val) * precision;
        return val;
    },

    bool: function(value){
        switch(value){
            case true:
            case "true":
            case 1:
            case "1":
            case "on":
            case "yes":
                return true;
            default:
                return false;
        }
    },

    copy: function(element){
        var body = document.body, range, sel;
        var el = $(element)[0];

        if (document.createRange && window.getSelection) {
            range = document.createRange();
            sel = window.getSelection();
            sel.removeAllRanges();
            try {
                range.selectNodeContents(el);
                sel.addRange(range);
            } catch (e) {
                range.selectNode(el);
                sel.addRange(range);
            }
        } else if (body.createTextRange) {
            range = body.createTextRange();
            range.moveToElementText(el);
            range.select();
        }

        document.execCommand("Copy");

        if (window.getSelection) {
            if (window.getSelection().empty) {  // Chrome
                window.getSelection().empty();
            } else if (window.getSelection().removeAllRanges) {  // Firefox
                window.getSelection().removeAllRanges();
            }
        } else if (document.selection) {  // IE?
            document.selection.empty();
        }
    },

    isLocalhost: function(){
        return window.location.hostname === 'localhost' ||
            window.location.hostname === '[::1]' ||
            window.location.hostname.match(
                /^127(?:\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/
            );
    },

    formData: function(f){
        var form = $(f)[0];
        var i, j, q = {};

        if (!form || form.nodeName !== "FORM") {
            return;
        }

        for (i = form.elements.length - 1; i >= 0; i = i - 1) {
            if (form.elements[i].name === "") {
                continue;
            }
            switch (form.elements[i].nodeName) {
                case 'INPUT':
                    switch (form.elements[i].type) {
                        case 'text':
                        case 'hidden':
                        case 'password':
                        case 'button':
                        case 'reset':
                        case 'submit':
                            q[form.elements[i].name] = form.elements[i].value;
                            break;
                        case 'checkbox':
                        case 'radio':
                            if (form.elements[i].checked) {
                                q[form.elements[i].name] = form.elements[i].value;
                            }
                            break;
                    }
                    break;
                case 'file':
                    break;
                case 'TEXTAREA':
                    q[form.elements[i].name] = form.elements[i].value;
                    break;
                case 'SELECT':
                    switch (form.elements[i].type) {
                        case 'select-one':
                            q[form.elements[i].name] = form.elements[i].value;
                            break;
                        case 'select-multiple':
                            q[form.elements[i].name] = [];
                            for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                                if (form.elements[i].options[j].selected) {
                                    q[form.elements[i].name].push(form.elements[i].options[j].value);
                                }
                            }
                            break;
                    }
                    break;
                case 'BUTTON':
                    switch (form.elements[i].type) {
                        case 'reset':
                        case 'submit':
                        case 'button':
                            q[form.elements[i].name] = form.elements[i].value;
                            break;
                    }
                    break;
            }
        }
        return q;
    }
};

Metro['utils'] = Utils;

var ButtonGroupDefaultConfig = {
    targets: "button",
    clsActive: "active",
    requiredButton: false,
    mode: Metro.groupMode.ONE,
    onButtonClick: Metro.noop,
    onButtonsGroupCreate: Metro.noop
};

Metro.buttonGroupSetup = function(options){
    ButtonGroupDefaultConfig = $.extend({}, ButtonGroupDefaultConfig, options);
};

if (typeof window.metroButtonGroupSetup !== undefined) {
    Metro.buttonGroupSetup(window.metroButtonGroupSetup);
}

var ButtonGroup = {
    init: function( options, elem ) {
        this.options = $.extend( {}, ButtonGroupDefaultConfig, options );
        this.elem  = elem;
        this.element = $(elem);
        this.active = null;

        this._setOptionsFromDOM();
        this._create();

        return this;
    },

    _setOptionsFromDOM: function(){
        var that = this, element = this.element, o = this.options;

        $.each(element.data(), function(key, value){
            if (key in o) {
                try {
                    o[key] = JSON.parse(value);
                } catch (e) {
                    o[key] = value;
                }
            }
        });
    },

    _create: function(){
        var that = this, element = this.element, o = this.options;

        this._createGroup();
        this._createEvents();

        Utils.exec(o.onButtonsGroupCreate, [element]);
        element.fire("buttongroupcreate");
    },

    _createGroup: function(){
        var that = this, element = this.element, o = this.options;
        var cls, buttons, buttons_active, id = Utils.elementId("button-group");

        if (element.attr("id") === undefined) {
            element.attr("id", id);
        }

        element.addClass("button-group");

        buttons = element.find( o.targets );
        buttons_active = element.find( "." + o.clsActive );

        if (o.mode === Metro.groupMode.ONE && buttons_active.length === 0 && o.requiredButton === true) {
            $(buttons[0]).addClass(o.clsActive);
        }

        if (o.mode === Metro.groupMode.ONE && buttons_active.length > 1) {
            buttons.removeClass(o.clsActive);
            $(buttons[0]).addClass(o.clsActive);
        }

        element.find( "." + o.clsActive ).addClass("js-active");
    },

    _createEvents: function(){
        var that = this, element = this.element, o = this.options;

        element.on(Metro.events.click, o.targets, function(){
            var el = $(this);

            Utils.exec(o.onButtonClick, [el], this);
            element.fire("buttonclick", {
                button: this
            });

            if (o.mode === Metro.groupMode.ONE && el.hasClass(o.clsActive)) {
                return ;
            }

            if (o.mode === Metro.groupMode.ONE) {
                element.find(o.targets).removeClass(o.clsActive).removeClass("js-active");
                el.addClass(o.clsActive).addClass("js-active");
            } else {
                el.toggleClass(o.clsActive).toggleClass("js-active");
            }

        });
    },

    changeAttribute: function(attributeName){

    },

    destroy: function(){
        var element = this.element, o = this.options;
        element.off(Metro.events.click, o.targets);
        element.find(o.targets).removeClass(o.clsActive).removeClass("js-active");
    }

};

Metro.plugin('buttongroup', ButtonGroup);

var CalendarDefaultConfig = {
    dayBorder: false,
    excludeDay: null,
    prevMonthIcon: "<span class='default-icon-chevron-left'></span>",
    nextMonthIcon: "<span class='default-icon-chevron-right'></span>",
    prevYearIcon: "<span class='default-icon-chevron-left'></span>",
    nextYearIcon: "<span class='default-icon-chevron-right'></span>",
    compact: false,
    wide: false,
    widePoint: null,
    pickerMode: false,
    show: null,
    locale: METRO_LOCALE,
    weekStart: METRO_WEEK_START,
    outside: true,
    buttons: 'cancel, today, clear, done',
    yearsBefore: 100,
    yearsAfter: 100,
    headerFormat: "%A, %b %e",
    showHeader: true,
    showFooter: true,
    showTimeField: true,
    showWeekNumber: false,
    clsCalendar: "",
    clsCalendarHeader: "",
    clsCalendarContent: "",
    clsCalendarFooter: "",
    clsCalendarMonths: "",
    clsCalendarYears: "",
    clsToday: "",
    clsSelected: "",
    clsExcluded: "",
    clsCancelButton: "",
    clsTodayButton: "",
    clsClearButton: "",
    clsDoneButton: "",
    isDialog: false,
    ripple: false,
    rippleColor: "#cccccc",
    exclude: null,
    preset: null,
    minDate: null,
    maxDate: null,
    weekDayClick: false,
    weekNumberClick: false,
    multiSelect: false,
    special: null,
    format: METRO_DATE_FORMAT,
    inputFormat: null,
    onCancel: Metro.noop,
    onToday: Metro.noop,
    onClear: Metro.noop,
    onDone: Metro.noop,
    onDayClick: Metro.noop,
    onDayDraw: Metro.noop,
    onWeekDayClick: Metro.noop,
    onWeekNumberClick: Metro.noop,
    onMonthChange: Metro.noop,
    onYearChange: Metro.noop,
    onCalendarCreate: Metro.noop
};

Metro.calendarSetup = function (options) {
    CalendarDefaultConfig = $.extend({}, CalendarDefaultConfig, options);
};

if (typeof window.metroCalendarSetup !== undefined) {
    Metro.calendarSetup(window.metroCalendarSetup);
}

var Calendar = {
    init: function( options, elem ) {
        this.options = $.extend( {}, CalendarDefaultConfig, options );
        this.elem  = elem;
        this.element = $(elem);
        this.today = new Date();
        this.today.setHours(0,0,0,0);
        this.show = new Date();
        this.show.setHours(0,0,0,0);
        this.current = {
            year: this.show.getFullYear(),
            month: this.show.getMonth(),
            day: this.show.getDate()
        };
        this.preset = [];
        this.selected = [];
        this.exclude = [];
        this.special = [];
        this.excludeDay = [];
        this.min = null;
        this.max = null;
        this.locale = null;
        this.minYear = this.current.year - this.options.yearsBefore;
        this.maxYear = this.current.year + this.options.yearsAfter;
        this.offset = (new Date()).getTimezoneOffset() / 60 + 1;

        this._setOptionsFromDOM();
        this._create();

        return this;
    },

    _setOptionsFromDOM: function(){
        var element = this.element, o = this.options;

        $.each(element.data(), function(key, value){
            if (key in o) {
                try {
                    o[key] = JSON.parse(value);
                } catch (e) {
                    o[key] = value;
                }
            }
        });
    },

    _create: function(){
        var that = this, element = this.element, o = this.options;

        element.html("").addClass("calendar " + (o.compact === true ? "compact" : "")).addClass(o.clsCalendar);

        if (o.dayBorder === true) {
            element.addClass("day-border");
        }

        if (Utils.isValue(o.excludeDay)) {
            this.excludeDay = (""+o.excludeDay).toArray(",", "int");
        }

        if (Utils.isValue(o.preset)) {
            this._dates2array(o.preset, 'selected');
        }

        if (Utils.isValue(o.exclude)) {
            this._dates2array(o.exclude, 'exclude');
        }

        if (Utils.isValue(o.special)) {
            this._dates2array(o.special, 'special');
        }

        if (o.buttons !== false) {
            if (Array.isArray(o.buttons) === false) {
                o.buttons = o.buttons.split(",").map(function(item){
                    return item.trim();
                });
            }
        }

        if (o.minDate !== null && Utils.isDate(o.minDate, o.inputFormat)) {
            this.min = Utils.isValue(o.inputFormat) ? o.minDate.toDate(o.inputFormat) : (new Date(o.minDate));
        }

        if (o.maxDate !== null && Utils.isDate(o.maxDate, o.inputFormat)) {
            this.max = Utils.isValue(o.inputFormat) ? o.maxDate.toDate(o.inputFormat) : (new Date(o.maxDate));
        }

        if (o.show !== null && Utils.isDate(o.show, o.inputFormat)) {
            this.show = Utils.isValue(o.inputFormat) ? o.show.toDate(o.inputFormat) : (new Date(o.show));
            this.show.setHours(0,0,0,0);
            this.current = {
                year: this.show.getFullYear(),
                month: this.show.getMonth(),
                day: this.show.getDate()
            }
        }

        this.locale = Metro.locales[o.locale] !== undefined ? Metro.locales[o.locale] : Metro.locales["en-US"];

        this._drawCalendar();
        this._createEvents();

        if (o.wide === true) {
            element.addClass("calendar-wide");
        } else {
            if (!Utils.isNull(o.widePoint) && Utils.mediaExist(o.widePoint)) {
                element.addClass("calendar-wide");
            }
        }


        if (o.ripple === true && Utils.isFunc(element.ripple) !== false) {
            element.ripple({
                rippleTarget: ".button, .prev-month, .next-month, .prev-year, .next-year, .day",
                rippleColor: this.options.rippleColor
            });
        }

        Utils.exec(this.options.onCalendarCreate, [this.element]);
        element.fire("calendarcreate");
    },

    _dates2array: function(val, category){
        var that = this, o = this.options;
        var dates;

        if (Utils.isNull(val)) {
            return ;
        }

        dates = typeof val === 'string' ? Utils.strToArray(val) : val;

        $.each(dates, function(){
            var _d;

            if (!Utils.isDateObject(this)) {
                _d = Utils.isValue(o.inputFormat) ? this.toDate(o.inputFormat) : new Date(this);
                if (Utils.isDate(_d) === false) {
                    return ;
                }
                _d.setHours(0,0,0,0);
            } else {
                _d = this;
            }

            that[category].push(_d.getTime());
        });
    },

    _createEvents: function(){
        var that = this, element = this.element, o = this.options;

        $(window).on(Metro.events.resize, function(){
            if (o.wide !== true) {
                if (!Utils.isNull(o.widePoint) && Utils.mediaExist(o.widePoint)) {
                    element.addClass("calendar-wide");
                } else {
                    element.removeClass("calendar-wide");
                }
            }
        });

        element.on(Metro.events.click, ".prev-month, .next-month, .prev-year, .next-year", function(e){
            var new_date, el = $(this);

            if (el.hasClass("prev-month")) {
                new_date = new Date(that.current.year, that.current.month - 1, 1);
                if (new_date.getFullYear() < that.minYear) {
                    return ;
                }
            }
            if (el.hasClass("next-month")) {
                new_date = new Date(that.current.year, that.current.month + 1, 1);
                if (new_date.getFullYear() > that.maxYear) {
                    return ;
                }
            }
            if (el.hasClass("prev-year")) {
                new_date = new Date(that.current.year - 1, that.current.month, 1);
                if (new_date.getFullYear() < that.minYear) {
                    return ;
                }
            }
            if (el.hasClass("next-year")) {
                new_date = new Date(that.current.year + 1, that.current.month, 1);
                if (new_date.getFullYear() > that.maxYear) {
                    return ;
                }
            }

            that.current = {
                year: new_date.getFullYear(),
                month: new_date.getMonth(),
                day: new_date.getDate()
            };
            setTimeout(function(){
                that._drawContent();
                if (el.hasClass("prev-month") || el.hasClass("next-month")) {
                    Utils.exec(o.onMonthChange, [that.current, element], element[0]);
                    element.fire("monthchange", {
                        current: that.current
                    });
                }
                if (el.hasClass("prev-year") || el.hasClass("next-year")) {
                    Utils.exec(o.onYearChange, [that.current, element], element[0]);
                    element.fire("yearchange", {
                        current: that.current
                    });
                }
            }, o.ripple ? 300 : 1);

            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, ".button.today", function(e){
            that.toDay();
            Utils.exec(o.onToday, [that.today, element]);
            element.fire("today", {
                today: that.today
            });

            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, ".button.clear", function(e){
            that.selected = [];
            that._drawContent();
            Utils.exec(o.onClear, [element]);
            element.fire("clear");

            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, ".button.cancel", function(e){
            that._drawContent();
            Utils.exec(o.onCancel, [element]);
            element.fire("cancel");

            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, ".button.done", function(e){
            that._drawContent();
            Utils.exec(o.onDone, [that.selected, element]);
            element.fire("done");

            e.preventDefault();
            e.stopPropagation();
        });

        if (o.weekDayClick === true) {
            element.on(Metro.events.click, ".week-days .day", function (e) {
                var day, index, days;

                day = $(this);
                index = day.index();

                if (o.multiSelect === true) {
                    days = o.outside === true ? element.find(".days-row .day:nth-child(" + (index + 1) + ")") : element.find(".days-row .day:not(.outside):nth-child(" + (index + 1) + ")");
                    $.each(days, function () {
                        var d = $(this);
                        var dd = d.data('day');

                        if (d.hasClass("disabled") || d.hasClass("excluded")) return;

                        if (!that.selected.contains(dd))
                            that.selected.push(dd);
                        d.addClass("selected").addClass(o.clsSelected);
                    });
                }

                Utils.exec(o.onWeekDayClick, [that.selected, day], element[0]);
                element.fire("weekdayclick", {
                    day: day,
                    selected: that.selected
                });

                e.preventDefault();
                e.stopPropagation();
            });
        }

        if (o.weekNumberClick) {
            element.on(Metro.events.click, ".days-row .week-number", function (e) {
                var weekNumElement, weekNumber, days;

                weekNumElement = $(this);
                weekNumber = weekNumElement.text();

                if (o.multiSelect === true) {
                    days = $(this).siblings(".day");
                    $.each(days, function () {
                        var d = $(this);
                        var dd = d.data('day');

                        if (d.hasClass("disabled") || d.hasClass("excluded")) return;

                        if (!that.selected.contains(dd))
                            that.selected.push(dd);
                        d.addClass("selected").addClass(o.clsSelected);
                    });
                }

                Utils.exec(o.onWeekNumberClick, [that.selected, weekNumber, weekNumElement], element[0]);
                element.fire("weeknumberclick", {
                    el: this,
                    num: weekNumber,
                    selected: that.selected
                });

                e.preventDefault();
                e.stopPropagation();
            });
        }

        element.on(Metro.events.click, ".days-row .day", function(e){
            var day = $(this);
            var index, date;

            date = day.data('day');
            index = that.selected.indexOf(date);

            if (day.hasClass("outside")) {
                date = new Date(date);
                that.current = {
                    year: date.getFullYear(),
                    month: date.getMonth(),
                    day: date.getDate()
                };
                that._drawContent();
                return ;
            }

            if (!day.hasClass("disabled")) {

                if (o.pickerMode === true) {
                    that.selected = [date];
                    that.today = new Date(date);
                    that.current.year = that.today.getFullYear();
                    that.current.month = that.today.getMonth();
                    that.current.day = that.today.getDate();
                    that._drawHeader();
                    that._drawContent();
                } else {
                    if (index === -1) {
                        if (o.multiSelect === false) {
                            element.find(".days-row .day").removeClass("selected").removeClass(o.clsSelected);
                            that.selected = [];
                        }
                        that.selected.push(date);
                        day.addClass("selected").addClass(o.clsSelected);
                    } else {
                        day.removeClass("selected").removeClass(o.clsSelected);
                        Utils.arrayDelete(that.selected, date);
                    }
                }

            }

            Utils.exec(o.onDayClick, [that.selected, day, element]);
            element.fire("dayclick", {
                day: day,
                selected: that.selected
            });

            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, ".curr-month", function(e){
            var target;
            var list = element.find(".months-list");

            list.find(".active").removeClass("active");
            list.scrollTop(0);
            element.find(".calendar-months").addClass("open");

            target = list.find(".js-month-"+that.current.month).addClass("active");

            setTimeout(function(){
                list.animate({
                    scrollTop: target.position().top - ( (list.height() - target.height() )/ 2)
                }, 200);
            }, 300);

            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, ".calendar-months li", function(e){
            that.current.month = $(this).index();
            that._drawContent();
            Utils.exec(o.onMonthChange, [that.current, element], element[0]);
            element.fire("monthchange", {
                current: that.current
            });
            element.find(".calendar-months").removeClass("open");
            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, ".curr-year", function(e){
            var target;
            var list = element.find(".years-list");

            list.find(".active").removeClass("active");
            list.scrollTop(0);
            element.find(".calendar-years").addClass("open");

            target = list.find(".js-year-"+that.current.year).addClass("active");

            setTimeout(function(){
                list.animate({
                    scrollTop: target.position().top - ( (list.height() - target.height() )/ 2)
                }, 200);
            }, 300);

            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, ".calendar-years li", function(e){
            that.current.year = $(this).text();
            that._drawContent();
            Utils.exec(o.onYearChange, [that.current, element], element[0]);
            element.fire("yearchange", {
                current: that.current
            });
            element.find(".calendar-years").removeClass("open");
            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.click, function(e){
            var months = element.find(".calendar-months");
            var years = element.find(".calendar-years");
            if (months.hasClass("open")) {
                months.removeClass("open");
            }
            if (years.hasClass("open")) {
                years.removeClass("open");
            }
            e.preventDefault();
            e.stopPropagation();
        });
    },

    _drawHeader: function(){
        var element = this.element, o = this.options;
        var header = element.find(".calendar-header");

        if (header.length === 0) {
            header = $("<div>").addClass("calendar-header").addClass(o.clsCalendarHeader).appendTo(element);
        }

        header.html("");

        $("<div>").addClass("header-year").html(this.today.getFullYear()).appendTo(header);
        $("<div>").addClass("header-day").html(this.today.format(o.headerFormat, o.locale)).appendTo(header);

        if (o.showHeader === false) {
            header.hide();
        }
    },

    _drawFooter: function(){
        var element = this.element, o = this.options;
        var buttons_locale = this.locale['buttons'];
        var footer = element.find(".calendar-footer");

        if (o.buttons === false) {
            return ;
        }

        if (footer.length === 0) {
            footer = $("<div>").addClass("calendar-footer").addClass(o.clsCalendarFooter).appendTo(element);
        }

        footer.html("");

        $.each(o.buttons, function(){
            var button = $("<button>").attr("type", "button").addClass("button " + this + " " + o['cls'+this.capitalize()+'Button']).html(buttons_locale[this]).appendTo(footer);
            if (this === 'cancel' || this === 'done') {
                button.addClass("js-dialog-close");
            }
        });

        if (o.showFooter === false) {
            footer.hide();
        }
    },

    _drawMonths: function(){
        var element = this.element, o = this.options;
        var months = $("<div>").addClass("calendar-months").addClass(o.clsCalendarMonths).appendTo(element);
        var list = $("<ul>").addClass("months-list").appendTo(months);
        var calendar_locale = this.locale['calendar'];
        var i;
        for(i = 0; i < 12; i++) {
            $("<li>").addClass("js-month-"+i).html(calendar_locale['months'][i]).appendTo(list);
        }
    },

    _drawYears: function(){
        var element = this.element, o = this.options;
        var years = $("<div>").addClass("calendar-years").addClass(o.clsCalendarYears).appendTo(element);
        var list = $("<ul>").addClass("years-list").appendTo(years);
        var i;
        for(i = this.minYear; i <= this.maxYear; i++) {
            $("<li>").addClass("js-year-"+i).html(i).appendTo(list);
        }
    },

    _drawContent: function(){
        var element = this.element, o = this.options;
        var content = element.find(".calendar-content"), toolbar;
        var calendar_locale = this.locale['calendar'];
        var i, j, d, s, counter = 0;
        var first = new Date(this.current.year, this.current.month, 1);
        var first_day;
        var prev_month_days = (new Date(this.current.year, this.current.month, 0)).getDate();
        var year, month;

        if (content.length === 0) {
            content = $("<div>").addClass("calendar-content").addClass(o.clsCalendarContent).appendTo(element);
        }

        content.html("");

        toolbar = $("<div>").addClass("calendar-toolbar").appendTo(content);

        /**
         * Calendar toolbar
         */
        $("<span>").addClass("prev-month").html(o.prevMonthIcon).appendTo(toolbar);
        $("<span>").addClass("curr-month").html(calendar_locale['months'][this.current.month]).appendTo(toolbar);
        $("<span>").addClass("next-month").html(o.nextMonthIcon).appendTo(toolbar);

        $("<span>").addClass("prev-year").html(o.prevYearIcon).appendTo(toolbar);
        $("<span>").addClass("curr-year").html(this.current.year).appendTo(toolbar);
        $("<span>").addClass("next-year").html(o.nextYearIcon).appendTo(toolbar);

        /**
         * Week days
         */
        var week_days = $("<div>").addClass("week-days").appendTo(content);
        var day_class = "day";

        if (o.showWeekNumber === true) {
            $("<span>").addClass("week-number").html("#").appendTo(week_days);
            day_class += " and-week-number";
        }

        for (i = 0; i < 7; i++) {
            if (o.weekStart === 0) {
                j = i;
            } else {
                j = i + 1;
                if (j === 7) j = 0;
            }
            $("<span>").addClass(day_class).html(calendar_locale["days"][j + 7]).appendTo(week_days);
        }

        /**
         * Calendar days
         */
        var days = $("<div>").addClass("days").appendTo(content);
        var days_row = $("<div>").addClass("days-row").appendTo(days);

        first_day = o.weekStart === 0 ? first.getDay() : (first.getDay() === 0 ? 6 : first.getDay() - 1);

        if (this.current.month - 1 < 0) {
            month = 11;
            year = this.current.year - 1;
        } else {
            month = this.current.month - 1;
            year = this.current.year;
        }

        if (o.showWeekNumber === true) {
            $("<div>").addClass("week-number").html((new Date(year, month, prev_month_days - first_day + 1)).getWeek(o.weekStart)).appendTo(days_row);
        }

        for(i = 0; i < first_day; i++) {
            var v = prev_month_days - first_day + i + 1;
            d = $("<div>").addClass(day_class+" outside").appendTo(days_row);

            s = new Date(year, month, v);
            s.setHours(0,0,0,0);

            d.data('day', s.getTime());

            if (o.outside === true) {
                d.html(v);

                if (this.excludeDay.length > 0) {
                    if (this.excludeDay.indexOf(s.getDay()) > -1) {
                        d.addClass("disabled excluded").addClass(o.clsExcluded);
                    }
                }

                Utils.exec(o.onDayDraw, [s], d[0]);
                element.fire("daydraw", {
                    cell: d[0],
                    date: s
                });
            }

            counter++;
        }

        first.setHours(0,0,0,0);
        while(first.getMonth() === this.current.month) {

            d = $("<div>").addClass(day_class).html(first.getDate()).appendTo(days_row);

            d.data('day', first.getTime());

            // console.log(this.show.getTime() === first.getTime());
            if (this.show.getTime() === first.getTime()) {
                d.addClass("showed");
            }

            // console.log(this.today.getTime() === first.getTime());
            if (this.today.getTime() === first.getTime()) {
                d.addClass("today").addClass(o.clsToday);
            }

            if (this.special.length === 0) {

                if (this.selected.indexOf(first.getTime()) !== -1) {
                    d.addClass("selected").addClass(o.clsSelected);
                }
                if (this.exclude.indexOf(first.getTime()) !== -1) {
                    d.addClass("disabled excluded").addClass(o.clsExcluded);
                }

                if (this.min !== null && first.getTime() < this.min.getTime()) {
                    d.addClass("disabled excluded").addClass(o.clsExcluded);
                }
                if (this.max !== null && first.getTime() > this.max.getTime()) {
                    d.addClass("disabled excluded").addClass(o.clsExcluded);
                }

                if (this.excludeDay.length > 0) {
                    if (this.excludeDay.indexOf(first.getDay()) > -1) {
                        d.addClass("disabled excluded").addClass(o.clsExcluded);
                    }
                }
            } else {

                if (this.special.indexOf(first.getTime()) === -1) {
                    d.addClass("disabled excluded").addClass(o.clsExcluded);
                }

            }

            Utils.exec(o.onDayDraw, [first], d[0]);
            element.fire("daydraw", {
                cell: d[0],
                date: first
            });

            counter++;
            if (counter % 7 === 0) {
                days_row = $("<div>").addClass("days-row").appendTo(days);
                if (o.showWeekNumber === true) {
                    $("<div>").addClass("week-number").html((new Date(first.getFullYear(), first.getMonth(), first.getDate() + 1)).getWeek(o.weekStart)).appendTo(days_row);
                }
            }
            first.setDate(first.getDate() + 1);
            first.setHours(0,0,0,0);
        }

        first_day = o.weekStart === 0 ? first.getDay() : (first.getDay() === 0 ? 6 : first.getDay() - 1);

        if (this.current.month + 1 > 11) {
            month = 0;
            year = this.current.year + 1;
        } else {
            month = this.current.month + 1;
            year = this.current.year;
        }

        if (first_day > 0) for(i = 0; i < 7 - first_day; i++) {
            d = $("<div>").addClass(day_class+" outside").appendTo(days_row);
            s = new Date(year, month, i + 1);
            s.setHours(0,0,0,0);
            d.data('day', s.getTime());
            if (o.outside === true) {
                d.html(i + 1);

                if (this.excludeDay.length > 0) {
                    if (this.excludeDay.indexOf(s.getDay()) > -1) {
                        d.addClass("disabled excluded").addClass(o.clsExcluded);
                    }
                }

                Utils.exec(o.onDayDraw, [s], d[0]);
                element.fire("daydraw", {
                    cell: d[0],
                    date: s
                });

            }
        }
    },

    _drawCalendar: function(){
        var that = this;
        setTimeout(function(){
            that.element.html("");
            that._drawHeader();
            that._drawContent();
            that._drawFooter();
            that._drawMonths();
            that._drawYears();
        }, 0);
    },

    getPreset: function(){
        return this.preset;
    },

    getSelected: function(){
        return this.selected;
    },

    getExcluded: function(){
        return this.exclude;
    },

    getToday: function(){
        return this.today;
    },

    getCurrent: function(){
        return this.current;
    },

    clearSelected: function(){
        this.selected = [];
        this._drawContent();
    },

    toDay: function(){
        this.today = new Date();
        this.today.setHours(0,0,0,0);
        this.current = {
            year: this.today.getFullYear(),
            month: this.today.getMonth(),
            day: this.today.getDate()
        };
        this._drawHeader();
        this._drawContent();
    },

    setExclude: function(exclude){
        var that = this, element = this.element, o = this.options;
        if (Utils.isNull(exclude) && Utils.isNull(element.attr("data-exclude"))) {
            return ;
        }
        o.exclude = !Utils.isNull(exclude) ? exclude : element.attr("data-exclude");
        this._dates2array(o.exclude, 'exclude');
        this._drawContent();
    },

    setPreset: function(preset){
        var that = this, element = this.element, o = this.options;
        if (Utils.isNull(preset) && Utils.isNull(element.attr("data-preset"))) {
            return ;
        }

        o.preset = !Utils.isNull(preset) ? preset : element.attr("data-preset");
        this._dates2array(o.preset, 'selected');
        this._drawContent();
    },

    setSpecial: function(special){
        var that = this, element = this.element, o = this.options;
        if (Utils.isNull(special) && Utils.isNull(element.attr("data-special"))) {
            return ;
        }
        o.special = !Utils.isNull(special) ? special : element.attr("data-special");
        this._dates2array(o.exclude, 'special');
        this._drawContent();
    },

    setShow: function(show){
        var that = this, element = this.element, o = this.options;

        if (Utils.isNull(show) && Utils.isNull(element.attr("data-show"))) {
            return ;
        }
        o.show = !Utils.isNull(show) ? show : element.attr("data-show");

        this.show = Utils.isDateObject(show) ? show : Utils.isValue(o.inputFormat) ? o.show.toDate(o.inputFormat) : new Date(o.show);
        this.show.setHours(0,0,0,0);
        this.current = {
            year: this.show.getFullYear(),
            month: this.show.getMonth(),
            day: this.show.getDate()
        };

        this._drawContent();
    },

    setMinDate: function(date){
        var that = this, element = this.element, o = this.options;

        o.minDate = Utils.isValue(date) ? date : element.attr("data-min-date");
        if (Utils.isValue(o.minDate) && Utils.isDate(o.minDate, o.inputFormat)) {
            this.min = Utils.isValue(o.inputFormat) ? o.minDate.toDate(o.inputFormat) : (new Date(o.minDate));
        }

        this._drawContent();
    },

    setMaxDate: function(date){
        var that = this, element = this.element, o = this.options;

        o.maxDate = Utils.isValue(date) ? date : element.attr("data-max-date");
        if (Utils.isValue(o.maxDate) && Utils.isDate(o.maxDate, o.inputFormat)) {
            this.max = Utils.isValue(o.inputFormat) ? o.maxDate.toDate(o.inputFormat) : (new Date(o.maxDate));
        }

        this._drawContent();
    },

    setToday: function(val){
        var that = this, element = this.element, o = this.options;

        if (!Utils.isValue(val)) {
            val = new Date();
        }
        this.today = Utils.isDateObject(val) ? val : Utils.isValue(o.inputFormat) ? val.toDate(o.inputFormat) : new Date(val);
        this.today.setHours(0,0,0,0);
        this._drawHeader();
        this._drawContent();
    },

    i18n: function(val){
        var that = this, element = this.element, o = this.options;
        if (val === undefined) {
            return o.locale;
        }
        if (Metro.locales[val] === undefined) {
            return false;
        }
        o.locale = val;
        this.locale = Metro.locales[o.locale];
        this._drawCalendar();
    },

    changeAttrLocale: function(){
        var that = this, element = this.element, o = this.options;
        this.i18n(element.attr("data-locale"));
    },

    changeAttribute: function(attributeName){
        switch (attributeName) {
            case 'data-exclude': this.setExclude(); break;
            case 'data-preset': this.setPreset(); break;
            case 'data-special': this.setSpecial(); break;
            case 'data-show': this.setShow(); break;
            case 'data-min-date': this.setMinDate(); break;
            case 'data-max-date': this.setMaxDate(); break;
            case 'data-locale': this.changeAttrLocale(); break;
        }
    },

    destroy: function(){
        var element = this.element, o = this.options;

        element.off(Metro.events.click, ".prev-month, .next-month, .prev-year, .next-year");
        element.off(Metro.events.click, ".button.today");
        element.off(Metro.events.click, ".button.clear");
        element.off(Metro.events.click, ".button.cancel");
        element.off(Metro.events.click, ".button.done");
        element.off(Metro.events.click, ".week-days .day");
        element.off(Metro.events.click, ".days-row .day");
        element.off(Metro.events.click, ".curr-month");
        element.off(Metro.events.click, ".calendar-months li");
        element.off(Metro.events.click, ".curr-year");
        element.off(Metro.events.click, ".calendar-years li");
        element.off(Metro.events.click);

        if (o.ripple === true) Metro.destroyPlugin(element, "ripple");

        element.html("");
    }
};

$(document).on(Metro.events.click, function(e){
    $('.calendar .calendar-years').each(function(){
        $(this).removeClass("open");
    });
    $('.calendar .calendar-months').each(function(){
        $(this).removeClass("open");
    });
});

Metro.plugin('calendar', Calendar);

var CalendarPickerDefaultConfig = {
    nullValue: true,

    prepend: "",

    calendarWide: false,
    calendarWidePoint: null,


    dialogMode: false,
    dialogPoint: 360,
    dialogOverlay: true,
    overlayColor: '#000000',
    overlayAlpha: .5,

    locale: METRO_LOCALE,
    size: "100%",
    format: METRO_DATE_FORMAT,
    inputFormat: null,
    headerFormat: "%A, %b %e",
    clearButton: false,
    calendarButtonIcon: "<span class='default-icon-calendar'></span>",
    clearButtonIcon: "<span class='default-icon-cross'></span>",
    copyInlineStyles: false,
    clsPicker: "",
    clsInput: "",

    yearsBefore: 100,
    yearsAfter: 100,
    weekStart: METRO_WEEK_START,
    outside: true,
    ripple: false,
    rippleColor: "#cccccc",
    exclude: null,
    minDate: null,
    maxDate: null,
    special: null,
    showHeader: true,

    clsCalendar: "",
    clsCalendarHeader: "",
    clsCalendarContent: "",
    clsCalendarMonths: "",
    clsCalendarYears: "",
    clsToday: "",
    clsSelected: "",
    clsExcluded: "",

    onDayClick: Metro.noop,
    onCalendarPickerCreate: Metro.noop,
    onCalendarShow: Metro.noop,
    onCalendarHide: Metro.noop,
    onChange: Metro.noop,
    onMonthChange: Metro.noop,
    onYearChange: Metro.noop
};

Metro.calendarPickerSetup = function (options) {
    CalendarPickerDefaultConfig = $.extend({}, CalendarPickerDefaultConfig, options);
};

if (typeof window.metroCalendarPickerSetup !== undefined) {
    Metro.calendarPickerSetup(window.metroCalendarPickerSetup);
}

var CalendarPicker = {
    init: function( options, elem ) {
        this.options = $.extend( {}, CalendarPickerDefaultConfig, options );
        this.elem  = elem;
        this.element = $(elem);
        this.value = null;
        this.value_date = null;
        this.calendar = null;
        this.overlay = null;

        this._setOptionsFromDOM();
        this._create();

        Utils.exec(this.options.onCalendarPickerCreate, [this.element], this.elem);
        $(elem).fire("calendarpickercreate");

        return this;
    },

    dependencies: ['calendar'],

    _setOptionsFromDOM: function(){
        var element = this.element, o = this.options;

        $.each(element.data(), function(key, value){
            if (key in o) {
                try {
                    o[key] = JSON.parse(value);
                } catch (e) {
                    o[key] = value;
                }
            }
        });
    },

    _create: function(){

        this._createStructure();
        this._createEvents();
    },

    _createStructure: function(){
        var that = this, element = this.element, o = this.options;
        var prev = element.prev();
        var parent = element.parent();
        var container = $("<div>").addClass("input " + element[0].className + " calendar-picker");
        var buttons = $("<div>").addClass("button-group");
        var calendarButton, clearButton, cal = $("<div>").addClass("drop-shadow");
        var curr = element.val().trim();

        if (element.attr("type") === undefined) {
            element.attr("type", "text");
        }

        if (!Utils.isValue(curr)) {
            //this.value = new Date();
        } else {
            this.value = Utils.isValue(o.inputFormat) === false ? new Date(curr) : curr.toDate(o.inputFormat, o.locale);
        }

        if (Utils.isValue(this.value)) this.value.setHours(0,0,0,0);

        element.val(!Utils.isValue(curr) && o.nullValue === true ? "" : this.value.format(o.format));

        if (prev.length === 0) {
            parent.prepend(container);
        } else {
            container.insertAfter(prev);
        }

        element.appendTo(container);
        buttons.appendTo(container);
        cal.appendTo(container);

        cal.calendar({
            wide: o.calendarWide,
            widePoint: o.calendarWidePoint,

            format: o.format,
            inputFormat: o.inputFormat,
            pickerMode: true,
            show: o.value,
            locale: o.locale,
            weekStart: o.weekStart,
            outside: o.outside,
            buttons: false,
            headerFormat: o.headerFormat,

            clsCalendar: o.clsCalendar,
            clsCalendarHeader: o.clsCalendarHeader,
            clsCalendarContent: o.clsCalendarContent,
            clsCalendarFooter: "d-none",
            clsCalendarMonths: o.clsCalendarMonths,
            clsCalendarYears: o.clsCalendarYears,
            clsToday: o.clsToday,
            clsSelected: o.clsSelected,
            clsExcluded: o.clsExcluded,

            ripple: o.ripple,
            rippleColor: o.rippleColor,
            exclude: o.exclude,
            minDate: o.minDate,
            maxDate: o.maxDate,
            yearsBefore: o.yearsBefore,
            yearsAfter: o.yearsAfter,
            special: o.special,
            showHeader: o.showHeader,
            showFooter: false,
            onDayClick: function(sel, day, el){
                var date = new Date(sel[0]);
                date.setHours(0,0,0,0);

                that._removeOverlay();

                that.value = date;
                element.val(date.format(o.format, o.locale));
                element.trigger("change");
                cal.removeClass("open open-up");
                cal.hide();

                Utils.exec(o.onChange, [that.value], element[0]);
                element.fire("change", {
                    val: that.value
                });

                Utils.exec(o.onDayClick, [sel, day, el], element[0]);
                element.fire("dayclick", {
                    sel: sel,
                    day: day,
                    el: el
                })
            },
            onMonthChange: o.onMonthChange,
            onYearChange: o.onYearChange
        });

        this.calendar = cal;

        if (o.clearButton === true) {
            clearButton = $("<button>").addClass("button input-clear-button").attr("tabindex", -1).attr("type", "button").html(o.clearButtonIcon);
            clearButton.appendTo(buttons);
        }

        calendarButton = $("<button>").addClass("button").attr("tabindex", -1).attr("type", "button").html(o.calendarButtonIcon);
        calendarButton.appendTo(buttons);

        if (o.prepend !== "") {
            var prepend = $("<div>").html(o.prepend);
            prepend.addClass("prepend").addClass(o.clsPrepend).appendTo(container);
        }

        if (element.attr('dir') === 'rtl' ) {
            container.addClass("rtl");
        }

        if (String(o.size).indexOf("%") > -1) {
            container.css({
                width: o.size
            });
        } else {
            container.css({
                width: parseInt(o.size) + "px"
            });
        }

        element[0].className = '';
        element.attr("readonly", true);

        if (o.copyInlineStyles === true) {
            $.each(Utils.getInlineStyles(element), function(key, value){
                container.css(key, value);
            });
        }

        container.addClass(o.clsPicker);
        element.addClass(o.clsInput);

        if (o.dialogOverlay === true) {
            this.overlay = that._overlay();
        }

        if (o.dialogMode === true) {
            container.addClass("dialog-mode");
        } else {
            if (Utils.media("(max-width: "+o.dialogPoint+"px)")) {
                container.addClass("dialog-mode");
            }
        }

        if (element.is(":disabled")) {
            this.disable();
        } else {
            this.enable();
        }

    },

    _createEvents: function(){
        var that = this, element = this.element, o = this.options;
        var container = element.parent();
        var clear = container.find(".input-clear-button");
        var cal = this.calendar;
        var cal_plugin = cal.data('calendar');

        $(window).on(Metro.events.resize, function(){
            if (o.dialogMode !== true) {
                if (Utils.media("(max-width: " + o.dialogPoint + "px)")) {
                    container.addClass("dialog-mode");
                } else {
                    container.removeClass("dialog-mode");
                }
            }
        });

        if (clear.length > 0) clear.on(Metro.events.click, function(e){
            element.val("").trigger('change').blur();
            that.value = null;
            e.preventDefault();
            e.stopPropagation();
        });

        container.on(Metro.events.click, "button, input", function(e){

            var value = Utils.isValue(that.value) ? that.value : new Date();

            value.setHours(0,0,0,0);

            if (cal.hasClass("open") === false && cal.hasClass("open-up") === false) {

                $(".calendar-picker .calendar").removeClass("open open-up").hide();

                cal_plugin.setPreset([value]);
                cal_plugin.setShow(value);
                cal_plugin.setToday(value);

                if (container.hasClass("dialog-mode")) {
                    that.overlay.appendTo($('body'));
                }
                cal.addClass("open");
                if (Utils.isOutsider(cal) === false) {
                    cal.addClass("open-up");
                }
                Utils.exec(o.onCalendarShow, [element, cal], cal);
                element.fire("calendarshow", {
                    calendar: cal
                });

            } else {

                that._removeOverlay();
                cal.removeClass("open open-up");
                Utils.exec(o.onCalendarHide, [element, cal], cal);
                element.fire("calendarhide", {
                    calendar: cal
                });

            }
            e.preventDefault();
            e.stopPropagation();
        });

        element.on(Metro.events.blur, function(){container.removeClass("focused");});
        element.on(Metro.events.focus, function(){container.addClass("focused");});
        element.on(Metro.events.change, function(){
            Utils.exec(o.onChange, [that.value], element[0]);
        });
    },

    _overlay: function(){
        var o = this.options;

        var overlay = $("<div>");
        overlay.addClass("overlay for-calendar-picker").addClass(o.clsOverlay);

        if (o.overlayColor === 'transparent') {
            overlay.addClass("transparent");
        } else {
            overlay.css({
                background: Utils.hex2rgba(o.overlayColor, o.overlayAlpha)
            });
        }

        return overlay;
    },

    _removeOverlay: function(){
        $('body').find('.overlay.for-calendar-picker').remove();
    },

    val: function(v){
        var element = this.element, o = this.options;

        if (Utils.isNull(v)) {
            return this.value;
        }

        if (Utils.isDate(v, o.inputFormat) === true) {
            this.calendar.data("calendar").clearSelected();
            this.value = typeof v === 'string' ? v.toDate(o.inputFormat) : v;
            element.val(this.value.format(o.format));
            element.trigger("change");
        }
    },

    disable: function(){
        this.element.data("disabled", true);
        this.element.parent().addClass("disabled");
    },

    enable: function(){
        this.element.data("disabled", false);
        this.element.parent().removeClass("disabled");
    },

    toggleState: function(){
        if (this.elem.disabled) {
            this.disable();
        } else {
            this.enable();
        }
    },

    i18n: function(val){
        var o = this.options;
        var hidden;
        var cal = this.calendar;
        if (val === undefined) {
            return o.locale;
        }
        if (Metro.locales[val] === undefined) {
            return false;
        }

        hidden = cal.is(':hidden');
        if (hidden) {
            cal.css({
                visibility: "hidden",
                display: "block"
            });
        }
        cal.data('calendar').i18n(val);
        if (hidden) {
            cal.css({
                visibility: "visible",
                display: "none"
            });
        }
    },

    changeAttribute: function(attributeName){
        var that = this, element = this.element, o = this.options;
        var cal = this.calendar.data("calendar");

        var changeAttrLocale = function(){
            that.i18n(element.attr("data-locale"));
        };

        var changeAttrSpecial = function(){
            cal.setSpecial(element.attr("data-special"));
        };

        var changeAttrExclude = function(){
            cal.setExclude(element.attr("data-exclude"));
        };

        var changeAttrMinDate = function(){
            cal.setMinDate(element.attr("data-min-date"));
        };

        var changeAttrMaxDate = function(){
            cal.setMaxDate(element.attr("data-max-date"));
        };

        var changeAttrValue = function(){
            that.val(element.attr("value"));
        };

        switch (attributeName) {
            case "value": changeAttrValue(); break;
            case 'disabled': this.toggleState(); break;
            case 'data-locale': changeAttrLocale(); break;
            case 'data-special': changeAttrSpecial(); break;
            case 'data-exclude': changeAttrExclude(); break;
            case 'data-min-date': changeAttrMinDate(); break;
            case 'data-max-date': changeAttrMaxDate(); break;
        }
    }
};

Metro.plugin('calendarpicker', CalendarPicker);

$(document).on(Metro.events.click, ".overlay.for-calendar-picker",function(){
    $(this).remove();
});

$(document).on(Metro.events.click, function(){
    $(".calendar-picker .calendar").removeClass("open open-up");
});


var ClockDefaultConfig = {
    showTime: true,
    showDate: true,
    timeFormat: '24',
    dateFormat: 'american',
    divider: "&nbsp;&nbsp;",
    leadingZero: true,
    dateDivider: '-',
    timeDivider: ":",
    onClockCreate: Metro.noop
};

Metro.clockSetup = function (options) {
    ClockDefaultConfig = $.extend({}, ClockDefaultConfig, options);
};

if (typeof window.metroClockSetup !== undefined) {
    Metro.clockSetup(window.metroClockSetup);
}

var Clock = {
    init: function( options, elem ) {
        this.options = $.extend( {}, ClockDefaultConfig, options );
        this.elem  = elem;
        this.element = $(elem);
        this._clockInterval = null;
        this._setOptionsFromDOM();
        this._create();

        return this;
    },

    _setOptionsFromDOM: function(){
        var that = this, element = this.element, o = this.options;

        $.each(element.data(), function(key, value){
            if (key in o) {
                try {
                    o[key] = JSON.parse(value);
                } catch (e) {
                    o[key] = value;
                }
            }
        });
    },

    _create: function(){
        var that = this, element = this.element;

        this._tick();

        Utils.exec(this.options.onClockCreate, [this.element]);
        element.fire("clockcreate");

        this._clockInterval = setInterval(function(){
            that._tick();
        }, 500);
    },

    _addLeadingZero: function(i){
        if (i<10){i="0" + i;}
        return i;
    },

    _tick: function(){
        var that = this, element = this.element, o = this.options;
        var timestamp = new Date();
        var time = timestamp.getTime();
        var result = "";
        var h = timestamp.getHours(),
            i = timestamp.getMinutes(),
            s = timestamp.getSeconds(),
            d = timestamp.getDate(),
            m = timestamp.getMonth() + 1,
            y = timestamp.getFullYear(),
            a = '';

        if (parseInt(o.timeFormat) === 12) {
            a = " AM";
            if (h > 11) { a = " PM"; }
            if (h > 12) { h = h - 12; }
            if (h === 0) { h = 12; }
        }

        i = this._addLeadingZero(i);
        s = this._addLeadingZero(s);

        if (o.leadingZero) {
            h = this._addLeadingZero(h);
            m = this._addLeadingZero(m);
            d = this._addLeadingZero(d);
        }

        if (o.showDate) {
            if (o.dateFormat === 'american') {
                result += "<span class='date-month'>" + m + "</span>";
                result += "<span class='date-divider'>" + o.dateDivider + "</span>";
                result += "<span class='date-day'>" + d + "</span>";
                result += "<span class='date-divider'>" + o.dateDivider + "</span>";
                result += "<span class='date-year'>" + y + "</span>";
            } else {
                result += "<span class='date-day'>" + d + "</span>";
                result += "<span class='date-divider'>" + o.dateDivider + "</span>";
                result += "<span class='date-month'>" + m + "</span>";
                result += "<span class='date-divider'>" + o.dateDivider + "</span>";
                result += "<span class='date-year'>" + y + "</span>";
            }
            result += o.divider;
        }

        if (o.showTime) {
            result += "<span class='clock-hour'>" + h + "</span>";
            result += "<span class='clock-divider'>" + o.timeDivider + "</span>";
            result += "<span class='clock-minute'>" + i + "</span>";
            result += "<span class='clock-divider'>" + o.timeDivider + "</span>";
            result += "<span class='clock-second'>" + s + "</span>";
            result += "<span class='clock-suffix'>" + a + "</span>";
        }

        element.html(result);
    },

    changeAttribute: function(attributeName){

    },

    destroy: function(){
        clearInterval(this._clockInterval);
        this._clockInterval = null;
        this.element.html("");
    }
};

Metro.plugin('clock', Clock);

var HintDefaultConfig = {
    hintHide: 5000,
    clsHint: "",
    hintText: "",
    hintPosition: Metro.position.TOP,
    hintOffset: 4,
    onHintShow: Metro.noop,
    onHintHide: Metro.noop,
    onHintCreate: Metro.noop
};

Metro.hintSetup = function (options) {
    HintDefaultConfig = $.extend({}, HintDefaultConfig, options);
};

if (typeof window.metroHintSetup !== undefined) {
    Metro.hintSetup(window.metroHintSetup);
}

var Hint = {
    init: function( options, elem ) {
        this.options = $.extend( {}, HintDefaultConfig, options );
        this.elem  = elem;
        this.element = $(elem);
        this.hint = null;
        this.hint_size = {
            width: 0,
            height: 0
        };

        this._setOptionsFromDOM();
        this._create();

        return this;
    },

    _setOptionsFromDOM: function(){
        var that = this, element = this.element, o = this.options;

        $.each(element.data(), function(key, value){
            if (key in o) {
                try {
                    o[key] = JSON.parse(value);
                } catch (e) {
                    o[key] = value;
                }
            }
        });
    },

    _create: function(){
        var that = this, element = this.element, o = this.options;

        element.on(Metro.events.enter + "-hint", function(){
            that.createHint();
            if (o.hintHide > 0) {
                setTimeout(function(){
                    that.removeHint();
                }, o.hintHide);
            }
        });

        element.on(Metro.events.leave + "-hint", function(){
            that.removeHint();
        });

        $(window).on(Metro.events.scroll + "-hint", function(){
            if (that.hint !== null) that.setPosition();
        });

        Utils.exec(o.onHintCreate, null, element[0]);
        element.fire("hintcreate");
    },

    createHint: function(){
        var that = this, elem = this.elem, element = this.element, o = this.options;
        var hint = $("<div>").addClass("hint").addClass(o.clsHint).html(o.hintText);

        this.hint = hint;
        this.hint_size = Utils.hiddenElementSize(hint);

        $(".hint:not(.permanent-hint)").remove();

        if (elem.tagName === 'TD' || elem.tagName === 'TH') {
            var wrp = $("<div/>").css("display", "inline-block").html(element.html());
            element.html(wrp);
            element = wrp;
        }

        this.setPosition();

        hint.appendTo($('body'));
        Utils.exec(o.onHintShow, [element[0]], hint[0]);
        element.fire("hintshow", {
            element: element[0]
        });
    },

    setPosition: function(){
        var hint = this.hint, hint_size = this.hint_size, o = this.options, element = this.element;

        if (o.hintPosition === Metro.position.BOTTOM) {
            hint.addClass('bottom');
            hint.css({
                top: element.offset().top - $(window).scrollTop() + element.outerHeight() + o.hintOffset,
                left: element.offset().left + element.outerWidth()/2 - hint_size.width/2  - $(window).scrollLeft()
            });
        } else if (o.hintPosition === Metro.position.RIGHT) {
            hint.addClass('right');
            hint.css({
                top: element.offset().top + element.outerHeight()/2 - hint_size.height/2 - $(window).scrollTop(),
                left: element.offset().left + element.outerWidth() - $(window).scrollLeft() + o.hintOffset
            });
        } else if (o.hintPosition === Metro.position.LEFT) {
            hint.addClass('left');
            hint.css({
                top: element.offset().top + element.outerHeight()/2 - hint_size.height/2 - $(window).scrollTop(),
                left: element.offset().left - hint_size.width - $(window).scrollLeft() - o.hintOffset
            });
        } else {
            hint.addClass('top');
            hint.css({
                top: element.offset().top - $(window).scrollTop() - hint_size.height - o.hintOffset,
                left: element.offset().left + element.outerWidth()/2 - hint_size.width/2  - $(window).scrollLeft()
            });
        }
    },

    removeHint: function(){
        var that = this;
        var hint = this.hint;
        var element = this.element;
        var options = this.options;
        var timeout = options.onHintHide === Metro.noop ? 0 : 300;

        if (hint !== null) {

            Utils.exec(options.onHintHide, [element[0]], hint[0]);
            element.fire("hinthide", {
                element: element[0]
            });

            setTimeout(function(){
                hint.hide(0, function(){
                    hint.remove();
                    that.hint = null;
                });
            }, timeout);
        }
    },

    changeText: function(){
        this.options.hintText = this.element.attr("data-hint-text");
    },

    changeAttribute: function(attributeName){
        switch (attributeName) {
            case "data-hint-text": this.changeText(); break;
        }
    },

    destroy: function(){
        var that = this, elem = this.elem, element = this.element, o = this.options;
        this.removeHint();
        element.off(Metro.events.enter + "-hint");
        element.off(Metro.events.leave + "-hint");
        $(window).off(Metro.events.scroll + "-hint");
    }
};

Metro.plugin('hint', Hint);

var InputDefaultConfig = {
    autocomplete: null,
    autocompleteDivider: ",",
    autocompleteListHeight: 200,

    history: false,
    historyPreset: "",
    historyDivider: "|",
    preventSubmit: false,
    defaultValue: "",
    size: "default",
    prepend: "",
    append: "",
    copyInlineStyles: true,
    searchButton: false,
    clearButton: true,
    revealButton: true,
    clearButtonIcon: "<span class='default-icon-cross'></span>",
    revealButtonIcon: "<span class='default-icon-eye'></span>",
    searchButtonIcon: "<span class='default-icon-search'></span>",
    customButtons: [],
    searchButtonClick: 'submit',

    clsComponent: "",
    clsInput: "",
    clsPrepend: "",
    clsAppend: "",
    clsClearButton: "",
    clsRevealButton: "",
    clsCustomButton: "",
    clsSearchButton: "",

    onHistoryChange: Metro.noop,
    onHistoryUp: Metro.noop,
    onHistoryDown: Metro.noop,
    onClearClick: Metro.noop,
    onRevealClick: Metro.noop,
    onSearchButtonClick: Metro.noop,
    onEnterClick: Metro.noop,
    onInputCreate: Metro.noop
};

Metro.inputSetup = function (options) {
    InputDefaultConfig = $.extend({}, InputDefaultConfig, options);
};

if (typeof window["metroInputSetup"] !== undefined) {
    Metro.inputSetup(window["metroInputSetup"]);
}

var Input = {
    init: function( options, elem ) {
        this.options = $.extend( {}, InputDefaultConfig, options );
        this.elem  = elem;
        this.element = $(elem);
        this.history = [];
        this.historyIndex = -1;
        this.autocomplete = [];

        this._setOptionsFromDOM();
        this._create();

        return this;
    },

    _setOptionsFromDOM: function(){
        var element = this.element, o = this.options;

        $.each(element.data(), function(key, value){
            if (key in o) {
                try {
                    o[key] = JSON.parse(value);
                } catch (e) {
                    o[key] = value;
                }
            }
        });
    },

    _create: function(){
        var that = this, element = this.element, o = this.options;

        this._createStructure();
        this._createEvents();

        Utils.exec(o.onInputCreate, null, element[0]);

        element.fire("inputcreate");
    },

    _createStructure: function(){
        var that = this, element = this.element, o = this.options;
        var prev = element.prev();
        var parent = element.parent();
        var container = $("<div>").addClass("input " + element[0].className);
        var buttons = $("<div>").addClass("button-group");
        var clearButton, revealButton, searchButton;

        if (Utils.isValue(o.historyPreset)) {
            $.each(Utils.strToArray(o.historyPreset, o.historyDivider), function(){
                that.history.push(this);
            });
            that.historyIndex = that.history.length - 1;
        }

        if (element.attr("type") === undefined) {
            element.attr("type", "text");
        }

        if (prev.length === 0) {
            parent.prepend(container);
        } else {
            container.insertAfter(prev);
        }

        element.appendTo(container);
        buttons.appendTo(container);

        if (!Utils.isValue(element.val().trim())) {
            element.val(o.defaultValue);
        }

        if (o.clearButton === true && !element[0].readOnly) {
            clearButton = $("<button>").addClass("button input-clear-button").addClass(o.clsClearButton).attr("tabindex", -1).attr("type", "button").html(o.clearButtonIcon);
            clearButton.appendTo(buttons);
        }
        if (element.attr('type') === 'password' && o.revealButton === true) {
            revealButton = $("<button>").addClass("button input-reveal-button").addClass(o.clsRevealButton).attr("tabindex", -1).attr("type", "button").html(o.revealButtonIcon);
            revealButton.appendTo(buttons);
        }
        if (o.searchButton === true) {
            searchButton = $("<button>").addClass("button input-search-button").addClass(o.clsSearchButton).attr("tabindex", -1).attr("type", o.searchButtonClick === 'submit' ? "submit" : "button").html(o.searchButtonIcon);
            searchButton.appendTo(buttons);
        }

        if (Utils.isValue(o.prepend)) {
            var prepend = $("<div>").html(o.prepend);
            prepend.addClass("prepend").addClass(o.clsPrepend).appendTo(container);
        }

        if (Utils.isValue(o.append)) {
            var append = $("<div>").html(o.append);
            append.addClass("append").addClass(o.clsAppend).appendTo(container);
        }

        if (typeof o.customButtons === "string") {
            o.customButtons = Utils.isObject(o.customButtons);
        }

        if (typeof o.customButtons === "object" && Utils.objectLength(o.customButtons) > 0) {
            $.each(o.customButtons, function(){
                var item = this;
                var customButton = $("<button>");

                customButton
                    .addClass("button input-custom-button")
                    .addClass(o.clsCustomButton)
                    .addClass(item.cls)
                    .attr("tabindex", -1)
                    .attr("type", "button")
                    .html(item.html);

                customButton.data("action", item.onclick);

                customButton.appendTo(buttons);
            });
        }

        if (Utils.isValue(element.attr('data-exclaim'))) {
            container.attr('data-exclaim', element.attr('data-exclaim'));
        }

        if (element.attr('dir') === 'rtl' ) {
            container.addClass("rtl").attr("dir", "rtl");
        }

        element[0].className = '';
        if (o.copyInlineStyles === true) {
            for (var i = 0, l = element[0].style.length; i < l; i++) {
                container.css(element[0].style[i], element.css(element[0].style[i]));
            }
        }

        container.addClass(o.clsComponent);
        element.addClass(o.clsInput);

        if (o.size !== "default") {
            container.css({
                width: o.size
            });
        }

        if (!Utils.isNull(o.autocomplete)) {

            var autocomplete_obj = Utils.isObject(o.autocomplete);

            if (autocomplete_obj !== false) {
                that.autocomplete = autocomplete_obj;
            } else {
                this.autocomplete = Utils.strToArray(o.autocomplete, o.autocompleteDivider);
            }
            $("<div>").addClass("autocomplete-list").css({
                maxHeight: o.autocompleteListHeight,
                display: "none"
            }).appendTo(container);
        }

        if (element.is(":disabled")) {
            this.disable();
        } else {
            this.enable();
        }
    },

    _createEvents: function(){
        var that = this, element = this.element, o = this.options;
        var container = element.closest(".input");
        var autocompleteList = container.find(".autocomplete-list");

        container.on(Metro.events.click, ".input-clear-button", function(){
            var curr = element.val();
            element.val(Utils.isValue(o.defaultValue) ? o.defaultValue : "").fire('clear').fire('change').fire('keyup').focus();
            if (autocompleteList.length > 0) {
                autocompleteList.css({
                    display: "none"
                })
            }
            Utils.exec(o.onClearClick, [curr, element.val()], element[0]);
            element.fire("clearclick", {
                prev: curr,
                val: element.val()
            });
        });

        container.on(Metro.events.start, ".input-reveal-button", function(){
            element.attr('type', 'text');
            Utils.exec(o.onRevealClick, [element.val()], element[0]);
            element.fire("revealclick", {
                val: element.val()
            });
        });

        container.on(Metro.events.start, ".input-search-button", function(){
            if (o.searchButtonClick !== 'submit') {
                Utils.exec(o.onSearchButtonClick, [element.val()], this);
                element.fire("searchbuttonclick", {
                    val: element.val(),
                    button: this
                });
            } else {
                this.form.submit();
            }
        });

        container.on(Metro.events.stop, ".input-reveal-button", function(){
            element.attr('type', 'password').focus();
        });

        container.on(Metro.events.stop, ".input-custom-button", function(){
            var button = $(this);
            var action = button.data("action");
            Utils.exec(action, [element.val(), button], this);
        });

        element.on(Metro.events.keyup, function(e){
            var val = element.val().trim();

            if (o.history && e.keyCode === Metro.keyCode.ENTER && val !== "") {
                element.val("");
                that.history.push(val);
                that.historyIndex = that.history.length - 1;
                Utils.exec(o.onHistoryChange, [val, that.history, that.historyIndex], element[0]);
                element.fire("historychange", {
                    val: val,
                    history: that.history,
                    historyIndex: that.historyIndex
                });
                if (o.preventSubmit === true) {
                    e.preventDefault();
                }
            }

            if (o.history && e.keyCode === Metro.keyCode.UP_ARROW) {
                that.historyIndex--;
                if (that.historyIndex >= 0) {
                    element.val("");
                    element.val(that.history[that.historyIndex]);
                    Utils.exec(o.onHistoryDown, [element.val(), that.history, that.historyIndex], element[0]);
                    element.fire("historydown", {
                        val: element.val(),
                        history: that.history,
                        historyIndex: that.historyIndex
                    });
                } else {
                    that.historyIndex = 0;
                }
                e.preventDefault();
            }

            if (o.history && e.keyCode === Metro.keyCode.DOWN_ARROW) {
                that.historyIndex++;
                if (that.historyIndex < that.history.length) {
                    element.val("");
                    element.val(that.history[that.historyIndex]);
                    Utils.exec(o.onHistoryUp, [element.val(), that.history, that.historyIndex], element[0]);
                    element.fire("historyup", {
                        val: element.val(),
                        history: that.history,
                        historyIndex: that.historyIndex
                    });
                } else {
                    that.historyIndex = that.history.length - 1;
                }
                e.preventDefault();
            }
        });

        element.on(Metro.events.keydown, function(e){
            if (e.keyCode === Metro.keyCode.ENTER) {
                Utils.exec(o.onEnterClick, [element.val()], element[0]);
                element.fire("enterclick", {
                    val: element.val()
                });
            }
        });

        element.on(Metro.events.blur, function(){
            container.removeClass("focused");
        });

        element.on(Metro.events.focus, function(){
            container.addClass("focused");
        });

        element.on(Metro.events.input, function(){
            var val = this.value.toLowerCase();
            var items;

            if (autocompleteList.length === 0) {
                return;
            }

            autocompleteList.html("");

            items = that.autocomplete.filter(function(item){
                return item.toLowerCase().indexOf(val) > -1;
            });

            autocompleteList.css({
                display: items.length > 0 ? "block" : "none"
            });

            $.each(items, function(i, v){
                var index = v.toLowerCase().indexOf(val);
                var item = $("<div>").addClass("item").attr("data-autocomplete-value", v);
                var html;

                if (index === 0) {
                    html = "<strong>"+v.substr(0, val.length)+"</strong>"+v.substr(val.length);
                } else {
                    html = v.substr(0, index) + "<strong>"+v.substr(index, val.length)+"</strong>"+v.substr(index + val.length);
                }
                item.html(html).appendTo(autocompleteList);
            })
        });

        container.on(Metro.events.click, ".autocomplete-list .item", function(){
            element.val($(this).attr("data-autocomplete-value"));
            autocompleteList.css({
                display: "none"
            });
            element.trigger("change");
        });
    },

    getHistory: function(){
        return this.history;
    },

    getHistoryIndex: function(){
        return this.historyIndex;
    },

    setHistoryIndex: function(val){
        this.historyIndex = val >= this.history.length ? this.history.length - 1 : val;
    },

    setHistory: function(history, append) {
        var that = this, o = this.options;
        if (Utils.isNull(history)) return;
        if (!Array.isArray(history)) {
            history = Utils.strToArray(history, o.historyDivider);
        }
        if (append === true) {
            $.each(history, function () {
                that.history.push(this);
            })
        } else{
            this.history = history;
        }
        this.historyIndex = this.history.length - 1;
    },

    clear: function(){
        this.element.val('');
    },

    toDefault: function(){
        this.element.val(Utils.isValue(this.options.defaultValue) ? this.options.defaultValue : "");
    },

    disable: function(){
        this.element.data("disabled", true);
        this.element.parent().addClass("disabled");
    },

    enable: function(){
        this.element.data("disabled", false);
        this.element.parent().removeClass("disabled");
    },

    toggleState: function(){
        if (this.elem.disabled) {
            this.disable();
        } else {
            this.enable();
        }
    },

    changeAttribute: function(attributeName){
        switch (attributeName) {
            case 'disabled': this.toggleState(); break;
        }
    },

    destroy: function(){
        var element = this.element;
        var parent = element.parent();
        var clearBtn = parent.find(".input-clear-button");
        var revealBtn = parent.find(".input-reveal-button");
        var customBtn = parent.find(".input-custom-button");

        if (clearBtn.length > 0) {
            clearBtn.off(Metro.events.click);
        }
        if (revealBtn.length > 0) {
            revealBtn.off(Metro.events.start);
            revealBtn.off(Metro.events.stop);
        }
        if (customBtn.length > 0) {
            clearBtn.off(Metro.events.click);
        }

        element.off(Metro.events.blur);
        element.off(Metro.events.focus);

        element.insertBefore(parent);
        parent.remove();
    }
};

Metro.plugin('input', Input);

$(document).on(Metro.events.click, function(e){
    $('.input .autocomplete-list').hide();
});


var TextareaDefaultConfig = {
    charsCounter: null,
    charsCounterTemplate: "$1",
    defaultValue: "",
    prepend: "",
    append: "",
    copyInlineStyles: true,
    clearButton: true,
    clearButtonIcon: "<span class='default-icon-cross'></span>",
    autoSize: true,
    clsPrepend: "",
    clsAppend: "",
    clsComponent: "",
    clsTextarea: "",
    onChange: Metro.noop,
    onTextareaCreate: Metro.noop
};

Metro.textareaSetup = function (options) {
    TextareaDefaultConfig = $.extend({}, TextareaDefaultConfig, options);
};

if (typeof window["metroTextareaSetup"] !== undefined) {
    Metro.textareaSetup(window["metroTextareaSetup"]);
}

var Textarea = {
    init: function( options, elem ) {
        this.options = $.extend( {}, TextareaDefaultConfig, options );
        this.elem  = elem;
        this.element = $(elem);

        this._setOptionsFromDOM();
        this._create();

        return this;
    },

    _setOptionsFromDOM: function(){
        var element = this.element, o = this.options;

        $.each(element.data(), function(key, value){
            if (key in o) {
                try {
                    o[key] = JSON.parse(value);
                } catch (e) {
                    o[key] = value;
                }
            }
        });
    },

    _create: function(){
        var element = this.element, o = this.options;

        this._createStructure();
        this._createEvents();

        Utils.exec(o.onTextareaCreate, null, element[0]);
        element.fire("textareacreate");
    },

    _createStructure: function(){
        var that = this, element = this.element, o = this.options;
        var prev = element.prev();
        var parent = element.parent();
        var container = $("<div>").addClass("textarea " + element[0].className);
        var clearButton;
        var timer = null;

        if (prev.length === 0) {
            parent.prepend(container);
        } else {
            container.insertAfter(prev);
        }

        if (o.clearButton !== false && !element[0].readOnly) {
            clearButton = $("<button>").addClass("button input-clear-button").attr("tabindex", -1).attr("type", "button").html(o.clearButtonIcon);
            clearButton.appendTo(container);
        }

        element.appendTo(container);

        if (o.autoSize) {

            container.addClass("autosize");

            timer = setTimeout(function(){
                timer = null;
                that.resize();
            }, 0);
        }

        if (element.attr('dir') === 'rtl' ) {
            container.addClass("rtl").attr("dir", "rtl");
        }

        if (o.prepend !== "") {
            var prepend = $("<div>").html(o.prepend);
            prepend.addClass("prepend").addClass(o.clsPrepend).appendTo(container);
        }

        if (o.append !== "") {
            var append = $("<div>").html(o.append);
            append.addClass("append").addClass(o.clsAppend).appendTo(container);
            clearButton.css({
                right: append.outerWidth() + 4
            });
        }

        element[0].className = '';
        if (o.copyInlineStyles === true) {
            for (var i = 0, l = element[0].style.length; i < l; i++) {
                container.css(element[0].style[i], element.css(element[0].style[i]));
            }
        }

        if (Utils.isValue(o.defaultValue) && element.val().trim() === "") {
            element.val(o.defaultValue);
        }

        container.addClass(o.clsComponent);
        element.addClass(o.clsTextarea);

        if (element.is(':disabled')) {
            this.disable();
        } else {
            this.enable();
        }
    },

    _createEvents: function(){
        var that = this, element = this.element, o = this.options;
        var textarea = element.closest(".textarea");
        var chars_counter = $(o.charsCounter);

        textarea.on(Metro.events.click, ".input-clear-button", function(){
            element.val(Utils.isValue(o.defaultValue) ? o.defaultValue : "").trigger('change').trigger('keyup').focus();
        });

        if (o.autoSize) {
            element.on(Metro.events.keyup, $.proxy(this.resize, that));
            element.on(Metro.events.keydown, $.proxy(this.resize, that));
            element.on(Metro.events.change, $.proxy(this.resize, that));
            element.on(Metro.events.focus, $.proxy(this.resize, that));
            element.on(Metro.events.cut, $.proxy(this.resize, that));
            element.on(Metro.events.paste, $.proxy(this.resize, that));
            element.on(Metro.events.drop, $.proxy(this.resize, that));
        }

        element.on(Metro.events.blur, function(){textarea.removeClass("focused");});
        element.on(Metro.events.focus, function(){textarea.addClass("focused");});

        element.on(Metro.events.keyup, function(){
            if (Utils.isValue(o.charsCounter) && chars_counter.length > 0) {
                if (chars_counter[0].tagName === "INPUT") {
                    chars_counter.val(that.length());
                } else {
                    chars_counter.html(o.charsCounterTemplate.replace("$1", that.length()));
                }
            }
            Utils.exec(o.onChange, [element.val(), that.length()], element[0]);
            element.fire("change", {
                val: element.val(),
                length: that.length()
            });
        })
    },

    resize: function(){
        var element = this.element;

        element[0].style.cssText = 'height:auto;';
        element[0].style.cssText = 'height:' + element[0].scrollHeight + 'px';
    },

    clear: function(){
        this.element.val("").trigger('change').trigger('keyup').focus();
    },

    toDefault: function(){
        this.element.val(Utils.isValue(this.options.defaultValue) ? this.options.defaultValue : "").trigger('change').trigger('keyup').focus();
    },

    length: function(){
        var characters = this.elem.value.split('');
        return characters.length;
    },

    disable: function(){
        this.element.data("disabled", true);
        this.element.parent().addClass("disabled");
    },

    enable: function(){
        this.element.data("disabled", false);
        this.element.parent().removeClass("disabled");
    },

    toggleState: function(){
        if (this.elem.disabled) {
            this.disable();
        } else {
            this.enable();
        }
    },

    changeAttribute: function(attributeName){
        switch (attributeName) {
            case 'disabled': this.toggleState(); break;
        }
    }
};

Metro.plugin('textarea', Textarea);

var TimePickerDefaultConfig = {
    hoursStep: 1,
    minutesStep: 1,
    secondsStep: 1,
    value: null,
    locale: METRO_LOCALE,
    distance: 3,
    hours: true,
    minutes: true,
    seconds: true,
    showLabels: true,
    scrollSpeed: 4,
    copyInlineStyles: true,
    clsPicker: "",
    clsPart: "",
    clsHours: "",
    clsMinutes: "",
    clsSeconds: "",
    okButtonIcon: "<span class='default-icon-check'></span>",
    cancelButtonIcon: "<span class='default-icon-cross'></span>",
    onSet: Metro.noop,
    onOpen: Metro.noop,
    onClose: Metro.noop,
    onScroll: Metro.noop,
    onTimePickerCreate: Metro.noop
};

Metro.timePickerSetup = function (options) {
    TimePickerDefaultConfig = $.extend({}, TimePickerDefaultConfig, options);
};

if (typeof window.metroTimePickerSetup !== undefined) {
    Metro.timePickerSetup(window.metroTimePickerSetup);
}

var TimePicker = {
    init: function( options, elem ) {
        this.options = $.extend( {}, TimePickerDefaultConfig, options );
        this.elem  = elem;
        this.element = $(elem);
        this.picker = null;
        this.isOpen = false;
        this.value = [];
        this.locale = Metro.locales[METRO_LOCALE]['calendar'];
        this.listTimer = {
            hours: null,
            minutes: null,
            seconds: null
        };


        this._setOptionsFromDOM();
        this._create();

        return this;
    },

    _setOptionsFromDOM: function(){
        var that = this, element = this.element, o = this.options;

        $.each(element.data(), function(key, value){
            if (key in o) {
                try {
                    o[key] = JSON.parse(value);
                } catch (e) {
                    o[key] = value;
                }
            }
        });
    },

    _create: function(){
        var that = this, element = this.element, o = this.options;
        var picker = this.picker;
        var i;

        if (o.distance < 1) {
            o.distance = 1;
        }

        if (o.hoursStep < 1) {o.hoursStep = 1;}
        if (o.hoursStep > 23) {o.hoursStep = 23;}

        if (o.minutesStep < 1) {o.minutesStep = 1;}
        if (o.minutesStep > 59) {o.minutesStep = 59;}

        if (o.secondsStep < 1) {o.secondsStep = 1;}
        if (o.secondsStep > 59) {o.secondsStep = 59;}

        if (element.val() === "" && (!Utils.isValue(o.value))) {
            o.value = (new Date()).format("%H:%M:%S");
        }

        this.value = Utils.strToArray(element.val() !== "" ? element.val() : String(o.value), ":");

        for(i = 0; i < 3; i++) {
            if (this.value[i] === undefined || this.value[i] === null) {
                this.value[i] = 0;
            } else {
                this.value[i] = parseInt(this.value[i]);
            }
        }

        this._normalizeValue();

        if (Metro.locales[o.locale] === undefined) {
            o.locale = METRO_LOCALE;
        }

        this.locale = Metro.locales[o.locale]['calendar'];

        this._createStructure();
        this._createEvents();
        this._set();

        Utils.exec(o.onTimePickerCreate, null, element[0]);
        element.fire("timepickercreate");
    },

    _normalizeValue: function(){
        var o = this.options;

        if (o.hoursStep > 1) {
            this.value[0] = Utils.nearest(this.value[0], o.hoursStep, true);
        }
        if (o.minutesStep > 1) {
            this.value[1] = Utils.nearest(this.value[1], o.minutesStep, true);
        }
        if (o.minutesStep > 1) {
            this.value[2] = Utils.nearest(this.value[2], o.secondsStep, true);
        }
    },

    _createStructure: function(){
        var that = this, element = this.element, o = this.options;
        var picker, hours, minutes, seconds, ampm, select, i;
        var timeWrapper, selectWrapper, selectBlock, actionBlock;

        var prev = element.prev();
        var parent = element.parent();
        var id = Utils.elementId("time-picker");

        picker = $("<div>").attr("id", id).addClass("wheel-picker time-picker " + element[0].className).addClass(o.clsPicker);

        if (prev.length === 0) {
            parent.prepend(picker);
        } else {
            picker.insertAfter(prev);
        }

        element.attr("readonly", true).appendTo(picker);


        timeWrapper = $("<div>").addClass("time-wrapper").appendTo(picker);

        if (o.hours === true) {
            hours = $("<div>").attr("data-title", this.locale['time']['hours']).addClass("hours").addClass(o.clsPart).addClass(o.clsHours).appendTo(timeWrapper);
        }
        if (o.minutes === true) {
            minutes = $("<div>").attr("data-title", this.locale['time']['minutes']).addClass("minutes").addClass(o.clsPart).addClass(o.clsMinutes).appendTo(timeWrapper);
        }
        if (o.seconds === true) {
            seconds = $("<div>").attr("data-title", this.locale['time']['seconds']).addClass("seconds").addClass(o.clsPart).addClass(o.clsSeconds).appendTo(timeWrapper);
        }

        selectWrapper = $("<div>").addClass("select-wrapper").appendTo(picker);

        selectBlock = $("<div>").addClass("select-block").appendTo(selectWrapper);
        if (o.hours === true) {
            hours = $("<ul>").addClass("sel-hours").appendTo(selectBlock);
            for (i = 0; i < o.distance; i++) $("<li>").html("&nbsp;").data("value", -1).appendTo(hours);
            for (i = 0; i < 24; i = i + o.hoursStep) {
                $("<li>").addClass("js-hours-"+i).html(i < 10 ? "0"+i : i).data("value", i).appendTo(hours);
            }
            for (i = 0; i < o.distance; i++) $("<li>").html("&nbsp;").data("value", -1).appendTo(hours);
        }
        if (o.minutes === true) {
            minutes = $("<ul>").addClass("sel-minutes").appendTo(selectBlock);
            for (i = 0; i < o.distance; i++) $("<li>").html("&nbsp;").data("value", -1).appendTo(minutes);
            for (i = 0; i < 60; i = i + o.minutesStep) {
                $("<li>").addClass("js-minutes-"+i).html(i < 10 ? "0"+i : i).data("value", i).appendTo(minutes);
            }
            for (i = 0; i < o.distance; i++) $("<li>").html("&nbsp;").data("value", -1).appendTo(minutes);
        }
        if (o.seconds === true) {
            seconds = $("<ul>").addClass("sel-seconds").appendTo(selectBlock);
            for (i = 0; i < o.distance; i++) $("<li>").html("&nbsp;").data("value", -1).appendTo(seconds);
            for (i = 0; i < 60; i = i + o.secondsStep) {
                $("<li>").addClass("js-seconds-"+i).html(i < 10 ? "0"+i : i).data("value", i).appendTo(seconds);
            }
            for (i = 0; i < o.distance; i++) $("<li>").html("&nbsp;").data("value", -1).appendTo(seconds);
        }

        selectBlock.height((o.distance * 2 + 1) * 40);

        actionBlock = $("<div>").addClass("action-block").appendTo(selectWrapper);
        $("<button>").attr("type", "button").addClass("button action-ok").html(o.okButtonIcon).appendTo(actionBlock);
        $("<button>").attr("type", "button").addClass("button action-cancel").html(o.cancelButtonIcon).appendTo(actionBlock);


        element[0].className = '';
        if (o.copyInlineStyles === true) {
            for (i = 0; i < element[0].style.length; i++) {
                picker.css(element[0].style[i], element.css(element[0].style[i]));
            }
        }

        if (o.showLabels === true) {
            picker.addClass("show-labels");
        }

        this.picker = picker;
    },

    _createEvents: function(){
        var that = this, element = this.element, o = this.options;
        var picker = this.picker;

        picker.on(Metro.events.start, ".select-block ul", function(e){

            if (e.changedTouches) {
                return ;
            }

            var target = this;
            var pageY = Utils.pageXY(e).y;

            $(document).on(Metro.events.move + "-picker", function(e){

                target.scrollTop -= o.scrollSpeed * (pageY  > Utils.pageXY(e).y ? -1 : 1);

                pageY = Utils.pageXY(e).y;
            });

            $(document).on(Metro.events.stop + "-picker", function(e){
                $(document).off(Metro.events.move + "-picker");
                $(document).off(Metro.events.stop + "-picker");
            });
        });

        picker.on(Metro.events.click, function(e){
            if (that.isOpen === false) that.open();
            e.stopPropagation();
        });

        picker.on(Metro.events.click, ".action-ok", function(e){
            var h, m, s, a;
            var sh = picker.find(".sel-hours li.active"),
                sm = picker.find(".sel-minutes li.active"),
                ss = picker.find(".sel-seconds li.active");

            h = sh.length === 0 ? 0 : sh.data("value");
            m = sm.length === 0 ? 0 : sm.data("value");
            s = ss.length === 0 ? 0 : ss.data("value");

            that.value = [h, m, s];
            that._normalizeValue();
            that._set();

            that.close();
            e.stopPropagation();
        });

        picker.on(Metro.events.click, ".action-cancel", function(e){
            that.close();
            e.stopPropagation();
        });

        var scrollLatency = 150;
        $.each(['hours', 'minutes', 'seconds'], function(){
            var part = this, list = picker.find(".sel-"+part);

            list.on("scroll", function(){
                if (that.isOpen) {
                    if (that.listTimer[part]) {
                        clearTimeout(that.listTimer[part]);
                        that.listTimer[part] = null;
                    }

                    if (!that.listTimer[part]) that.listTimer[part] = setTimeout(function () {

                        var target, targetElement, scrollTop, delta;

                        that.listTimer[part] = null;

                        target = Math.round((Math.ceil(list.scrollTop()) / 40));

                        targetElement = list.find(".js-" + part + "-" + target);
                        scrollTop = targetElement.position().top - (o.distance * 40) + list.scrollTop() - 1;

                        list.find(".active").removeClass("active");

                        list[0].scrollTop = scrollTop;
                        targetElement.addClass("active");
                        Utils.exec(o.onScroll, [targetElement, list, picker], list[0]);

                    }, scrollLatency);
                }
            })
        });
    },

    _set: function(){
        var that = this, element = this.element, o = this.options;
        var picker = this.picker;
        var h = "00", m = "00", s = "00";

        if (o.hours === true) {
            h = parseInt(this.value[0]);
            if (h < 10) {
                h = "0"+h;
            }
            picker.find(".hours").html(h);
        }
        if (o.minutes === true) {
            m = parseInt(this.value[1]);
            if (m < 10) {
                m = "0"+m;
            }
            picker.find(".minutes").html(m);
        }
        if (o.seconds === true) {
            s = parseInt(this.value[2]);
            if (s < 10) {
                s = "0"+s;
            }
            picker.find(".seconds").html(s);
        }

        element.val([h, m, s].join(":")).trigger("change");

        Utils.exec(o.onSet, [this.value, element.val()], element[0]);
        element.fire("set", {
            val: this.value,
            elementVal: element.val()
        });
    },

    open: function(){
        var that  = this, element = this.element, o = this.options;
        var picker = this.picker;
        var h, m, s;
        var h_list, m_list, s_list;
        var items = picker.find("li");
        var select_wrapper = picker.find(".select-wrapper");
        var select_wrapper_in_viewport, select_wrapper_rect;
        var h_item, m_item, s_item;

        select_wrapper.parent().removeClass("for-top for-bottom");
        select_wrapper.show(0);
        items.removeClass("active");

        select_wrapper_in_viewport = Utils.inViewport(select_wrapper);
        select_wrapper_rect = Utils.rect(select_wrapper);

        if (!select_wrapper_in_viewport && select_wrapper_rect.top > 0) {
            select_wrapper.parent().addClass("for-bottom");
        }

        if (!select_wrapper_in_viewport && select_wrapper_rect.top < 0) {
            select_wrapper.parent().addClass("for-top");
        }

        var animateList = function(list, item){
            list.scrollTop(0).animate({
                scrollTop: item.position().top - (o.distance * 40) + list.scrollTop()
            }, 100);
        };

        if (o.hours === true) {
            h = parseInt(this.value[0]);
            h_list = picker.find(".sel-hours");
            h_item = h_list.find("li.js-hours-" + h).addClass("active");
            animateList(h_list, h_item);
        }
        if (o.minutes === true) {
            m = parseInt(this.value[1]);
            m_list = picker.find(".sel-minutes");
            m_item = m_list.find("li.js-minutes-" + m).addClass("active");
            animateList(m_list, m_item);
        }
        if (o.seconds === true) {
            s = parseInt(this.value[2]);
            s_list = picker.find(".sel-seconds");
            s_item = s_list.find("li.js-seconds-" + s).addClass("active");
            animateList(s_list, s_item);
        }

        this.isOpen = true;

        Utils.exec(o.onOpen, [this.value], element[0]);
        element.fire("open", {
            val: this.value
        });
    },

    close: function(){
        var picker = this.picker, o = this.options, element = this.element;
        picker.find(".select-wrapper").hide(0);
        this.isOpen = false;
        Utils.exec(o.onClose, [this.value], element[0]);
        element.fire("close", {
            val: this.value
        });
    },

    _convert: function(t){
        var result;

        if (Array.isArray(t)) {
            result = t;
        } else if (typeof  t.getMonth === 'function') {
            result = [t.getHours(), t.getMinutes(), t.getSeconds()];
        } else if (Utils.isObject(t)) {
            result = [t.h, t.m, t.s];
        } else {
            result = Utils.strToArray(t, ":");
        }

        return result;
    },

    val: function(t){
        if (t === undefined) {
            return this.element.val();
        }
        this.value = this._convert(t);
        this._normalizeValue();
        this._set();
    },

    time: function(t){
        if (t === undefined) {
            return {
                h: this.value[0],
                m: this.value[1],
                s: this.value[2]
            }
        }

        this.value = this._convert(t);
        this._normalizeValue();
        this._set();
    },

    date: function(t){
        if (t === undefined || typeof t.getMonth !== 'function') {
            var ret = new Date();
            ret.setHours(this.value[0]);
            ret.setMinutes(this.value[1]);
            ret.setSeconds(this.value[2]);
            ret.setMilliseconds(0);
            return ret;
        }

        this.value = this._convert(t);
        this._normalizeValue();
        this._set();
    },

    changeAttribute: function(attributeName){
        var that = this, element = this.element;

        var changeValueAttribute = function(){
            that.val(element.attr("data-value"));
        };

        switch (attributeName) {
            case "data-value": changeValueAttribute(); break;
        }
    },

    destroy: function(){
        var element = this.element;
        var picker = this.picker;
        var parent = element.parent();

        $.each(['hours', 'minutes', 'seconds'], function(){
            picker.find(".sel-"+this).off("scroll");
        });

        picker.off(Metro.events.start, ".select-block ul");
        picker.off(Metro.events.click);
        picker.off(Metro.events.click, ".action-ok");
        picker.off(Metro.events.click, ".action-cancel");

        element.insertBefore(parent);
        parent.remove();
    }

};

Metro.plugin('timepicker', TimePicker);

$(document).on(Metro.events.click, function(e){
    $.each($(".time-picker"), function(){
        $(this).find("input").data("timepicker").close();
    });
});




return METRO_INIT === true ? Metro.init() : Metro;
}));