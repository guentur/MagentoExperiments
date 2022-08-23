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

