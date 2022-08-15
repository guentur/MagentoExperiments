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






