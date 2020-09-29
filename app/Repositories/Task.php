<?php

namespace ActivitiesTasks\Repositories;

use Espo\ORM\Entity;

class Task extends \Espo\Core\Repositories\Event
{
    protected $reminderDateAttribute = 'dateEnd';

    protected $reminderSkippingStatusList = ['Completed', 'Canceled'];

    protected function init()
    {
        parent::init();
        $this->addDependency('dateTime');
        $this->addDependency('config');
    }

    protected function getConfig()
    {
        return $this->getInjection('config');
    }

    protected function getDateTime()
    {
        return $this->getInjection('dateTime');
    }

    protected function convertDateTimeToDefaultTimezone($string)
    {
        $dateTime = \DateTime::createFromFormat($this->getDateTime()->getInternalDateTimeFormat(), $string);
        $timeZone = $this->getConfig()->get('timeZone');
        if (empty($timeZone)) {
            $timeZone = 'UTC';
        }
        $tz = $timezone = new \DateTimeZone($timeZone);

        if ($dateTime) {
            return $dateTime->setTimezone($tz)->format($this->getDateTime()->getInternalDateTimeFormat());
        }
        return null;
    }

    protected function beforeSave(Entity $entity, array $options = array())
    {
        if ($entity->isAttributeChanged('status')) {
            if ($entity->get('status') == 'Completed') {
                $entity->set('dateCompleted', date('Y-m-d H:i:s'));
            } else {
                $entity->set('dateCompleted', null);
            }
        }

        if ($entity->has('dateStartDate')) {
            $dateStartDate = $entity->get('dateStartDate');
            if (!empty($dateStartDate)) {
                $dateStart = $dateStartDate . ' 00:00:00';
                $dateStart = $this->convertDateTimeToDefaultTimezone($dateStart);

                $entity->set('dateStart', $dateStart);
            } else {
                $entity->set('dateStartDate', null);
            }
        }

        if ($entity->has('dateEndDate')) {
            $dateEndDate = $entity->get('dateEndDate');
            if (!empty($dateEndDate)) {
                $dateEnd = $dateEndDate . ' 00:00:00';
                $dateEnd = $this->convertDateTimeToDefaultTimezone($dateEnd);

                $entity->set('dateEnd', $dateEnd);
            } else {
                $entity->set('dateEndDate', null);
            }
        }

        if (!$entity->isNew() && $entity->isAttributeChanged('parentId')) {
            $entity->set('accountId', null);
            $entity->set('contactId', null);
            $entity->set('accountName', null);
            $entity->set('contactName', null);
        }

        $parentId = $entity->get('parentId');
        $parentType = $entity->get('parentType');

        if ($entity->isAttributeChanged('parentId') || $entity->isAttributeChanged('parentType')) {
            $parent = null;
            if ($parentId && $parentType) {
                if ($this->getEntityManager()->hasRepository($parentType)) {
                    $columnList = ['id', 'name'];
                    if ($this->getEntityManager()->getMetadata()->get($parentType, ['fields', 'accountId'])) {
                        $columnList[] = 'accountId';
                    }
                    if ($this->getEntityManager()->getMetadata()->get($parentType, ['fields', 'contactId'])) {
                        $columnList[] = 'contactId';
                    }
                    if ($parentType === 'Lead') {
                        $columnList[] = 'status';
                        $columnList[] = 'createdAccountId';
                        $columnList[] = 'createdAccountName';
                        $columnList[] = 'createdContactId';
                        $columnList[] = 'createdContactName';
                    }
                    $parent = $this->getEntityManager()->getRepository($parentType)->select($columnList)->get($parentId);
                }
            }

            $accountId = null;
            $contactId = null;
            $accountName = null;
            $contactName = null;

            if ($parent) {
                if ($parent->getEntityType() == 'Account') {
                    $accountId = $parent->id;
                    $accountName = $parent->get('name');
                } else {
                    if ($parent->getEntityType() == 'Lead') {
                        if ($parent->get('status') == 'Converted') {
                            if ($parent->get('createdAccountId')) {
                                $accountId = $parent->get('createdAccountId');
                                $accountName = $parent->get('createdAccountName');
                            }
                            if ($parent->get('createdContactId')) {
                                $contactId = $parent->get('createdContactId');
                                $contactName = $parent->get('createdContactName');
                            }
                        }
                    } else {
                        if ($parent->getEntityType() == 'Contact') {
                            $contactId = $parent->id;
                            $contactName = $parent->get('name');
                        }
                    }
                }

                if (!$accountId && $parent->get('accountId') && $parent->getRelationParam('account',
                        'entity') == 'Account') {
                    $accountId = $parent->get('accountId');
                }
                if (!$contactId && $parent->get('contactId') && $parent->getRelationParam('contact',
                        'entity') == 'Contact') {
                    $contactId = $parent->get('contactId');
                }
            }

            $entity->set('accountId', $accountId);
            $entity->set('accountName', $accountName);

            $entity->set('contactId', $contactId);
            $entity->set('contactName', $contactName);

            if (
                $entity->get('accountId')
                &&
                !$entity->get('accountName')
            ) {
                $account = $this->getEntityManager()->getRepository('Account')->select([
                    'id',
                    'name'
                ])->get($entity->get('accountId'));
                if ($account) {
                    $entity->set('accountName', $account->get('name'));
                }
            }

            if (
                $entity->get('contactId')
                &&
                !$entity->get('contactName')
            ) {
                $contact = $this->getEntityManager()->getRepository('Contact')->select([
                    'id',
                    'name'
                ])->get($entity->get('contactId'));
                if ($contact) {
                    $entity->set('contactName', $contact->get('name'));
                }
            }
        }


        parent::beforeSave($entity, $options);
    }
}
