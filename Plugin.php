<?php

namespace Kanboard\Plugin\Ficoactions;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\Ficoactions\Action\TaskNotifyCreator;
use Kanboard\Plugin\Ficoactions\Action\CommentDueDateChange;

class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new TaskNotifyCreator($this->container));
        $this->actionManager->register(new CommentDueDateChange($this->container));
    }

    public function getPluginName()
    {
        return 'Ficoactions';
    }

    public function getPluginAuthor()
    {
        return 'Manuel Raposo';
    }

    public function getPluginVersion()
    {
        return '0.1.0';
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
        return 'https://github.com/eSkiSo/Ficoactions';
    }
}
