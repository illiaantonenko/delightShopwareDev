<?php


namespace RevanProductList\Subscribers;


use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

class ProductListSubscriber implements SubscriberInterface
{

    private $pluginDirectory;
    private $container;

    public function __construct($pluginDirectory, ContainerInterface $container)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch',
            'Theme_Compiler_Collect_Plugin_Javascript' => 'onCollectJavascript',
        ];
    }

    public function onPreDispatch(\Enlight_Event_EventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir($this->pluginDirectory.'/Resources/views');
    }



    /**
     * @return ArrayCollection|null
     */
    public function onCollectJavascript()
    {
        $files = $this->collectResourceFiles($this->pluginDirectory, 'js');
        error_log(print_r($files,true));
        if ($files) {
            return new ArrayCollection($files);
        }

        return null;
    }


    /**
     * @param string $baseDir resource base directory
     * @param string $type    `css` or `js`
     *
     * @return string[]
     */
    private function collectResourceFiles($baseDir, $type)
    {
        $directory = $baseDir . '/Resources/frontend/' . $type;
        if (!is_dir($directory)) {
            return [];
        }

        $files = [];
        $finder = new Finder();
        $finder->files()->name('*.' . $type)->in($directory);
        $finder->sortByName();

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $files[] = $file->getRealPath();
        }

        return $files;
    }
}