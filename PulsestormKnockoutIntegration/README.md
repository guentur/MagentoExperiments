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



















