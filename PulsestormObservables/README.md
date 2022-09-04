# Guentur_PulsestormObservables
My training practise by [this article](https://alanstorm.com/knockout-observables-for-javascript-programmers/) 
titled _Knockout Observables for Javascript Programmers_

## Some interesting parts from the article mentioned above
Observables are stand-alone setter/getter objects. 

---
```js
//subscribe to a change
objectToHoldMyValue.subscribe(function(newValue){
    console.log("The subscriber sees: " + newValue);
});     
```
The above code sets up a callback that is, in other terms, an event listener (i.e. you’re subscribing to an event)

**Important**: Subscribers are **only** called when a value changes. If you pass in the observable’s current value, 
Knockout will not call subscriber callbacks
---

### The Importance of Observables
If you consider a simple Knockout.js data binding (from [the official intro tutorial](http://learn.knockoutjs.com/#/?tutorial=intro))
```
<input data-bind="value: firstName" ... />
```
Behind the scenes, the `value` data-binding will check if `firstName` is an observable. 
If `firstName` is an observable, the `value` binding implementation will setup a subscriber that updates the `<input/>`.

Even if you create [a custom binding](http://knockoutjs.com/documentation/custom-bindings.html), 
Knockout handles setting up the subscriber, and your binding’s `update` method gets called

The subscribe method feels like something that should be a private API, 
but since this is javascript and everything’s public by default, developers can and will setup their own subscribers for observables.

#### Why observable is without parenthesis at the template level
Something else that may cause you, as a javascript or PHP programmer, a bit of cognitive dissonance is the lack of empty parameter `()` parenthesis when someone uses an observable in a data binding
```
<input data-bind="value: firstName" ... />
```
When I first started with Knockout.js, 
the lack of any clear _distinction_ between a regular object property and an observable — at the template level — 
was a little confusing. 
Once you understand that _observables are just callable javascript objects_, 
and understand that the _binding needs to receive this object and not its stored value_, 
things start to make a little more sense. 
Developers from a civilized language (like ruby), where you don’t need parenthesis to call a method, are now free to laugh.

### For Magento 2 Developers
As a Knockout.js developer, you can live a life that’s mostly ignorant of how observables are implemented. 
Magento 2 developers don’t have this luxury. 
The UI Component systems make heavy use of observable properties, and **also** setup their own subscribers.

The good news is: When you see something like
```js
//...
someProp: ko.observable('default value')
//...
```
you don’t need to panic. The program is just using `someProp` to store a value.

The bad news is — that observable may have a number of subscribers. 
These subscribers may come from a Knockout.js template’s `data-bind` attributes. 
They may come from Magento core code setting up their own subscribers. 
You can view the number of callbacks an observer has via the `_subscriptions` property
```js
console.log(objectToHoldMyValue._subscriptions);
Object
    change: Array[3]
        0: ko.subscription
        1: ko.subscription
        2: ko.subscription
```
Or peek at a particular callback like this
```js
console.log(
    objectToHoldMyValue._subscriptions.change[1].callback
);
```

However — you’re at the mercy of your debugger w/r/t to how this information is displayed, 
and there’s no easy way to tell **where** a particular subscriber comes from.
