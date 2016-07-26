<?php

namespace Kanboard\Plugin\FicoActions;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\FicoActions\Action\TaskNotifyCreator;

class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new TaskNotifyCreator($this->container));
    }

    public function getPluginName()
    {
        return 'FicoActions';
    }

    public function getPluginAuthor()
    {
        return 'Manuel Raposo';
    }

    public function getPluginVersion()
    {
        return '0.0.2';
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginDescription()
    {
        return 'Re-assign task back to creator and notify by email when task is moved to defined column';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/eSkiSo/FicoActions';
    }
}
