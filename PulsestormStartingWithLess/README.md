# Guentur_PulsestormStartingWithLess
My training practise by [this article](https://alanstorm.com/magento_2_less_css/) titled Magento 2 and the Less CSS Preprocessor

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
