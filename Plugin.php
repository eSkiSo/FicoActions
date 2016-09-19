<?php

namespace Kanboard\Plugin\Ficoactions;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Event\EventManager;
use Kanboard\Plugin\Ficoactions\Action\TaskNotifyCreator;
use Kanboard\Plugin\Ficoactions\Action\SubTaskNotify;
use Kanboard\Plugin\Ficoactions\Action\CommentDueDateChange;

class Plugin extends Base
{
    public function initialize()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
        $this->eventManager->register('subtask.create', t('New sub-task'));
        $this->eventManager->register('subtask.update', t('Sub-task updated'));
        $this->actionManager->register(new TaskNotifyCreator($this->container));
        $this->actionManager->register(new SubTaskNotify($this->container));
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
        return '0.1.1';
    }

    public function onStartup()
    {
        //Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginDescription()
    {
        return 'Re-assign task back to creator,notify by email when task is moved to defined column and notify on subtask';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/eSkiSo/Ficoactions';
    }
}
