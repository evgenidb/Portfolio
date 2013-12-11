var Class = (function () {
    'use strict';

    // Prototype OOP
    if (!Object.create) {
        Object.create = function(obj) {
            function f() {}
            f.prototype = obj;
            return new f();
        };
    }

    if (!Object.prototype.extend) {
        Object.prototype.extend = function(properties) {
            // NOTE: This still doesn't work, although it's better than in the Demo
            function f() {}
            f.prototype = Object.create(this);
            for (var prop in properties) {
                f.prototype[prop] = properties[prop];
            }
            f.prototype._super = Object.create(this);
            return new f();
        };
    }

    // Classic OOP
    function createClass(properties) {
        var f = function () {
            //This is an addition to enable super (base) class with inheritance
            if (this._superConstructor) {
                this._super = new this._superConstructor(arguments);
            }
            this.init.apply(this, arguments);
        };
        for (var prop in properties) {
            f.prototype[prop] = properties[prop];
        }

        if (!f.prototype.init) {
            f.prototype.init = function () {};
        }
        return f;
    }

    Function.prototype.inherit = function (parent) {
        var oldPrototype = this.prototype;
        this.prototype = new parent();
        this.prototype._superConstructor = parent;

        for (var prop in oldPrototype) {
            this.prototype[prop] = oldPrototype[prop];
        }
    };

    return {
        create: createClass
    };
}());