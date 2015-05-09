Kelas = function () {
    var c = function () {
        if (this.initialize) {
            this.initialize.apply(this, arguments);
        }
    }

    function Extend(dst, src) {
        for (var i in src) {
            try {
                dst[i] = src[i];
            } catch (e) {
            }
        }
        return dst;
    }

    c.prototype = Object.create(Kelas.prototype);
    for (var i = 0; i < arguments.length; i++) {
        var a = arguments[i];
        if (a.prototype) {
            c.prototype = new a();
        } else {
            Extend(c.prototype, a);
        }
    }
    c.prototype.constructor = c;
    Extend(c, c.prototype);
    return c;
}

DStorage = (function () {
    var prefixData = 'dee_d_';
    var prefixInfo = 'dee_i_';

    return Kelas({
        initialize: function (name) {
            this.name = name;
            this.access = 0;
        },
        attr: function (key, val) {
            var s = localStorage.getItem(prefixInfo + this.name);
            var info = s ? JSON.parse(s) : {lastUpdate: 1};
            if (val === undefined) {
                return info[key];
            } else {
                info[key] = val;
                localStorage.setItem(prefixInfo + this.name, JSON.stringify(info));
            }
        },
        _refresh: function () {
            if (this.access < this.attr('lastUpdate')) {
                var s = localStorage.getItem(prefixData + this.name);
                this._items = s ? JSON.parse(s) : {};
                this.access = (new Date()).getTime();
            }
        },
        _save: function () {
            localStorage.setItem(prefixData + this.name, JSON.stringify(this._items));
            this.access = (new Date()).getTime();
            this.attr('lastUpdate', this.access);
        },
        all: function () {
            this._refresh();
            return this._items;
        },
        get: function (id) {
            this._refresh();
            return this._items[id];
        },
        replace: function (items) {
            this._items = items;
            this._save();
        },
        save: function (item, id) {
            this._refresh();
            if (id === undefined) {
                var last = this.attr('lastId');
                id = last === undefined ? 1 : parseInt(last) + 1;
            }
            this._items[id] = item;
            this.attr('lastId', id);
            this._save();
            return id;
        },
        update: function (id, item) {
            this._refresh();
            this._items[id] = item;
            this._save();
        },
        remove: function (id) {
            this._refresh();
            if (this._items[id]) {
                delete this._items[id];
            }
            this._save();
        }
    });
})();

DQueue = (function () {
    return Kelas(DStorage, {
        initialize: function (name, callback, interval) {
            DStorage.initialize.call(this, name);
            if (callback !== undefined) {
                this.callback = callback;
            }
            this.interval = interval || 1000;

            if (callback) {
                setInterval(function () {
                    this.push();
                }, this.interval);
            }
        },
        push: function (callback) {
            callback = callback || this.callback;
            if (callback) {
                var items = this.all();
                if (!this.attr('onPush')) {
                    var item, key;

                    for (key in items) {
                        item = items[key];
                        break;
                    }
                    if (item !== undefined) {
                        this.attr('onPush', true);
                        callback.call(this, item, function (success) {
                            this.attr('onPush', false);
                            if (success) {
                                this.remove(key);
                            }
                        });
                    }
                }
            }
        }
    });
})();

DMaster = (function () {
    return Kelas(DStorage, {
        initialize: function (name, callback, interval) {
            DStorage.initialize.call(this, name);
            if (callback !== undefined) {
                this.callback = callback;
            }
            this.interval = interval || 3600000;

            if (callback) {
                setInterval(function () {
                    this.pull();
                }, this.interval);
            }
        },
        pull: function (callback) {
            callback = callback || this.callback;
            if (callback) {
                if (!this.attr('onPull')) {
                    this.attr('onPull', true);
                    callback.call(this, function (success, items) {
                        this.attr('onPull', false);
                        if (success) {
                            this.replace(items);
                        }
                    });

                }
            }
        }
    });
})();