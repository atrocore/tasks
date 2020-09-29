<?php

namespace ActivitiesTasks\Services;

use \Espo\Core\Exceptions\Error;
use \Espo\Core\Exceptions\Forbidden;

use \Espo\ORM\Entity;

class Task extends \Espo\Services\Record
{
    public function loadAdditionalFields(Entity $entity)
    {
        parent::loadAdditionalFields($entity);
        $this->loadRemindersField($entity);
    }

    protected function loadRemindersField(Entity $entity)
    {
        $reminders = $this->getRepository()->getEntityReminderList($entity);
        $entity->set('reminders', $reminders);
    }
}
