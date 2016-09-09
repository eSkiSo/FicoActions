<?php

namespace Kanboard\Plugin\Ficoactions\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\UserModel;
use Kanboard\Model\TaskFinderModel;
use Kanboard\Model\ColumnModel;
use Kanboard\Action\Base;

/**
 * Notify assignee on SubTask assignment
 *
 * @package action
 * @author  Manuel Raposo
 */
class SubTaskNotify extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Subtask Notify');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            SubtaskModel::EVENT_CREATE,
            SubtaskModel::EVENT_UPDATE,
        );
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array();
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array(
            'id',
            'task_id',
            'user_id'
        );
    }

    /**
     * Execute the action
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $taskInfo = $this->taskFinderModel->getById($data['task_id']);
        $subTaskInfo = $this->SubtaskModel->getById($data['id']);
        $creator = $this->userModel->getById($subTaskInfo['user_id']);
        $columnInfo = $this->columnModel->getById($taskInfo['creator_id']);
        $taskInfo['column_title'] = $columnInfo['title'];

        if (!empty($creator['email'])) {
            $this->emailClient->send(
                $creator['email'],
                $creator['name'] ?: $creator['username'],
                'Nova Subtarefa Assignada',
                $this->template->render('notification/subtask_create', array(
                    'task' => $taskInfo,
                    'subtask' => $subTaskInfo,
                    'application_url' => $this->configModel->get('application_url'),
                ))
            );
            return true;
        }
        return false;
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return true;
    }
}
