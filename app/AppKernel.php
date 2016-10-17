<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Wifinder\PageBundle\PageBundle(),
            new Wifinder\WebItemBundle\WebItemBundle(),
            new Wifinder\MenuBundle\MenuBundle(),
            new Wifinder\CatalogBundle\CatalogBundle(),
            new Wifinder\LoginBundle\LoginBundle(),
            new Wifinder\FrontendContentBundle\FrontendContentBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Wifinder\FileCategoryBundle\FileCategoryBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Wifinder\ImageGalleryBundle\ImageGalleryBundle(),
            new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new Wifinder\NewsBundle\NewsBundle(),
            new Wifinder\ProjectBundle\ProjectBundle(),
            new Wifinder\CallbackBundle\CallbackBundle(),
            new Wifinder\LayoutBundle\LayoutBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
