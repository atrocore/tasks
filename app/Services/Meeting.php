<?php

namespace ActivitiesTasks\Services;

use \Espo\ORM\Entity;
use \ActivitiesTasks\Business\Event\Invitations;

use \Espo\Core\Exceptions\Error;
use \Espo\Core\Exceptions\Forbidden;

class Meeting extends \Espo\Services\Record
{
    protected function init()
    {
        $this->addDependencyList([
            'preferences',
            'language',
            'dateTime',
            'container',
            'fileManager',
            'number'
        ]);
    }

    protected $exportSkipFieldList = ['duration'];

    protected function getMailSender()
    {
        return $this->getInjection('container')->get('mailSender');
    }

    protected function getPreferences()
    {
        return $this->getInjection('preferences');
    }

    protected function getCrypt()
    {
        return $this->getInjection('container')->get('crypt');
    }

    protected function getLanguage()
    {
        return $this->getInjection('language');
    }

    protected function getDateTime()
    {
        return $this->getInjection('dateTime');
    }

    public function checkAssignment(Entity $entity)
    {
        $result = parent::checkAssignment($entity);
        if (!$result) {
            return false;
        }

        $userIdList = $entity->get('usersIds');
        if (!is_array($userIdList)) {
            $userIdList = [];
        }

        $newIdList = [];
        if (!$entity->isNew()) {
            $existingIdList = [];
            foreach ($entity->get('users') as $user) {
                $existingIdList[] = $user->id;
            }
            foreach ($userIdList as $id) {
                if (!in_array($id, $existingIdList)) {
                    $newIdList[] = $id;
                }
            }
        } else {
            $newIdList = $userIdList;
        }

        foreach ($newIdList as $userId) {
            if (!$this->getAcl()->checkAssignmentPermission($userId)) {
                return false;
            }
        }

        return true;
    }

    protected function getInvitationManager($useUserSmtp = true)
    {
        $smtpParams = null;
        if ($useUserSmtp) {
            $smtpParams = $this->getPreferences()->getSmtpParams();
            if ($smtpParams) {
                if (array_key_exists('password', $smtpParams)) {
                    $smtpParams['password'] = $this->getCrypt()->decrypt($smtpParams['password']);
                }
                $smtpParams['fromAddress'] = $this->getUser()->get('emailAddress');
                $smtpParams['fromName'] = $this->getUser()->get('name');
            }
        }
        return new Invitations(
            $this->getEntityManager(),
            $smtpParams,
            $this->getMailSender(),
            $this->getConfig(),
            $this->getInjection('fileManager'),
            $this->getDateTime(),
            $this->getInjection('number'),
            $this->getLanguage()
        );
    }

    public function sendInvitations(Entity $entity, $useUserSmtp = true)
    {
        $invitationManager = $this->getInvitationManager($useUserSmtp);

        $emailHash = array();

        $sentCount = 0;

        $users = $entity->get('users');
        foreach ($users as $user) {
            if ($user->id === $this->getUser()->id) {
                if ($entity->getLinkMultipleColumn('users', 'status', $user->id) === 'Accepted') {
                    continue;
                }
            }
            if ($user->get('emailAddress') && !array_key_exists($user->get('emailAddress'), $emailHash)) {
                $invitationManager->sendInvitation($entity, $user, 'users');
                $emailHash[$user->get('emailAddress')] = true;
                $sentCount++;
            }
        }

        $contacts = $entity->get('contactsStatus');
        foreach ($contacts as $contact) {
            if ($contact->get('emailAddress') && !array_key_exists($contact->get('emailAddress'), $emailHash)) {
                $invitationManager->sendInvitation($entity, $contact, 'contactsStatus');
                $emailHash[$user->get('emailAddress')] = true;
                $sentCount++;
            }
        }

        $leads = $entity->get('leadsStatus');
        foreach ($leads as $lead) {
            if ($lead->get('emailAddress') && !array_key_exists($lead->get('emailAddress'), $emailHash)) {
                $invitationManager->sendInvitation($entity, $lead, 'leadsStatus');
                $emailHash[$user->get('emailAddress')] = true;
                $sentCount++;
            }
        }

        if (!$sentCount) {
            return false;
        }

        return true;
    }

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

    public function massSetHeld(array $ids)
    {
        foreach ($ids as $id) {
            $entity = $this->getEntityManager()->getEntity($this->entityType, $id);
            if ($entity && $this->getAcl()->check($entity, 'edit')) {
                $entity->set('status', 'Held');
                $this->getEntityManager()->saveEntity($entity);
            }
        }
        return true;
    }

    public function massSetNotHeld(array $ids)
    {
        foreach ($ids as $id) {
            $entity = $this->getEntityManager()->getEntity($this->entityType, $id);
            if ($entity && $this->getAcl()->check($entity, 'edit')) {
                $entity->set('status', 'Not Held');
                $this->getEntityManager()->saveEntity($entity);
            }
        }
        return true;
    }

    public function getSelectAttributeList($params)
    {
        $attributeList = parent::getSelectAttributeList($params);
        if (is_array($attributeList)) {
            if (array_key_exists('select', $params)) {
                $passedAttributeList = $params['select'];
                if (in_array('duration', $passedAttributeList)) {
                    if (!in_array('dateStart', $attributeList)) {
                        $attributeList[] = 'dateStart';
                    }
                    if (!in_array('dateEnd', $attributeList)) {
                        $attributeList[] = 'dateEnd';
                    }
                }
            }
        }
        return $attributeList;
    }
}
