<?php

namespace Wifinder\FrontendContentBundle\Twig;

use Symfony\Component\HttpFoundation\Request;

class LocaleExtension extends \Twig_Extension {

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Constructor
     * 
     * @param ContainerInterface $container
     */
    public function __construct($container) {
        $this->container = $container;

        if ($this->container->isScopeActive('request')) {
            $this->request = $this->container->get('request');
        }
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('localeLink', array($this, 'localeFilter')),
        );
    }

    public function localeFilter($link, $prefix = "", $locale = null) {
        $scriptName = $this->request->getScriptName();
        $kernel = $this->container->get('kernel');
        if(!$kernel->isDebug())
            $scriptName = '';
        if($locale)
            return $scriptName.'/'.$locale.$prefix.$link;
        if($this->request->getLocale() != $this->container->getParameter('locale'))
            return $scriptName.'/'.$this->request->getLocale().$prefix.$link;

        return $scriptName.$prefix.$link;
    }

    public function getName() {
        return 'locale_extension';
    }

}
