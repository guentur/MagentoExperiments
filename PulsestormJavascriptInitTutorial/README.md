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

