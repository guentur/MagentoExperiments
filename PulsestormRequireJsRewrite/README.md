# Guentur_PulsestormRequireJsRewrite
My training practise by [this article](https://alanstorm.com/the-curious-case-of-magento-2-mixins/) 
titled _The Curious Case of Magento 2 Mixins_

## Some interesting parts from the article mentioned above

### What is a Mixin? Underscore library with Mixins functionality
A “mixin” is, from one point of view, an alternative to traditional class inheritance. 
Mixins date back [to lisp programming of the early/mid 1980s](https://en.wikipedia.org/wiki/Mixin#History).

If you’ve ever used [PHP Traits](http://php.net/manual/en/language.oop5.traits.php), you’ve used a simplified mixin system.

Contrast this [with ruby](http://ruby-doc.com/docs/ProgrammingRuby/html/tut_modules.html), 
which allows one module to completely “include” (or “mix in”) another module’s methods.

You’ll also see the idea of [multiple inheritance](https://en.wikipedia.org/wiki/Multiple_inheritance) 
thrown about in mixin discussions.

### Javascript and Mixins, Sitting in a Tree
Javascript doesn’t have any native classes. In javascript, you define methods by attaching functions to objects
```js
var foo = {};

foo.someMethod = function(){
    //...
};
```

Since objects can be easily reflected into, Javascript is a fertile enviornment for developers who want to build systems for creating mixin like objects. One library that offers this sort of functionality (although the word mixin isn’t used) [is underscore.js](http://underscorejs.org/#extend).

Using the `extend` method in underscore.js, you can have a de-facto mixin-like behavior. Consider the following
```js
var a = {
    foo:function(){
        //...
    }
};

var b = {
    bar:function(){
        //...
    }

}

c = _.extend(a, b);    
```
Confusingly, underscore.js has an actual method [named mixin](http://underscorejs.org/#mixin), 
but this method is for adding methods **to the underscore JS object itself**.

### Magento uiClass Objects. What is the difference with Underscore library Mixin functionality
If you’ve worked your way through [the UI Component series](http://alanstorm.com/category/magento-2/#magento-2-ui), 
you’re already familiar with [Magento’s `uiClass` objects](http://alanstorm.com/magento_2_uiclass_data_features/). 
These objects also have an `extend` method. This method looks similar to the underscore.js method

```js
var b = {
    bar:function(){
        //...
    }

}
UiClass = requirejs('uiClass');

// class NewClass extends uiClass
var NewClass = UiClass.extend(b);

// class AnotherNewClass extends NewClass
var AnotherNewClass = NewClass.extend({});

var object = new NewClass;
object.bar();
```

However, the `uiClass` `extend` method is used for something _slightly_ different. 
The purpose of the `uiClass.extend` is to create a **new** javascript constructor function that’s based on 
an existing javascript constructor function. Above, `NewClass` won’t get a `bar` method, but objects instantiated from it will.

While this feels more like straight inheritance, 
there might be some folks who would call this a mixin due to the `uiClass`‘s implementation details.

We’re now going to jump to a **completely** different topic, but keep all of the above in mind.

### Mixins in Magento
```
//File: app/code/Pulsestorm/RequireJsRewrite/view/base/requirejs-config.js
var config = {
    'config':{
        'mixins': {
            'Magento_Customer/js/view/customer': {
                'Pulsestorm_RequireJsRewrite/hook.js':true
            }
        }
    }
};    
```

You may be confused by the `mixins` configuration key.
This is **not** a part of [standard RequireJS](http://requirejs.org/).
This is a [special configuration flag](http://magento.stackexchange.com/questions/142826/how-are-the-things-magento-2-calls-mixins-implemented) 
Magento introduced to their RequireJS system.
Don’t let the word `mixins` confused you. This has (almost) **nothing** to do with the programming concept we [discussed earlier](#What_is_a_Mixin?_Underscore_library_with_Mixins_functionality). 
It’s just a poorly chosen name.

### Class Rewrites for Javascript
One thing you could do with it is **replace** method implementations on RequireJS modules that return objects
```js
define([], function(){
    'use strict';    
    
    console.log("Called this Hook.");
    
    return function(targetModule) {
        targetModule.someMethod = function(){
            //replacement for `someMethod
        }
        return targetModule;
    };
});
```

Also, if the module in question returns a `uiClass` based object? 
You could use `uiClass`‘s `extend` method to return a different class that extended the method, 
but used `uiClass`‘s `_super()` feature to call the parent method.
```js
define([], function(){
    'use strict';    
    console.log("Called this Hook.");
    return function(targetModule){
        //if targetModule is a uiClass based object
        return targetModule.extend({
            someMethod:function()
            {
                var result = this._super(); //call parent method

                //do your new stuff

                return result;
            }
        });
    };
});
```

While multiple developers can all safely setup their own hooks, 
they **can’t** all redefine the same method or function. 
One person’s modifications will win out over the other person’s.

Fortunately, Magento 2 has a solution for that in the `mage/utils/wrapper` module.

### Wrapping Function Calls
The `mage/utils/wrapper` module allows for functionality similar to a 
[Magento 2 backend around plugin](http://alanstorm.com/magento_2_object_manager_plugin_system/).

Great thing about wrapping is, multiple people can do it.
```js
var example = {};
example.foo = function (){
    console.log("Called foo");
}

var wrapper = requirejs('mage/utils/wrapper');

var wrappedFunction = wrapper.wrap(example.foo, function(originalFunction){        
    console.log("Before");
    originalFunction();
    console.log("After");
});

//call wrapped function
wrappedFunction();

//change method definition to use wrapped function
example.foo = wrappedFunction;

var wrappedFunction2 = wrapper.wrap(wrappedFunction, function(originalFunction){
    console.log("Before 2");
    originalFunction();
    console.log("After 2");
});

wrappedFunction2();
```
If you run the above code, you’ll see the following output
```
Before
Called foo
After

Before 2
Before
Called foo
After
After 2
```
This means if you’re using it to replace method definitions, you can avoid the winner take all situation we described earlier.

The wrap method accepts two arguments. The first is the original function you want to wrap. 
The second is the function you want to wrap it with.
The originalFunction parameter will be a reference to the function you’re trying to wrap (example.foo above). 
The wrap method returns a function that, when called, will call your function.


Consider the following “mixin” hook.

```js
define(['mage/utils/wrapper'], function(wrapper){
    'use strict';    
    console.log("Called this Hook.");
    return function(targetModule){

        var newFunction = targetModule.someFunction;
        var newFunction = wrapper.wrap(newFunction, function(original){
            //do extra stuff

            //call original method
            var result = original();    

            //do extra stuff                

            //return original value
            return result;
        });

        targetModule.someFunction = newFunction;
        return targetModule;
    };
});    
```
Here we’ve replaced the definition of `someFunction` with our wrapped function. 
This technique also has the advantage of working with RequireJS modules that _return functions instead of objects_.
