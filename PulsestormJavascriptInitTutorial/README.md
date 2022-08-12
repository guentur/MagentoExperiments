# Guentur_PulsestormJavascriptInitTutorial
My training practise by [this article](https://alanstorm.com/magento_2_javascript_init_scripts/)
titled Magento 2: Javascript Init Scripts

## Some interesting parts from the article mentioned above:
The Magento javascript init methods we’re going to discuss solve a few different problems.
- First, they provide a standard mechanism to discourage directly embedding javascript into a page. 
- Second, they provide a way to invoke a stand alone RequireJS module (defined with define) as a program. 
- Third, they provide a way to pass that program a server side generated JSON object. 
- Fourth, they provide a way to tell that program which (if any) DOM nodes it should operate on.

Keep these four goals in mind.

---
### X-Magento-Init
```html
#File: app/code/Guentur/PulsestormJavascriptInitTutorial/view/frontend/templates/content.phtml
<script type="text/x-magento-init">
    //...
</script>
```
This tag is **not** a javascript tag. 
Notice the `type="text/x-magento-init"` attribute. 
When a browser doesn’t recognize the value in a script’s type tag, 
it will ignore the contents of that tag. 
Magento (similar to other modern javascript frameworks) uses this behavior to its advantage. 
While it’s beyond the scope of this tutorial, 
there’s Magento javascript code running that will scan for `text/x-magento-init` script tags. 
If you want to explore this yourself, [this Stack Exchange question and answer](http://magento.stackexchange.com/questions/89187/in-magento2-what-is-script-type-text-x-magento-init) 
is a good place to start.

---
The other part of the `x-magento-init` code chunk is the following object
```json
#File: app/code/Guentur/PulsestormJavascriptInitTutorial/view/frontend/templates/content.phtml
{
    "Guentur_PulsestormJavascriptInitTutorial/example": {}         
}
```
Magento will look at the _key_ of this object, and include it (the key) as a RequireJS module. 
That’s what loading our `example.js` script.

This works, and Magento itself often uses the x-magento-init method to invoke a RequireJS module as a **program**.

---
```
<div id="one" class="foo">Hello World</div>
<div id="two" class="foo">
    Goodbye World
</div>    

<script type="text/x-magento-init">
    {
        "* ---> #one": {
            "Guentur_PulsestormJavascriptInitTutorial/example":{"config":"value"}          
        }
    }        
</script>
```

Here, we’ve changed the `*` to a `#one`. The `*` we used previously is actually a special case, 
for programs that don’t need to operate on DOM nodes.
