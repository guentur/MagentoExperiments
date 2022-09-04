# Guentur_PulsestormJQueryWidget
My training practise by [this article](https://alanstorm.com/modifying-a-jquery-widget-in-magento-2/) 
titled _Modifying a jQuery Widget in Magento 2_

## Some interesting parts from the article mentioned above

Like its brethren _plugin_ and _module_, the word _widget_ has the unfortunate distinction of being a popular way to describe a bunch of computer code _without_ a corresponding strict definition of what a widget is.

Like Magento 1, Magento 2 has a “CMS Widget” system that allows developers to create user interfaces for data entry of structured content blocks. While an interesting system, that’s not what we’re here to talk about today.

The [jQuery widget system](https://learn.jquery.com/jquery-ui/widget-factory/how-to-use-the-widget-factory/) 
is a part of [jQuery UI](https://learn.jquery.com/jquery-ui/). 
It’s marketed as a way to develop your own user interface elements for jQuery UI, 
but long time readers of this website will recognize it for what it is: 
An object system built on top of javascript.

A good chunk of Magento’s default user interface is built-out [using custom jQuery widgets](http://devdocs.magento.com/guides/v2.1/javascript-dev-guide/widgets/jquery-widgets-about.html)

While this article is intended for anyone working with the Magento 2 system, 
it will help if you’ve already worked your way through our 
[Magento 2 Advanced Javascript](http://alanstorm.com/category/magento-2/#magento2-advanced-javascript) series 
(especially [the javascript init](http://alanstorm.com/magento_2_javascript_init_scripts/) 
and [javascript mixins](http://alanstorm.com/the-curious-case-of-magento-2-mixins/) articles), 
the [Serving Frontend Files](http://alanstorm.com/magento-2-frontend-files-serving/), 
[Adding Frontend Files to your Module](http://alanstorm.com/magento_2_adding_frontend_files_to_your_module/), 
and [RequireJS](http://alanstorm.com/magento_2_and_requirejs/) articles 
in our [Magento 2 for PHP MVC developers series](http://alanstorm.com/category/magento-2/#magento-2-mvc), 
and jQuery’s [five part Widget Factory series](https://learn.jquery.com/jquery-ui/widget-factory/).

### What are JQuery Widgets?
If you’re a **very** long time reader, 
you might remember my pre-Magento [four](http://alanstorm.com/objective_c_selector/) 
[part](http://alanstorm.com/objective_c_selector_part_2/) 
[series](http://alanstorm.com/objective_c_selector_part_3/) 
on [developing](http://alanstorm.com/objective_c_selector_part_4/) a jQuery plugin.

The widget system is, on one level, just another javascript object system. 
In jQuery, you create a widget definition with code that looks something like this
```js
jQuery.widget('ournamespace.ourPluginMethod', {
    _create:function(){
        //widget initilization code here, widget has
        //access to things like this.options, this.element
        //to access configuration and the matched dom node
    },
    hello:function(){
        console.log("Say Hello");
    }
});
```
The above code would make a method named `ourPluginMethod` available for jQuery client programmers.
```js
//instantiate a widget instance
jQuery('.some-node').ourPluginMethod({/* ... initial config ...*/});
```

When we call `jQuery.widget` — we’re creating a widget definition. 
This is similar to creating a class definition file in a traditional object system. 
When a developer says `jQuery('.some-node').ourPluginMethod`, 
this is similar to a developer instantiating an object using a class definition file. 
The jQuery widget system even allows you to call through to widget methods via a (slightly weird) API
```js
var widgetInstasnce = jQuery('#the-node').ourPluginMethod({/* ... initial config ...*/});

//call the `hello` method
widgetInstasnce.ourPluginMethod('hello');
```

#### JQuery Widget Namespace
One of the more confusing things about widgets are the namespace — `ournamespace` below
```
jQuery.widget('ournamespace.ourPluginMethod',
```

If you peek at the global jQuery object, you’ll find your widget definition object stored under your namespace.
```
console.log(jQuery.ournamespace.ourPluginMethod)
```

Widgets are a complex system — if you’re going to customize how the default Magento theme(s) behave you’ll want to learn them inside and out. 
However, the most important thing to understand about widgets is they’re just another javascript object system.

### Magento 2 and jQuery Widgets
So, that’s plain jQuery widgets _without_ Magento. 
Magento 2 offers users a [number of custom widgets built using the jQuery UI pattern](http://devdocs.magento.com/guides/v2.1/javascript-dev-guide/widgets/jquery-widgets-about.html).

Magento _defines_ widgets inside RequireJS modules. 
For example, Magento’s core code defines [the list widget](http://devdocs.magento.com/guides/v2.1/javascript-dev-guide/widgets/widget_list.html) in the `mage/list` module.
```js
//File: lib/web/mage/list.js
define([
    "jquery",
    'mage/template',
    "jquery/ui"
], function($, mageTemplate){
    "use strict";

    $.widget('mage.list', {        /*...*/});
    /*...*/
    return $.mage.list;
})
```

If you want to use the list widgets, you need to do something like this:
```js
requirejs([
    'jquery',
    'mage/list'
], function($, listWidget){
    $('#some-node').list({/* ... */});
})
```
You’ll notice we never actually use the `listWidget` variable in our program.
We need to **load** the `mage/list` module so that the widget gets defined directly in the jQuery object.

There are times when the core code _strays_ from this simple “one widget, one RequireJS module” pattern. 
For example, the [Menu](http://devdocs.magento.com/guides/v2.1/javascript-dev-guide/widgets/widget_menu.html) 
and [Navigation](http://devdocs.magento.com/guides/v2.1/javascript-dev-guide/widgets/widget_navigation.html) widgets 
are _both_ defined in the `mage/menu` RequireJS module.
```js
//File: vendor/magento/magento2-base/lib/web/mage/menu.js
define([
    "jquery",
    "matchMedia",
    "jquery/ui",
    "jquery/jquery.mobile.custom",
    "mage/translate"
], function ($, mediaCheck) {
    'use strict';

    $.widget(/*...*/);


    $.widget(/*...*/);

    return {
        menu: $.mage.menu,
        navigation: $.mage.navigation
    };
});
```

The `mage/menu` module also offers another example of something to watch out for. 
Magento often aliases its jQuery-widget-defining-RequireJS-modules. For example, you can see `mage/menu` aliased as `menu` here
```js
//File: vendor/magento/module-theme/view/frontend/requirejs-config.js
    "menu":                   "mage/menu",
```

#### Sometimes Magento defies all convention
Consider the [calendar widget](http://devdocs.magento.com/guides/v2.1/javascript-dev-guide/widgets/widget_calendar.html). 
You might assume that the calendar widget is defined via a RequireJS module named `mage/calendar`. 
They’d be right so far in that there’s a `lib/web/mage/calendar.js` file that Magento invokes as a RequireJS module named `mage/calendar`. 
You can see an example of that here.
```js
//File: vendor/magento/module-ui/view/base/web/js/lib/knockout/bindings/datepicker.js
define([
    /* ... */
    'mage/calendar'
    /* ... */        
],
/* ... */
```

However, the `calendar.js` file is not actually a RequireJS module. 
Instead, it’s an immediately invoked anonymous callback function that defines both the `mage.dateRange` and `mage.calendar` widget.
```js
//File: vendor/magento/magento2-base/lib/web/mage/calendar.js

(function (factory) {
    'use strict';

    if (typeof define === 'function' && define.amd) {
        define([
            'jquery',
            'jquery/ui',
            'jquery/jquery-ui-timepicker-addon'
        ], factory);
    } else {
        factory(window.jQuery);
    }
}(function ($) {
    /* ... */
    return {
        dateRange:  $.mage.dateRange,
        calendar:   $.mage.calendar
    };        
}));
```
This callback style allows a developer to use the `lib/web/mage/calendar.js` file as both a RequireJS module 
_or_ as a bog-standard `<script src=""></script>` javascript include. 
This comes at the cost of some confusion for developers coming [along later]() (i.e. us).

## Instantiating Widgets with Magento 2
As we previously mentioned — when a developer calls the `jQuery.widget` method
```js
$.widget('foo.someWidget', /*...*/);
```

they’re creating a widget’s definition — similar to a PHP/Java/C# developer defining a class. When a developer **uses** the widget
```js
$(function(){
    /* ... */
    $('#someNode').someWidget(/*...*/);   
});
```

they’re telling jQuery to use the `foo.someWidget` definition to create or _instantiate_ the widget, similar to how a PHP/Java/C# developer might instantiate an object from a class
```php
$object = new Object;
```

While it’s possible to use these Magento 2 defined widgets in the same way
```js
requirejs([
    'jquery',
    'mage/list'
], function($, listWidget){
    $('#some-node').list({/* ... config ... */});
})
```

#### Magento 2 offers two new ways of instantiating widget objects
the `data-mage-init` attributes, 
and the `x-magento-init` script tags. 
We covered both in our [Javascript Init Scripts](http://alanstorm.com/magento_2_javascript_init_scripts/) article. 
It turns out that _both_ `data-mage-init` and the `x-magento-init` form _with_ a DOM node (not the `*` form) are _widget_ compatible.
```html
<div id="some-node" data-mage-init='{"mage/list":{/* ... config ... */}}'></div>
```

and it’s equivalent to

```js
$('#some-node').list({/* ... config ... */});    
```

This works because the `mage/list` module (and other Magento 2 “widget modules”) **returns** the widget callback that jQuery creates (`$.mage.list` below)

```js
//File: lib/web/mage/list.js
define([
    "jquery",
    'mage/template',
    "jquery/ui"
], function($, mageTemplate){
    "use strict";

    $.widget('mage.list', {        /*...*/});
    /*...*/
    return $.mage.list;
})
```

and the `data-mage-init` and `x-magento-init` techniques expect a RequireJS module that returns a function with the same signature as a jQuery widget callback. 

> In fact, it’s probably safe to say that both `data-mage-init` and `x-magento-init` were designed to work with widgets initially, 
> and it was only later that they were adopted (by the UI Component system, for one) 
> as a way of invoking javascript with server side rendered JSON objects.

Here’s one example from the home page
```js
<ul class="dropdown switcher-dropdown" data-mage-init='{"dropdownDialog":{
        "appendTo":"#switcher-currency > .options",
        "triggerTarget":"#switcher-currency-trigger",
        "closeOnMouseLeave": false,
        "triggerClass":"active",
        "parentClass":"active",
        "buttons":null}}'> 

    <!-- ... -->
    </ul>
```
This `data-mage-init` attribute invokes the `dropdownDialog` RequireJS module

and if we look at the source for the `mage/dropdown` module
```js
//File: vendor/magento/magento2-base/lib/web/mage/dropdown.js
define([
    "jquery",
    "jquery/ui",
    "mage/translate"
], function($){
    'use strict';

    var timer = null;
    /**
     * Dropdown Widget - this widget is a wrapper for the jQuery UI Dialog
     */
    $.widget('mage.dropdownDialog', $.ui.dialog, {/* ... */});

    return $.mage.dropdownDialog;
});
```
we see this module both defines, and then returns, the `mage.dropdownDialog` widget.



