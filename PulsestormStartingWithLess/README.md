# Guentur_PulsestormStartingWithLess
My training practise by [this article](https://alanstorm.com/magento_2_less_css/) 
titled Magento 2 and the Less CSS Preprocessor

## Some interesting parts from the article mentioned above

### Understanding Magento’s Less Implementation
How has Magento applied my less file if the config of entry point for styles is pointing to a plain .css file?
```
<css src="Pulsestorm\_StartingWithLess::red-alert.css"/>
```
Magento will search for this file on disk. 
If Magento doesn’t find it, Magento will look again, 
but instead of looking for a .css file, 
Magento will look for a .less file with the same name (red-alert.less). 

If Magento finds the Less source file, Magento will transform the file into CSS, and generate a CSS file. 

If you load the above CSS in a browser, you’ll see Magento has transformed the Less file into a CSS file.

### Understanding Less.js
```
System -> Stores -> Configuration -> Advanced -> Developer -> Front-end Developer Workflow 
```
and setting the _Front-end Developer Workflow_ option to `Client side less compilation`.

With this setting enabled (and if you remove any generated files/links from pub/static), 
changes to Less source files will be immediately reflected on the next page load.

Behind the scenes, when you have `less.js` enabled, Magento does three things. First, 
Magento adds the following two javascript files to your page’s source.
```
<script src="http://magento.example.com/static/adminhtml/Magento/backend/en_US/less/config.less.js"></script>
<script src="http://magento.example.com/static/adminhtml/Magento/backend/en_US/less/less.min.js"></script>
```

The `less.min.js` file is the minified `less.js` source. 
The `config.less.js` file is a javascript configuration file used to [configure `less.js`](http://lesscss.org/#using-less-configuration). 
Having these here enables client side Less compilation.

The second change is each CSS link will have its `rel` attribute’s value changed from `stylesheet`

```
<link  rel="stylesheet" type="text/css" ... />
```

to `stylesheet/less`.

```
<link  rel="stylesheet/less" type="text/css" ... />
```

This identifies the source of a `<link/>` as needing the Less transformations provided by `less.js`.

Finally, and most importantly, when you’re running Magento in developer mode with client side Less compilation on, 
Magento **will serve Less source files instead of CSS files**. 
i.e., if you try to download the `red-alert.css` file in your system

```
http://magento.example.com/static/frontend/Magento/luma/en_US/Pulsestorm_StartingWithLess/red-alert.css
```

instead of getting the CSS file, you’ll get the Less source file

```
@oh-my-blue: #ff0000;
body
{
    background-color:@oh-my-blue;
} 
```
While this client-side processing is useful, I’d be wary of relying too heavily on it. 
The GA release contains [what seems to be a bug](https://github.com/magento/magento2/issues/3293) 
where Magento can serve CSS source files with a `rel="stylesheet/less"`. 
Processing CSS as Less can create subtle bugs. 

Also, as we [learned last time](http://alanstorm.com/magento_2_and_requirejs), 
Magento uses RequireJS as its foundational javascript framework. 
RequireJS means there’s DOM altering javascript loading asynchronously in the background, 
which may interfere with the DOM altering `less.js`.
