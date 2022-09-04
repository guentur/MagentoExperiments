# Guentur_PulsestormJQueryWidget
My training practise by [this article](https://alanstorm.com/modifying-a-jquery-widget-in-magento-2/) 
titled _Modifying a jQuery Widget in Magento 2_

## Some interesting parts from the article mentioned above

Like its brethren _plugin_ and _module_, the word _widget_ has the unfortunate distinction of being a popular way to describe a bunch of computer code _without_ a corresponding strict definition of what a widget is.

Like Magento 1, Magento 2 has a “CMS Widget” system that allows developers to create user interfaces for data entry of structured content blocks. While an interesting system, that’s not what we’re here to talk about today.

The [jQuery widget system](https://learn.jquery.com/jquery-ui/widget-factory/how-to-use-the-widget-factory/) 
is a part of [jQuery UI](https://learn.jquery.com/jquery-ui/). 
It’s marketed as a way to develop your own user interface elements for jQuery UI, 
but long time readers of this website will recognize it for what it is: 
An object system built on top of javascript.

A good chunk of Magento’s default user interface is built-out [using custom jQuery widgets](http://devdocs.magento.com/guides/v2.1/javascript-dev-guide/widgets/jquery-widgets-about.html)

While this article is intended for anyone working with the Magento 2 system, 
it will help if you’ve already worked your way through our 
[Magento 2 Advanced Javascript](http://alanstorm.com/category/magento-2/#magento2-advanced-javascript) series 
(especially [the javascript init](http://alanstorm.com/magento_2_javascript_init_scripts/) 
and [javascript mixins](http://alanstorm.com/the-curious-case-of-magento-2-mixins/) articles), 
the [Serving Frontend Files](http://alanstorm.com/magento-2-frontend-files-serving/), 
[Adding Frontend Files to your Module](http://alanstorm.com/magento_2_adding_frontend_files_to_your_module/), 
and [RequireJS](http://alanstorm.com/magento_2_and_requirejs/) articles 
in our [Magento 2 for PHP MVC developers series](http://alanstorm.com/category/magento-2/#magento-2-mvc), 
and jQuery’s [five part Widget Factory series](https://learn.jquery.com/jquery-ui/widget-factory/).


