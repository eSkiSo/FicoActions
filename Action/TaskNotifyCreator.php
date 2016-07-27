<?php

namespace Kanboard\Plugin\Ficoactions\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Model\UserModel;
use Kanboard\Model\TaskFinderModel;
use Kanboard\Model\ColumnModel;
use Kanboard\Action\Base;

/**
 * Notify Task Creator on Action and assign it back to him
 *
 * @package action
 * @author  Manuel Raposo
 */
class TaskNotifyCreator extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Re-assign back to creator and notify by email');
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
            TaskModel::EVENT_MOVE_COLUMN,
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
        return array(
            'column_id' => t('Column'),
            'subject' => t('Email subject'),
        );
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
            'task_id',
            'column_id',
            'project_id'
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
        $creator = $this->creatorModel->getById($taskInfo['creator_id']);
        $columnInfo = $this->columnModel->getById($taskInfo['creator_id']);
        $taskInfo['column_title'] = $columnInfo['title'];
        if($taskInfo['creator_id'] != $taskInfo['owner_id']) $this->taskModificationModel->update(array('id' => $data['task_id'], 'owner_id' => $taskInfo['creator_id']));
        if (! empty($creator['email'])) {
            $this->emailClient->send(
                $creator['email'],
                $creator['name'] ?: $creator['username'],
                $this->getParam('subject'),
                $this->template->render('notification/task_move_column', array(
                    'task' => $taskInfo,
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
        return $data['column_id'] == $this->getParam('column_id');
    }
}
