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
            'subtask'
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
        if(isset($data['changes'])) $title_email = t('Sub-task updated');
        else $title_email = t('New sub-task');

        $creator = $this->userModel->getById($data['subtask']['user_id']);

        if (!empty($creator['email'])) {
            $this->emailClient->send(
                $creator['email'],
                $creator['name'] ?: $creator['username'],
                $title_email,
                $this->template->render('notification/subtask_create', array(
                    'task' => $data['task'],
                    'subtask' => $data['subtask'],
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
