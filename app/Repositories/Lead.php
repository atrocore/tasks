<?php

namespace ActivitiesTasks\Repositories;

use Espo\ORM\Entity;

class Lead extends \Espo\Core\ORM\Repositories\RDB
{
    public function afterSave(Entity $entity, array $options = array())
    {
        parent::afterSave($entity, $options);

        if ($entity->has('targetListId')) {
            $this->relate($entity, 'targetLists', $entity->get('targetListId'));
        }
    }

    public function handleSelectParams(&$params)
    {
        parent::handleSelectParams($params);
        if (array_key_exists('select', $params)) {
            if (in_array('name', $params['select'])) {
                $additionalAttributeList = ['emailAddress', 'phoneNumber', 'accountName'];
                foreach ($additionalAttributeList as $attribute) {
                    if (!in_array($attribute, $params['select'])) {
                        $params['select'][] = $attribute;
                    }
                }
            }
        }
    }
}
