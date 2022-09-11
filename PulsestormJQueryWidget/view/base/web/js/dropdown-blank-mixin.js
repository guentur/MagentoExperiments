define(['jquery'], function(jQuery){
    return function(originalWidget){
        alert("Our mixin is hooked up.");
        console.log("Our mixin is hooked up");

        return originalWidget;
    };
});
