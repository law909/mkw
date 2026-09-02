/**
 * Mattkarb konfiguráció.
 *
 *     const mattkarbconfig = new MattkarbConfig({entityName: 'orszag'});
 *
 */
class MattkarbConfig {

    static get URLPATHS() {
        return {
            viewUrl: 'getkarb',
            newWindowUrl: 'viewkarb',
            saveUrl: 'save'
        };
    }

    /**
     * A jquery.mattkarb.js baseOptions-e, az URL-ek az entitásnévből származtatva.
     * Az `independent: true` a mattable `karb:` ágán ártalmatlan: onnan a mattable csak
     * a newWindowUrl-t és a saveUrl-t olvassa.
     *
     * @param {string} [entityName] az entitás admin-neve (pl. 'orszag')
     */
    static defaults(entityName) {
        const noop = function () {
        };
        const values = {
            name: 'egyed',
            animationSpeed: 50,
            independent: true,
            container: '#mattkarb',
            header: '#mattkarb-header',
            footer: '#mattkarb-footer',
            form: '#mattkarb-form',
            tab: '#mattkarb-tabs',
            page: '.mattkarb-page',
            titlebar: '.mattkarb-titlebar',
            cancel: '#mattkarb-cancelbutton',
            ok: '#mattkarb-okbutton',
            beforeShow: noop,
            beforeHide: noop,
            onSubmit: noop,
            afterSave: null,
            onCancel: noop
        };
        if (entityName) {
            Object.keys(MattkarbConfig.URLPATHS).forEach(function (key) {
                values[key] = '/admin/' + entityName + '/' + MattkarbConfig.URLPATHS[key];
            });
        }
        return values;
    }

    /**
     * @param {object} [options] a hívó beállításai; az alapértelmezésekre olvad, és nyer
     */
    constructor(options) {
        const opts = options || {};
        MattkarbConfig.merge(this, MattkarbConfig.defaults(opts.entityName));
        MattkarbConfig.merge(this, opts);
    }

    /**
     * @param {object} [options]
     * @return {MattkarbConfig}
     */
    extend(options) {
        return new MattkarbConfig(MattkarbConfig.merge(MattkarbConfig.merge({}, this), options || {}));
    }

    toObject() {
        return MattkarbConfig.merge({}, this);
    }

    /**
     * @return {object} a cél objektum
     */
    static merge(target, source) {
        Object.keys(source || {}).forEach(function (key) {
            const value = source[key];
            if (MattkarbConfig.isPlainObject(value)) {
                if (!MattkarbConfig.isPlainObject(target[key])) {
                    target[key] = {};
                }
                MattkarbConfig.merge(target[key], value);
            } else {
                target[key] = value;
            }
        });
        return target;
    }

    static isPlainObject(value) {
        return !!value
            && typeof value === 'object'
            && !Array.isArray(value)
            && (Object.getPrototypeOf(value) === Object.prototype || Object.getPrototypeOf(value) === null);
    }

}
