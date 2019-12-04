<?php

namespace DelightBonusSystem\Subscribers;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use Symfony\Component\Finder\Finder;

class TemplateRegistration implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDirectory;

    /**
     * @var \Enlight_Template_Manager
     */
    private $templateManager;

    /**
     * @param $pluginDirectory
     * @param \Enlight_Template_Manager $templateManager
     */
    public function __construct($pluginDirectory, \Enlight_Template_Manager $templateManager)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->templateManager = $templateManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch',
            'Theme_Compiler_Collect_Plugin_Css' => 'onCollectCss',
        ];
    }

    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir($this->pluginDirectory . '/Resources/views');
    }


    /**
     * @return ArrayCollection|null
     */
    public function onCollectCss()
    {
        $files = $this->collectResourceFiles($this->pluginDirectory, 'css');
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