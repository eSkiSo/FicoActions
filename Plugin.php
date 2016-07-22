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
}
