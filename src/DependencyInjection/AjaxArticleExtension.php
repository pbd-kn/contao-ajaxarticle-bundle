<?php

declare(strict_types=1);



namespace PBDKN\AjaxArticleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


/* Da die Bundle Klasse "AjaxArticleBundle" lautet siehe ajaxAriticleBundle.php
 * muss die Dependency Injection Klasse "AjaxArticleExtension" lauten
 */
 
class AjaxArticleExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        //echo "PBD PBD dependencInjection contao-ajaxarticle-bundle file ContaoAjaxBundleExtension load service";
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');
    }
}

