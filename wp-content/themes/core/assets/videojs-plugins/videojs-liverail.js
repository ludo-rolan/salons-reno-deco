/*!

   LiveRail Plugin for VideoJS 1.0.0
   Build Date: Wed, 16 Jul 2014 14:21:56 -0700

*/
(function() {
    var g, f, e, d, h, b, c;
    if(window.LiveRail === undefined) {
        window.LiveRail = {}
    }
    if(window.LiveRail.Framework !== undefined) {
        return
    }
    window.LiveRail.Framework = {
        widget: {}
    };
    window.LiveRail.Framework.build = {
        version: "1.1",
        timestamp: "201403201916",
        date: "Thu, Mar 20, 2014 19:16:23 -0700"
    };
    e = [];
    g = {};
    (function() {
        var i = {
            Array: {},
            Date: {},
            Object: {}
        };
        if(window.Array && window.Array.isArray !== undefined) {
            i.Array.isArray = window.Array.isArray
        } else {
            i.Array.isArray = function(j) {
                return(Object.prototype.toString.call(j) === "[object Array]")
            }
        }
        if(window.Date && window.Date.now !== undefined) {
            i.Date.now = window.Date.now
        } else {
            i.Date.now = function() {
                return(new Date()).getTime()
            }
        }
        if(window.Object && window.Object.defineProperties !== undefined) {
            i.Object.defineProperties = window.Object.defineProperties
        } else {
            i.Object.defineProperties = function(l, k) {
                var j;
                if(l && typeof l === "object" && k && typeof k === "object") {
                    for(j in k) {
                        if(k.hasOwnProperty(j) && k[j].hasOwnProperty("value")) {
                            l[j] = k[j].value
                        }
                    }
                }
            }
        }
        if(window.Object && window.Object.create !== undefined) {
            i.Object.create = window.Object.create
        } else {
            i.Object.create = function(m, l) {
                var j, k;
                j = function() {};
                if(m) {
                    j.prototype = m
                }
                k = new j();
                if(l && typeof l === "object") {
                    i.Object.defineProperties(k, l)
                }
                return k
            }
        }
        if(window.Object && window.Object.keys !== undefined) {
            i.Object.keys = window.Object.keys
        } else {
            i.Object.keys = (function() {
                var l = window.Object.prototype.hasOwnProperty,
                    m = !({
                        toString: null
                    }).propertyIsEnumerable("toString"),
                    k = ["toString", "toLocaleString", "valueOf", "hasOwnProperty", "isPrototypeOf", "propertyIsEnumerable", "constructor"],
                    j = k.length;
                return function(p) {
                    var o, n, q;
                    if((typeof p !== "object" && typeof p !== "function") || p === null) {
                        throw new TypeError("Object.keys called on non-object")
                    }
                    n = [];
                    for(q in p) {
                        if(p.hasOwnProperty(q)) {
                            n.push(q)
                        }
                    }
                    if(m) {
                        for(o = 0; o < j; o += 1) {
                            if(p.hasOwnProperty(k[o])) {
                                n.push(k[o])
                            }
                        }
                    }
                    return n
                }
            }())
        }
        if(window.Object && window.Object.freeze !== undefined) {
            i.Object.seal = window.Object.seal;
            i.Object.freeze = window.Object.freeze
        } else {
            i.Object.seal = function() {};
            i.Object.freeze = function() {}
        }
        i.Object.freeze(i.Array);
        i.Object.freeze(i.Date);
        i.Object.freeze(i.Object);
        i.Object.freeze(i);
        i.Object.defineProperties(g, {
            es5: {
                value: i,
                enumerable: true
            }
        })
    }());

    function a() {
        if(h === undefined) {
            h = 0
        }
        h += 1;
        return h
    }
    f = function() {
        if(typeof window.console !== "object" || c === 0) {
            return
        }
        var k, m, r, j, p, l, n, o;
        k = this.context;
        m = (this === window);
        n = new Date();
        r = this.priority;
        if(!k.is_int(r)) {
            r = 3
        }
        j = this.method;
        if(typeof j !== "string") {
            j = "log"
        }
        p = [""];
        for(l = 0; l < arguments.length; l += 1) {
            p.push(arguments[l])
        }
        if(c === undefined) {
            if(document.location.href.match(/^https?:\/\/[A-Za-z0-9.\-_]+\.liverail\.com/)) {
                c = parseInt(k.readCookie().debug, 10);
                if(!c) {
                    c = 0
                }
                b = k.readCookie().debugIdFilter;
                if(b) {
                    b = decodeURIComponent(k.readCookie().debugIdFilter)
                }
            } else {
                if(typeof window.LiveRail === "object" && window.LiveRail.debugOutput !== undefined) {
                    c = parseInt(window.LiveRail.debugOutput, 10);
                    b = window.LiveRail.debugIdFilter || ""
                }
            }
        }
        o = {
            context: k,
            priority: r,
            timestamp: n,
            method: j,
            args: p
        };
        if(c === undefined) {
            e.push(o);
            return
        }

        function q(v) {
            var t, i, w, u, s;
            i = v.context.id && v.context.id.fullname ? v.context.id.fullname : "";
            if(c < v.priority) {
                return
            }
            t = "";
            if(!m && v.context && v.context.id) {
                t = v.context.id.fullname;
                if(c > 2 && t) {
                    t += " " + v.context.id.instance
                }
            }
            v.args[0] = "LIVERAIL [" + k.date("H:i:s.", v.timestamp) + k.date("u", v.timestamp).substr(0, 3) + "]";
            if(typeof window.LiveRail === "object" && !window.LiveRail.debugFilterMessage && b) {
                window.LiveRail.debugFilterMessage = true;
                if(typeof window.console.warn === "function") {
                    window.console.warn(v.args[0], 'Debug ID Filter set to "' + b + '"')
                }
            }
            if(!i.match(new RegExp(b))) {
                return
            }
            if(t) {
                v.args[0] += "[" + t + "]"
            }
            if(v.method !== "purge") {
                if(typeof window.LiveRail.debugElement === "object" && window.LiveRail.debugElement !== null && window.LiveRail.debugElement.tagName) {
                    w = document.createElement("div");
                    u = "";
                    if(window.LiveRail.debugElement.offsetHeight === window.LiveRail.debugElement.scrollHeight || (window.LiveRail.debugElement.scrollTop + window.LiveRail.debugElement.offsetHeight) >= (window.LiveRail.debugElement.scrollHeight - 2)) {
                        s = true
                    }
                    o.context.each(v.args, function(x) {
                        if(u.length > 0) {
                            u += " "
                        }
                        u += x
                    });
                    w.innerHTML = u;
                    window.LiveRail.debugElement.appendChild(w);
                    if(s) {
                        window.LiveRail.debugElement.scrollTop = window.LiveRail.debugElement.scrollHeight
                    }
                }
                if(typeof window.console[v.method] === "function") {
                    window.console[v.method].apply(window.console, v.args)
                }
            }
        }
        if(e.length > 0) {
            for(l = 0; l < e.length; l += 1) {
                q(e[l])
            }
            e = []
        }
        if(Function.prototype.bind && window.console && typeof window.console.log === "object") {
            ["log", "info", "warn", "error", "assert", "dir", "clear", "profile", "profileEnd"].forEach(function(i) {
                window.console[i] = this.bind(window.console[i], window.console)
            }, Function.prototype.call)
        }
        q(o)
    };
    g.es5.Object.defineProperties(g, {
        getDebugLevel: {
            value: function() {
                return c || 0
            },
            enumerable: true
        },
        getDebugIdFilter: {
            value: function() {
                return b || ""
            },
            enumerable: true
        },
        info: {
            value: function() {
                f.apply({
                    context: this,
                    priority: 1,
                    method: "info"
                }, arguments)
            },
            enumerable: true
        },
        warn: {
            value: function() {
                f.apply({
                    context: this,
                    priority: 1,
                    method: "warn"
                }, arguments)
            },
            enumerable: true
        },
        error: {
            value: function() {
                f.apply({
                    context: this,
                    priority: 1,
                    method: "error"
                }, arguments)
            },
            enumerable: true
        },
        debug: {
            value: function() {
                f.apply({
                    context: this,
                    priority: 2,
                    method: "log"
                }, arguments)
            },
            enumerable: true
        },
        log: {
            value: function() {
                f.apply({
                    context: this,
                    priority: 1
                }, arguments)
            },
            enumerable: true
        },
        verbose: {
            value: function() {
                f.apply({
                    context: this,
                    priority: 3
                }, arguments)
            },
            enumerable: true
        }
    });
    g.es5.Object.defineProperties(g, {
        date: {
            value: function(q, o) {
                var k, p, n, j = /\\?([a-z])/gi,
                    i, l = function(s, r) {
                        s = s.toString();
                        return s.length < r ? l("0" + s, r, "0") : s
                    },
                    m = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                i = function(r, u) {
                    return n[r] ? n[r]() : u
                };
                n = {
                    d: function() {
                        return l(n.j(), 2)
                    },
                    D: function() {
                        return n.l().slice(0, 3)
                    },
                    j: function() {
                        return p.getDate()
                    },
                    l: function() {
                        return m[n.w()] + "day"
                    },
                    N: function() {
                        return n.w() || 7
                    },
                    S: function() {
                        var r = n.j();
                        return r < 4 | r > 20 && (["st", "nd", "rd"][r % 10 - 1] || "th")
                    },
                    w: function() {
                        return p.getDay()
                    },
                    z: function() {
                        var s = new Date(n.Y(), n.n() - 1, n.j()),
                            r = new Date(n.Y(), 0, 1);
                        return Math.round((s - r) / 86400000) + 1
                    },
                    W: function() {
                        var s = new Date(n.Y(), n.n() - 1, n.j() - n.N() + 3),
                            r = new Date(s.getFullYear(), 0, 4);
                        return l(1 + Math.round((s - r) / 86400000 / 7), 2)
                    },
                    F: function() {
                        return m[6 + n.n()]
                    },
                    m: function() {
                        return l(n.n(), 2)
                    },
                    M: function() {
                        return n.F().slice(0, 3)
                    },
                    n: function() {
                        return p.getMonth() + 1
                    },
                    t: function() {
                        return(new Date(n.Y(), n.n(), 0)).getDate()
                    },
                    L: function() {
                        var r = n.Y();
                        return r % 4 === 0 & r % 100 !== 0 | r % 400 === 0
                    },
                    o: function() {
                        var t = n.n(),
                            r = n.W(),
                            s = n.Y();
                        return s + (t === 12 && r < 9 ? 1 : t === 1 && r > 9 ? -1 : 0)
                    },
                    Y: function() {
                        return p.getFullYear()
                    },
                    y: function() {
                        return n.Y().toString().slice(-2)
                    },
                    a: function() {
                        return p.getHours() > 11 ? "pm" : "am"
                    },
                    A: function() {
                        return n.a().toUpperCase()
                    },
                    B: function() {
                        var t = p.getUTCHours() * 3600,
                            r = p.getUTCMinutes() * 60,
                            u = p.getUTCSeconds();
                        return l(Math.floor((t + r + u + 3600) / 86.4) % 1000, 3)
                    },
                    g: function() {
                        return n.G() % 12 || 12
                    },
                    G: function() {
                        return p.getHours()
                    },
                    h: function() {
                        return l(n.g(), 2)
                    },
                    H: function() {
                        return l(n.G(), 2)
                    },
                    i: function() {
                        return l(p.getMinutes(), 2)
                    },
                    s: function() {
                        return l(p.getSeconds(), 2)
                    },
                    u: function() {
                        return l(p.getMilliseconds() * 1000, 6)
                    },
                    e: function() {
                        throw "Not supported (see source code of date() for timezone on how to add support)"
                    },
                    I: function() {
                        var s = new Date(n.Y(), 0),
                            u = Date.UTC(n.Y(), 0),
                            r = new Date(n.Y(), 6),
                            t = Date.UTC(n.Y(), 6);
                        return((s - u) !== (r - t)) ? 1 : 0
                    },
                    O: function() {
                        var s = p.getTimezoneOffset(),
                            r = Math.abs(s);
                        return(s > 0 ? "-" : "+") + l(Math.floor(r / 60) * 100 + r % 60, 4)
                    },
                    P: function() {
                        var r = n.O();
                        return(r.substr(0, 3) + ":" + r.substr(3, 2))
                    },
                    T: function() {
                        return "UTC"
                    },
                    Z: function() {
                        return -p.getTimezoneOffset() * 60
                    },
                    c: function() {
                        return "Y-m-d\\TH:i:sP".replace(j, i)
                    },
                    r: function() {
                        return "D, d M Y H:i:s O".replace(j, i)
                    },
                    U: function() {
                        return p / 1000 | 0
                    }
                };
                k = function(s, r) {
                    p = (r === undefined ? new Date() : (r instanceof Date) ? new Date(r) : new Date(r * 1000));
                    return s.replace(j, i)
                };
                return k(q, o)
            },
            enumerable: true
        },
        detect: {
            value: function(j) {
                var i = {};
                if(typeof j === "object" && typeof j.feature === "string") {
                    i.feature = j.feature.replace(/^\s\s*/, "").replace(/\s\s*$/, "").toLowerCase();
                    switch(i.feature) {
                        case "flash":
                            i.present = false;
                            (function() {
                                var l, k, n;
                                if(window.navigator && window.navigator.plugins && window.navigator.plugins.length > 0) {
                                    l = "application/x-shockwave-flash";
                                    k = window.navigator.mimeTypes;
                                    if(k && k[l] && k[l].enabledPlugin && k[l].enabledPlugin.description) {
                                        i.present = true
                                    }
                                } else {
                                    if(window.ActiveXObject !== undefined) {
                                        try {
                                            n = new window.ActiveXObject("ShockwaveFlash.ShockwaveFlash")
                                        } catch(m) {
                                            n = {
                                                activeXError: true
                                            }
                                        }
                                        if(!n.activeXError) {
                                            i.present = true
                                        }
                                    }
                                }
                            }());
                            break;
                        case "html5":
                            i.present = !!document.createElement("video").canPlayType;
                            break;
                        case "svg":
                            if(document.implementation && document.implementation.hasFeature) {
                                i.present = document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1")
                            } else {
                                i.present = false
                            }
                            break
                    }
                    return i
                }
            },
            enumerable: true
        },
        each: {
            value: function(j, l) {
                var k, n, m;
                if(j && typeof l === "function") {
                    if(j.length !== undefined) {
                        n = j.length;
                        for(k = 0; k < n; k += 1) {
                            if(l(j[k], k) === false) {
                                break
                            }
                        }
                        return
                    }
                    m = g.es5.Object.keys(j);
                    n = m.length;
                    for(k = 0; k < n; k += 1) {
                        if(l(j[m[k]], m[k]) === false) {
                            break
                        }
                    }
                }
            },
            enumerable: true
        },
        eachKey: {
            value: function(i, j) {
                this.each(i, function(l, k) {
                    if(typeof j === "function") {
                        return j(k, l)
                    }
                })
            },
            enumerable: true
        },
        gmdate: {
            value: function(k, j) {
                var i = j === undefined ? new Date() : typeof j === "object" ? new Date(j) : new Date(j * 1000);
                j = Date.parse(i.toUTCString().slice(0, -4)) / 1000;
                return this.date(k, j)
            },
            enumerable: true
        },
        is_int: {
            value: function(i) {
                return i === +i && isFinite(i) && i % 1 === 0
            },
            enumerable: true
        },
        readCookie: {
            value: function() {
                var m, l, j = {},
                    k;
                k = document.cookie.split("; ");
                for(l = 0; l < k.length; l += 1) {
                    m = k[l].split("=");
                    if(m[1] !== undefined) {
                        j[m[0]] = decodeURIComponent(m[1])
                    } else {
                        j[m[0]] = ""
                    }
                }
                return j
            },
            enumerable: true
        }
    });
    d = function(i, j) {
        var l;
        if(!i) {
            return null
        }

        function k(m) {
            return function() {
                var n = "";

                function o(p) {
                    if(p.id && p.id.name && p.id.name.substr(0, 10) !== "Framework.") {
                        if(n.length > 0) {
                            n = "." + n
                        }
                        n = p.id.name + n
                    }
                    if(p.id.parent) {
                        o(p.id.parent)
                    }
                }
                o(m);
                return n
            }
        }
        l = g.es5.Object.create(g, {
            id: {
                value: {},
                enumerable: true
            },
            descendent: {
                value: function(p, o) {
                    var n, m;
                    n = g.es5.Object.create(this, o);
                    m = this;
                    g.es5.Object.defineProperties(n, {
                        id: {
                            value: {}
                        }
                    });
                    g.es5.Object.defineProperties(n.id, {
                        name: {
                            value: p
                        },
                        instance: {
                            value: a()
                        },
                        parent: {
                            value: m
                        },
                        fullname: {
                            get: k(n)
                        }
                    });
                    n.verbose("New descendent of " + n.id.parent.id.fullname, n);
                    return n
                },
                enumerable: true
            }
        });
        if(j && typeof j === "object") {
            g.es5.Object.defineProperties(l, j)
        }
        g.es5.Object.defineProperties(l.id, {
            name: {
                value: i,
                enumerable: true
            },
            instance: {
                value: a(),
                enumerable: true
            },
            fullname: {
                get: k(l),
                enumerable: true
            }
        });
        l.verbose("New descendent of window.LiveRail.Framework", l);
        return l
    };
    f.apply({
        context: g,
        priority: 2,
        method: "log"
    }, ["LiveRail.Framework version " + window.LiveRail.Framework.build.version + ", build " + window.LiveRail.Framework.build.timestamp + " (" + window.LiveRail.Framework.build.date + ")"]);
    window.LiveRail.Framework.messageQueue = {};
    g.es5.Object.defineProperties(window.LiveRail.Framework.messageQueue, {
        purge: {
            value: function() {
                f.apply({
                    context: g,
                    priority: 1,
                    method: "purge"
                })
            },
            enumerable: true
        }
    });
    window.LiveRail.Framework.descendent = d;
    if(window.LiveRail.onFrameworkLoad) {
        g.each(window.LiveRail.onFrameworkLoad, function(i) {
            if(typeof i === "function") {
                i()
            }
        });
        delete window.LiveRail.onFrameworkLoad
    }
}());
(function() {
    var e, a, d = {};

    function c(h) {
        var k, j, f, g, l;
        if(!h) {
            h = window.event
        }
        // if(!h.origin.match(/^https?:\/\/cdn-static(-secure)?\.liverail\.com/)) {
        //     return
        // }
        if(!h.data || typeof h.data !== "string" || h.data.substr(0, 20) !== "lrHandshakeResponse=") {
            return
        }
        k = h.data.substr(20);
        j = "";
        for(f = 0; f < k.length; f += 1) {
            j += String.fromCharCode(k.charCodeAt(f) - a.charCodeAt(f))
        }
        g = j.split("&");
        for(f = 0; f < g.length; f += 1) {
            l = g[f].split("=");
            if(l[1]) {
                switch(l[1]) {
                    case "true":
                        d[l[0]] = true;
                        break;
                    case "false":
                        d[l[0]] = false;
                        break;
                    default:
                        d[l[0]] = decodeURIComponent(l[1]);
                        break
                }
            }
        }
        if(e && e.parentNode === document.body) {
            document.body.removeChild(e)
        }
        if(d.debug !== undefined) {
            window.LiveRail.debugOutput = parseInt(d.debug, 10);
            window.LiveRail.debugIdFilter = d.debugIdFilter;
            if(window.LiveRail.Framework.messageQueue) {
                window.LiveRail.Framework.messageQueue.purge()
            }
        }
    }

    function b() {
        var f, h, g, wl;
        wl = window.location;

        if(typeof window.postMessage !== "function") {
            return
        }
        if(wl.protocol === "https:" || (wl.protocol !== "http:" && window.parent && window.parent.location.protocol === "https:")) {
            h = true
        }
        f = wl.protocol+'//'+wl.hostname+'/wp-content/themes/core/assets/videojs-plugins/handshake.html';
        a = "";
        for(g = 0; g < 50; g += 1) {
            a += Math.random().toString(36).substr(2, 5)
        }
        e = document.createElement("iframe");
        e.style.display = "none";
        e.src = f;
        document.body.appendChild(e);
        e.onload = function() {
            if(e.contentWindow && e.contentWindow.postMessage) {
                e.contentWindow.postMessage(a, f)
            } else {
                if(e && e.parentNode === document.body) {
                    document.body.removeChild(e)
                }
            }
        };
        if(window.addEventListener) {
            window.addEventListener("message", c, false)
        } else {
            if(window.attachEvent) {
                window.attachEvent("onmessage", c)
            }
        }
    }
    if(window.LiveRail.debugOutput === undefined && !document.location.href.match(/^https?:\/\/[A-Za-z0-9.\-_]+\.liverail\.com/)) {
        if(document.readyState !== "complete" || document.body === null) {
            if(window.addEventListener) {
                window.addEventListener("load", b, false)
            } else {
                if(window.attachEvent) {
                    window.attachEvent("onload", b)
                }
            }
        } else {
            b()
        }
    }
}());
(function(d, a, f) {
    var c = f.LiveRail = {},
        g, e, b, rw_b;
    g = f.LiveRail.extend = function(m) {
        var h, l, j;
        for(l = 1; l < arguments.length; l += 1) {
            h = arguments[l];
            for(j in h) {
                if(h.hasOwnProperty(j)) {
                    m[j] = h[j]
                }
            }
        }
        return m
    };
    e = c.defaults = {
        adTechOrder: ["Flash", "Html5"],
        timeout: 60000,
        prerollTimeout: 60000
    };
    b = function(n) {
        // console.log(this);
        var L = g({}, e, n || {}),
            F = this,
            y = LiveRail.Framework.descendent("VideoJSPlugin"),
            u = "2014-07-16 14:21",
            M = "1.0.0",
            G = (d.location.protocol === "https:" ? "https://cdn-static-secure" : "http://cdn-static") + ".liverail.com/js/LiveRail.AdManager-1.0.js",
            B = F.el().querySelector(".vjs-tech"),
            J = L.adTechOrder,
            w, j = {
                videoSlot: B,
                LR_INTEGRATION: "vjs"
            },
            K, z, t = false,
            C = false,
            D = false,
            s = false,
            r = a.getElementById("lr_debug");
        if(r) {
            LiveRail.debugElement = r
        }
        // // if(pub_ads.liverail!=='undefined')
        // console.log(L);
        F.ads(L);
        F.LiveRail = {
            player: F,
            tech: B,
            settings: L
        };

        function E(O, P) {
            if(O === "LR_ADMAP") {
                y.log("Retrieved LR_ADMAP", P)
            } else {
                if(O === "LR_LAYOUT_SKIN_MESSAGE") {
                    y.log("Retrieved LR_LAYOUT_SKIN_MESSAGE: " + P)
                } else {
                    if(O === "LR_ENVIRONMENT") {
                        y.warn("Warning: LR_ENVIRONMENT was passed by player with value=" + P);
                        j[O] = P
                    } else {
                        j[O] = P
                    }
                }
            }
        }

        function I() {
            var O = F.el();
            if(!K) {
                y.log("Creating additional swf DIV container for vpaid");
                K = a.createElement("div");
                K.style.position = "absolute";
                K.style.width = "100%";
                K.style.height = "100%";
                K.className = "lr_admanager";
                K.style.background = "#000000";
                y.log("Adding SWF div on top of first child", O);
                O.insertBefore(K, O.firstChild)
            }
            K.style.display = "block"
        }

        function k() {
            var O;
            if(K) {
                y.log("Removing additional swf div container");
                K.parentNode.removeChild(K);
                K = undefined;
                O = a.getElementsByClassName("vjs-control-bar")[0];
                O.style.zIndex = ""
            }
        }

        function o() {
            var Q = B.offsetWidth,
                O = B.offsetHeight,
                P;
            C = false;
            D = false;
            for(P = 0; P < J.length; P += 1) {
                w = J[P];
                if(f[w].isSupported()) {
                    y.log("LiveRail using engine: " + w);
                    j.LR_ENVIRONMENT = w.toLowerCase();
                    break
                }
            }
            if(w === "Html5" && B.tagName.toLowerCase() === "video") {
                C = true;
                y.log("Retrieved video slot", B);
                if(B.parentNode) {
                    j.slot = B.parentNode;
                    y.log("Using environmentVars.slot", j.slot)
                }
            } else {
                I();
                D = true;
                y.log("Using a private div", K);
                j.slot = K
            }
            y.log("retrieve runtime params: ", L);
            y.each(L, function(S, R) {
                if(R.substr(0, 3) === "LR_") {
                    E(R, S)
                }
            });
            if(!j.LR_ADMAP) {
                j.LR_ADMAP = "in::0"
            }
            y.log("VPAID initAd() ", j);
            z.initAd(Q, O, "normal", 512, undefined, j)
        }

        function H() {
            var O;
            if(D && K) {
                K.style.zIndex = 1000;
                K.style.background = "#000";
                O = a.getElementsByClassName("vjs-control-bar")[0];
                O.style.zIndex = 1001
            }
            y.log("vpaid.startAd()");
            z.startAd()
        }

        function N() {
            y.log(" - --------- VPAID AdLoaded ----------- - ");
            y.log("player state: ", F.ads.state);
            F.trigger("adstart");
            H()
        }

        function q() {
            y.log(" - --------- VPAID AdStopped ------------- - ");
            y.log("player state: ", F.ads.state);
            F.trigger("adend");
            k()
        }

        function v() {
            y.log(" - --------- VPAID AdError ----------- - ");
            y.log("player state: ", F.ads.state);
            F.trigger("adend");
            k()
        }

        function A() {
            y.log(" - --------- VPAID AdStarted ----------- - ");
            y.log("player state: ", F.ads.state)
        }

        function m() {
            y.log(" - --------- VPAID AdImpression ----------- - ");
            y.log("player state: ", F.ads.state)
        }

        function h() {
            y.log(" - --------- VPAID AdDurationChange ----------- - ");
            y.log("player state: ", F.ads.state)
        }

        function l() {
            y.log(" - --------- VPAID AdPaused ----------- - ");
            s = true
        }

        function p() {
            y.log(" - --------- VPAID AdPlaying ----------- - ");
            s = false
        }
        F.on("loadeddata", function(O) {
            y.log("ready at current playback position...", O);
            y.verbose(L)
        });
        F.on("readyforpreroll", function(O) {
            y.log("start preroll", O);
            y.verbose(L);
            o()
        });
        F.one("play", function(O) {
            y.log("playback has started!", O);
            y.verbose(L);
            F.on("play", function(P) {
                y.log("playback has resumed!", P);
                y.verbose(L);
                if(w === "Flash" && F.ads.state === "ad-playback") {
                    F.pause();
                    if(s === true) {
                        z.resumeAd()
                    }
                }
            })
        });
        F.on("pause", function(O) {
            y.log("playback has been paused!", O);
            y.verbose(L);
            if(w === "Flash" && F.ads.state === "ad-playback") {
                if(s === false) {
                    z.pauseAd()
                }
            }
        });
        F.on("stop", function(O) {
            y.log("playback has been stopped!", O);
            y.verbose(L);
            z.stopAd()
        });
        F.on("ended", function(O) {
            y.log("playback has ended!", O);
            y.verbose(L)
        });
        F.on("cuechange", function(O) {
            y.log("cue change!", O);
            y.verbose(L)
        });
        F.on("volumechange", function(P) {
            y.log("the volume changed", P);
            y.log("target's volume:", P.currentTarget.volume);
            var O = (P.currentTarget.muted) ? 0 : P.currentTarget.volume;
            if(w === "Flash" && F.ads.state === "ad-playback") {
                z.setAdVolume(O)
            }
        });
        F.on("adtimeout", function(P) {
            var O = (F.ads.state === "content-playback") ? L.prerollTimeout : L.timeout;
            y.warn("The videojs ad timeout has been triggered", P);
            y.log("timeout in seconds: ", O)
        });

        function x() {
            y.log("VPAID frame load complete");
            t = false;
            z.handshakeVersion("2.0");
            z.subscribe(N, "AdLoaded");
            z.subscribe(q, "AdStopped");
            z.subscribe(v, "AdError");
            z.subscribe(h, "AdDurationChange");
            z.subscribe(l, "AdPaused");
            z.subscribe(p, "AdPlaying");
            z.subscribe(m, "AdImpression");
            z.subscribe(A, "AdStarted");
            F.trigger("adsready")
        }

        function i(P) {
            var Q = a.createElement("iframe"),
                O;
            t = true;
            Q.style.display = "none";
            O = function() {
                var V, U, W, S;
                try {
                    S = Q.contentWindow;
                    W = S.document
                } catch(T) {
                    W = Q.document;
                    S = W.parentWindow
                }
                try {
                    V = W.createElement("script");
                    V.src = (d.location.protocol === "https:" ? "https://cdn-static-secure" : "http://cdn-static") + ".liverail.com/js/LiveRail.AdManager-1.0.js?ts=201308012110";
                    U = function() {
                        if(typeof S.getVPAIDAd === "function") {
                            z = S.getVPAIDAd();
                            if(r) {
                                S.LiveRail.debugElement = r
                            }
                            if(typeof P === "function") {
                                P()
                            }
                        }
                    };
                    if(V.readyState) {
                        V.onreadystatechange = function() {
                            if(this.readyState === "loaded" || this.readyState === "complete") {
                                U()
                            }
                        }
                    } else {
                        V.onload = function() {
                            U()
                        }
                    }
                    W.body.appendChild(V)
                } catch(R) {
                    t = false
                }
            };
            Q.onload = function() {
                O()
            };
            if(typeof Q.onload !== "function") {
                Q.onreadystatechange = function() {
                    if(this.readyState === "loaded" || this.readyState === "complete") {
                        O()
                    }
                }
            }
            a.body.appendChild(Q)
        }
        y.log("LiveRail Plugin for VideoJS version " + M + " date " + u);
        y.log("Plugin options", n);
        y.log("Loading the LiveRail VPAID component...");
        i(x)
    };
    // rw_b = function(n){
    //     console.log(this);
    // 	if(typeof n.liverail!=='undefined'){
	   //  	b(n.liverail,this);
	   //  }
    // 	if(typeof n.liverail2!=='undefined'){
	   //  	b(n.liverail2,this);
	   //  }
    // 	if(typeof n.liverail3!=='undefined'){
	   //  	b(n.liverail3,this);
	   //  }
    // };
    f.plugin("LiveRail", b)
}(window, document, videojs));
