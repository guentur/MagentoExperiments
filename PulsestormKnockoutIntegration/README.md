# Guentur_PulsestormKnockoutIntegration
My training practise by [this article](https://alanstorm.com/magento_2_knockoutjs_integration/)
titled Magento 2: KnockoutJS Integration

## Some interesting parts from the article mentioned above:
KnockoutJS itself has no native concept of data storage, 
and like many modern javascript frameworks it was designed to work best with a service only backend. 
i.e. [KnockoutJS’s “Model” is some other framework making AJAX requests](http://knockoutjs.com/documentation/json-data.html) 
to populate view model values.

KnockoutJS has no opinion on how you include it in your projects, 
or how you organize your code (although the documentation makes it clear the KnockoutJS team members are fans of [RequireJS](http://requirejs.org/)).
