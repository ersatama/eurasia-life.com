/**
 * @version: 0.0.1
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
// https://github.com/umdjs/umd/blob/master/templates/jqueryPlugin.js
// Uses CommonJS, AMD or browser globals to create a jQuery plugin.
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = function (root, jQuery) {
            if (jQuery === undefined) {
                // require('jQuery') returns a factory that requires window to
                // build a jQuery instance, we normalize how we use modules
                // that require this pattern but the window provided is a noop
                // if it's defined (how jquery works)
                if (typeof window !== 'undefined') {
                    jQuery = require('jquery');
                }
                else {
                    jQuery = require('jquery')(root);
                }
            }
            factory(jQuery);
            return jQuery;
        };
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    'use strict';

    $.fn.selectToLinks = function () {
        $(this).each(function (index, select) {
            _select($(select));
        });
        return true;
    };

    var o = {
        containerClass: 'select-to-links',
        itemClass: 'select-to-links__item',
        activeItemClass: 'select-to-links__item_active',
        linkClass: 'select-to-links__link',
    };

    function _select($select) {
        var $container = $('<ul/>', {
            class: o.containerClass,
        });

        var selectedValue = $select.val();

        $select.hide().find('option').each(function () {
            var $option = $(this);
            var selected = $option.val() === selectedValue;
            $('<a/>', {
                href: '#',
                text: $option.text(),
                class: o.linkClass,
                click: function () {
                    $container.find('.' + o.activeItemClass).removeClass(o.activeItemClass);
                    $(this).closest('.' + o.itemClass).addClass(o.activeItemClass);
                    $select.val($option.val());
                    return false;
                }
            }).appendTo($('<li/>', {class: o.itemClass + (selected ? ' ' + o.activeItemClass : '')}).appendTo($container));
        });
        $select.after($container);
    }
}));
