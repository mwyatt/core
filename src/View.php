<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class View extends \Mwyatt\Core\Data /*implements ViewInterface*/
{


    public $url;


    protected $pathBase;


    /**
     * must store the routes found in the registry for building urls
     */
    public function __construct()
    {
        $registry = Registry::getInstance();
        $this->url = $registry->get('url');
        $this->pathBase = (string) (__DIR__ . '/');
    }

    
    /**
     * load template file and prepare all objects for output
     * @param  string $templatePath
     */
    public function getTemplate($templatePath)
    {

        // common meta vars
        $this->setMeta();

        // obtain path
        $path = $this->getTemplatePath($templatePath);
        if (! $path) {
            return;
        }

        // push stored into method scope
        extract($this->getData());

        // start output buffer
        // @todo start this at the start of the app?
        ob_start();

        // render template using extracted variables
        include($path);
        $content = ob_get_contents();

        // destroy output buffer
        // @todo convert to ob_clean
        ob_end_clean();

        // add this data to existing
        $this->setData($content);

        // return just loaded template result
        return $content;
    }


    /**
     * finds a template, either using a prioroty or
     * gracefully searching
     * @param  string $append    foo/bar
     * @return string            the path
     */
    public function getTemplatePath($append)
    {

        // appending
        $end = 'template' . DS . strtolower($append) . EXT;

        // site-specific
        $path = SITE_PATH . $end;
        if (file_exists($path)) {
            return $path;
        }
        
        // common
        $path = APP_PATH . $end;
        if (file_exists($path)) {
            return $path;
        }

        // 500
        return $path;
    }


    /**
     * find the asset path for a particular path
     * used with includes
     * all assets come from only asset/
     * @param  string $append foo/bar.svg
     * @return string
     */
    public function getAssetPath($append)
    {
        $end = 'asset' . DS . $append;
        $path = $this->pathBase . $end;
        return $path;
    }


    /**
     * grabs base path for the view folder, used for headers, footers
     * and all includes within the view templates
     * @return string
     */
    public function getPath($append)
    {
        $path = $this->pathBase;
        return $path . $append;
    }


    /**
     * returns an absolute url of the asset complete with asset version
     * @param  string $append path to asset
     * @return string         url of asset with modified time
     */
    public function getUrlAsset($append)
    {
        $path = $this->getAssetPath($append);

        // check actually there
        if (! file_exists($path)) {
            return;
        }

        // get mod time
        $modifiedTime = filemtime($path);

        // return url to asset with modified time as query var
        return $this->url->generate() . 'asset' . US . $append . '?' . $modifiedTime;
    }


    /**
     * grabs base path for the view folder, used for headers, footers
     * and all includes within the view templates
     * @return string
     */
    public function getPathMedia($append)
    {
        return $this->pathBase . 'media' . US . SITE . US . $append;
    }


    /**
     * appends admin to the path
     * @param  string $template
     * @return string
     */
    public function pathAdminView($template = '')
    {
        return $this->getTemplatePath('admin/' . $template);
    }


    /**
     * returns a body class using the parts of the url after the domain
     * @return string
     */
    public function getBodyClass()
    {
        $bodyClass = '';
        foreach ($this->url->getPath() as $path) {
            $bodyClass .= $path . '-';
        }
        return $bodyClass = rtrim($bodyClass, -1);
    }


    /**
     * always run before a template is output
     * fills data with the db defaults
     * then overrides with controller custom sets
     * then injects the site title in front of title
     */
    public function setMeta()
    {
        $data = $this->getData();
        $metas = [
            'meta_title' => 'metaTitle',
            'meta_description' => 'metaDescription',
            'meta_keywords' => 'metaKeywords'
        ];
        foreach ($metas as $key) {
            $$key = '';
        }

        // set database defaults
        if (!empty($data['option'])) {
            foreach ($metas as $keyLegacy => $key) {
                if (!empty($data['option'][$keyLegacy])) {
                    $$key = $data['option'][$keyLegacy]->getValue();
                }
            }
        }

        // ovveride with controller sets
        foreach ($metas as $keyLegacy => $key) {
            if (!empty($data[$key])) {
                $$key = $data[$key];
            }
        }

        // set to template
        foreach ($metas as $key) {
            $this->setDataKey($key, $$key);
        }

        // inject site title to the meta title
        // added bonus!
        if (!empty($metaTitle) && !empty($data['option']['site_title'])) {
            $this->setDataKey('metaTitle', implode(' -- ', [$data['option']['site_title']->getValue(), $metaTitle]));
        }

        // chain
        return $this;
    }


    /**
     * needed for mustache, js and css asset register
     * @param  string $key   mustache, css, js
     * @param  string $value path
     * @return object
     */
    public function appendAsset($key, $value)
    {

        // validate
        if (! in_array($key, ['mustache', 'css', 'js'])) {
            return $this;
        }

        // set
        $rootKey = 'asset';
        if (! isset($this->data[$rootKey])) {
            $this->data[$rootKey] = [];
        }
        if (! isset($this->data[$rootKey][$key])) {
            $this->data[$rootKey][$key] = [];
        }
        $this->data[$rootKey][$key][] = $value;
        return $this;
    }
}
