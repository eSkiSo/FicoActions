<?php

namespace Kanboard\Plugin\FicoActions;

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
        return '0.0.1';
    }

    public function getPluginDescription()
    {
        return 'Notify Task creator when the task is moved to another column or closed';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/eSkiSo/FicoActions';
    }
}
