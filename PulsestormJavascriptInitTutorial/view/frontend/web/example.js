define([], function () {
    var mageJsComponent = function(config, node)
    {
        alert("From \"example\" JsComponent: Look in your browser's console");
        console.log(config);
        console.log(node);
        //alert(config);
    };

    return mageJsComponent;
});
