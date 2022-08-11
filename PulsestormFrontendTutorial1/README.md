# Guentur_PulsestormFrontendTutorial1

My training practise by [this article](https://alanstorm.com/magento_2_adding_frontend_files_to_your_module/)

## Some interesting parts from the article mentioned above:

Next up is the `web` folder.
**Files in web are ones that will be served via http or https**. 
Other folders at this level are:
- email, 
- layout, 
- page_layout, 
- templates,
- ui_component

---
The first part of a Magento 2 front end asset URL is `static`. This points to the actual

```
pub/static
```

folder in your Magento install. If you’re using the root level `index.php` file instead of `pub/index.php`, this should be

```
http://magento.example.com/pub/static/...
```

---
If the symlinks from development mode are still present in the pub/static sub-folders when you run setup:static-content:deploy, **Magento will not remove them**. 

On one hand — this shows whomever implemented setup:static-content:deploy cared enough to make sure their command wasn’t destructive. 

On the other hand — if your deployment procedure isn’t super tight, this means you may end up with symlinks on your production website.

---
### My notices

This URL is not working 
```
http://magento.example.com/static/adminhtml/Magento/blank/en\_US/Pulsestorm\_FrontendTutorial1/hello.js
```
because `Magento/blank` is not determined for adminhtml area. Magento uses `Magento/backend` theme for adminhtml, so we must use this URL:
```
http://mage24.local/static/adminhtml/Magento/backend/en_US/Guentur_PulsestormFrontendTutorial1/hello.js
```
But would magento loaded this static resource by first link if we defined `Magento/blank` as parent theme for `Magento/backend`?
I know about Magento's `theme` table, but I am curious where we define area for theme.
I cannot check it right now because I have some problems with my elasticsearch

