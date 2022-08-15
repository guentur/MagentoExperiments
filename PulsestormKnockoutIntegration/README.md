# Guentur_PulsestormKnockoutIntegration
My training practise by [this article](https://alanstorm.com/magento_2_knockoutjs_integration/)
titled Magento 2: KnockoutJS Integration

## Some interesting parts from the article mentioned above:
KnockoutJS itself has no native concept of data storage, 
and like many modern javascript frameworks it was designed to work best with a service only backend. 
i.e. [KnockoutJS’s “Model” is some other framework making AJAX requests](http://knockoutjs.com/documentation/json-data.html) 
to populate view model values.

KnockoutJS has no opinion on how you include it in your projects, 
or how you organize your code (although the documentation makes it clear the KnockoutJS team members are fans of [RequireJS](http://requirejs.org/)).

### RequireJS Initialization
In [our previous article](http://alanstorm.com/magento_2_javascript_init_scripts), 
and in [the official KnockoutJS tutorials](http://learn.knockoutjs.com/), KnockoutJS initialization is a simple affair.
```js
object = SomeViewModelConstructor();
ko.applyBindings(object);
```

For tutorial applications, this makes sense. However, if you were to keep all your view model logic, custom bindings, components, etc. in a single chunk of code, KnockoutJS would quickly grow un-manageable.

Instead, Magento’s core team has created the `Magento_Ui/js/lib/ko/initialize` RequireJS module that, 
when listed as a dependency, will perform any and all KnockoutJS initialization. You can use this module like this
```js
requirejs(['Magento_Ui/js/lib/ko/initialize'], function(){
    //your program here
});
```

### KnockoutJS Initialization
If we take a look at the source of of the `Magento_Ui/js/lib/ko/initialize` module

```
#File: vendor/magento/module-ui/view/base/web/js/lib/ko/initialize.js
define([
    'ko',
    './template/engine',
    'knockoutjs/knockout-repeat',
    'knockoutjs/knockout-fast-foreach',
    'knockoutjs/knockout-es5',
    './bind/scope',
    './bind/staticChecked',
    './bind/datepicker',
    './bind/outer_click',
    './bind/keyboard',
    './bind/optgroup',
    './bind/fadeVisible',
    './bind/mage-init',
    './bind/after-render',
    './bind/i18n',
    './bind/collapsible',
    './bind/autoselect',
    './extender/observable_array',
    './extender/bound-nodes'
], function (ko, templateEngine) {
    'use strict';

    ko.setTemplateEngine(templateEngine);
    ko.applyBindings();
});
```
We see a program that’s relatively simple, but that also includes nineteen other modules. 
Covering what each of these modules does is beyond the scope of this article

The `knockoutjs/knockout-repeat`,`knockoutjs/knockout-fast-foreach`, and  
`knockoutjs/knockout-es5` modules are KnockoutJS community extras. None of these are formal RequireJS modules.

The modules that start with ./bind/* are Magento’s custom bindings for KnockoutJS. These are formal RequireJS modules, 
but do not actually return a module. Instead, each script manipulates the global ko object to add bindings to KnockoutJS. 

Hopefully Magento [gets us official documentation soon](https://github.com/magento/devdocs/issues/718).

The two `extender` modules are Magento core extensions to KnockoutJS’s functionality.

The `./template/engine` module returns a customized version of KnockoutJS’s template engine

### Magento KnockoutJS Templates
To review, in a stock KnockoutJS system, _templates_ are chunks of pre-written DOM/KnockoutJS code 
that you can use by referencing their `id`. 
These chunks are added to the HTML of the page via script tags, with a type of `text/html`
```
<script type="text/html" id="my_template">
    <h1 data-bind="text:title"></h1>
</script>
```

It presents a problem for a server side framework — how do you get the right templates rendered on a page? 
How can you be sure the template will be there without recreating it every-time?

Magento’s core engineers did better way to load KnockoutJS templates by **replacing** the native KnockoutJS template engine 
with the engine loaded from the `Magento_Ui/js/lib/ko/template/engine` RequireJS module.

Example:
```html
<!--File: app/code/Pulsestorm/KnockoutTutorial/view/frontend/templates/content.phtml-->
<div data-bind="template:'Pulsestorm_KnockoutTutorial/hello'"></div>
```
Reload the page with this template

### No View Model
Coming back to the `initialize.js` module, after Magento sets the custom template engine, 
Magento calls KnockoutJS’s `applyBindings` method. 
This kicks off rendering the current HTML page as a view.
```js
// File: vendor/magento/module-ui/view/base/web/js/lib/ko/initialize.js
ko.setTemplateEngine(templateEngine);
ko.applyBindings();
```
Magento called `applyBindings` **without** a view model.

The key to understanding what Magento is doing here is back up in our KnockoutJS initialization
```
#File: vendor/magento/module-ui/view/base/web/js/lib/ko/initialize.js
define([
    //...
    './bind/scope',
    //...
],
```
Magento’s KnockoutJS team created a custom KnockoutJS binding named `scope`.
When you invoke the scope element like this 
```html
<li class="greet welcome" ==data-bind="scope: 'customer'"==>
    <span data-bind="text: customer().fullname ? $t('Welcome, %1!').replace('%1', customer().fullname) : 'Default welcome msg!'"></span>
</li>
```
Magento will apply the customer view model to this tag and its descendants.

```
<script type="text/x-magento-init">
{
    "*": {
        "Magento_Ui/js/core/app": {
            "components": {
                "customer": {
                    "component": "Magento_Customer/js/view/customer"
                }
            }
        }
    }
}
</script>
```
As we know [from the first article in this series](http://alanstorm.com/knockoutjs_primer_for_magento_developers), 
when Magento encounters a `text/x-magento-init` script tag with an `*` attribute, it will
1.  Initialize the specified RequireJS module (`Magento_Ui/js/core/app`)
2.  Call the function returned by that module, passing in the data object

The `Magento_Ui/js/core/app` RequireJS module is a module that **instantiates KnockoutJS view models** to use with the `scope` attribute

So, for the `customer` key, Magento will run code that’s equivalent to the following.
```js
//gross over simplification
var ViewModelConstructor = requirejs('Magento_Customer/js/view/customer');
var viewModel = new ViewModelConstructor;
viewModelRegistry.save('customer', viewModel);
```

If we take a look at the implementation of the `scope` custom binding
```js
// #File: vendor/magento/module-ui/view/base/web/js/lib/ko/bind/scope.js
define([
    'ko',
    'uiRegistry',
    'jquery',
    'mage/translate'
], function (ko, registry, $) {
    'use strict';

    //...
        update: function(el, valueAccessor, allBindings, viewModel, bindingContext) {
            var component = valueAccessor(),
                apply = applyComponents.bind(this, el, bindingContext);

            if (typeof component === 'string') {
                registry.get(component, apply);
            } else if (typeof component === 'function') {
                component(apply);
            }
        }
    //...

});
```
It’s the `registry.get(component, apply);` line that fetches the named view model from the view model registry,
and then the following code is what actually applies the object as a view model in KnockoutJS

```js
// #File: vendor/magento/module-ui/view/base/web/js/lib/ko/bind/scope.js

//the component variable is our viewModel
function applyComponents(el, bindingContext, component) {
    component = bindingContext.createChildContext(component);

    ko.utils.extend(component, {
        $t: i18n
    });

    ko.utils.arrayForEach(el.childNodes, ko.cleanNode);

    ko.applyBindingsToDescendants(component, el);
}
```

The `registry` variable comes from the `uiRegistry` module, which is an alias for the `Magento_Ui/js/lib/registry/registry` RequireJS module.
```
vendor/magento/module-ui/view/base/requirejs-config.js
17:            uiRegistry:     'Magento_Ui/js/lib/registry/registry',
```

If you want to peek at the data available in a particular scope’s binding, the following debugging code should steer you straight.
```html
<li class="greet welcome" data-bind="scope: 'customer'">
    <pre data-bind="text: ko.toJSON($data, null, 2)"></pre>            
    <!-- ... -->
</li>
```

If you’re one of the folks interested in diving into the **real** code that creates the view models 
(and not our simplified pseudo-code above), you can start out in the `Magento_Ui/js/core/app` module.
```js
// #File: vendor/magento/module-ui/view/base/web/js/core/app.js
define([
    './renderer/types',
    './renderer/layout',
    'Magento_Ui/js/lib/ko/initialize'
], function (types, layout) {
    'use strict';

    return function (data) {
        types.set(data.types);
        layout(data.components);
    };
});
```

This module has a dependency named `Magento_Ui/js/core/renderer/layout`. 
It’s in this dependency module that Magento initializes the view models, and adds them to the view model registry.
```
#File: vendor/magento/module-ui/view/base/web/js/core/renderer/layout.js
```








