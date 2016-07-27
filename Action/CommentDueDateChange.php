<?php

namespace Kanboard\Plugin\Ficoactions\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Model\UserModel;
use Kanboard\Model\TaskFinderModel;
use Kanboard\Model\ColumnModel;
use Kanboard\Action\Base;

/**
 * Add comment on due date change and notify creator by email
 *
 * @package action
 * @author  Manuel Raposo
 */
class CommentDueDateChange extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Add comment on due date change and notify creator by email');
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
            TaskModel::EVENT_UPDATE,
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
            'project_id',
            'changes',
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
        
        if(isset($data['changes']) && isset($data['changes']['date_due'])) {
            $taskInfo = $this->taskFinderModel->getById($data['task_id']);
            $creator = $this->userModel->getById($taskInfo['creator_id']);
            $changedBy = $this->userModel->getById($this->userSession->getId());
            $columnInfo = $this->columnModel->getById($taskInfo['creator_id']);
            $taskInfo['column_title'] = $columnInfo['title'];

            if (! $this->userSession->isLogged()) {
                return false;
            }
            $dataFormat = $this->configModel->get('application_date_format', 'Y-m-d');
            $addComment = $this->commentModel->create(array(
                'comment' => t('Due Date updated to %s', date($dataFormat,$data['changes']['date_due'])),
                'task_id' => $data['task_id'],
                'user_id' => $this->userSession->getId(),
            ));
            if($addComment) {
                if (! empty($creator['email'])) {
                    $this->emailClient->send(
                        $creator['email'],
                        $creator['name'] ?: $creator['username'],
                        $this->getParam('subject'),
                        $this->template->render('ficoactions:notification/due_date_updated', array(
                            'task' => $taskInfo,
                            'by' => $changedBy['name'],
                            'changes' => $data['changes'],
                            'application_url' => $this->configModel->get('application_url'),
                        ))
                    );
                    return true;
                }
            }
            else return false;

        }
        return true;
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
        return true; //$data['column_id'] == $this->getParam('column_id');
    }
}
