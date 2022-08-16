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
