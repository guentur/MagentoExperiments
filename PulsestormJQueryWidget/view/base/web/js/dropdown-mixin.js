define(['jquery'], function(jQuery){
    return function(originalWidget){
        // if you want to get fancy and pull the widget namespace
        // and name from the returned widget definition
        // var widgetFullName = originalWidget.prototype.namespace +
        //     '.' +
        //     originalWidget.prototype.widgetName;


        jQuery.widget(
            'mage.dropdownDialog',              //named widget we're redefining

            //jQuery.mage.dropdownDialog
            jQuery['mage']['dropdownDialog'],   //widget definition to use as
            //a "parent" definition -- in
            //this case the original widget
            //definition, accessed using
            //bracket syntax instead of
            //dot syntax

            {                                   //the new methods
                open:function(){
                    //our new code here
                    console.log("I opened a dropdown!");

                    //call parent open for original functionality
                    return this._super();

                }
            });

        //return the redefined widget for `data-mage-init`
        //jQuery.mage.dropdownDialog
        return jQuery['mage']['dropdownDialog'];
    };
});
