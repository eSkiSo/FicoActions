<h2><?= $this->text->e($task['title']) ?> (#<?= $task['id'] ?>)</h2>
	<ul>
<?php 
	if (empty($task['date_due'])) {
        echo '<li>'.t('The due date have been removed').'</li>';
    } else {
        echo '<li>'.t('New due date: ').$this->dt->date($task['date_due']).' '.t('by').' <b>'.$by.'</b></li>';
    }
?>
	</ul>
<?= $this->render('notification/footer', array('task' => $task, 'application_url' => $application_url)) ?>