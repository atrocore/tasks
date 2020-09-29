<?php

namespace ActivitiesTasks\Acl;

use \Espo\Entities\User;
use \Espo\ORM\Entity;

class Meeting extends \Espo\Core\Acl\Base
{
    protected $ownerUserIdAttribute = 'usersIds';

    public function checkEntityRead(User $user, Entity $entity, $data)
    {
        if ($this->checkEntity($user, $entity, $data, 'read')) {
            return true;
        }

        if (is_object($data)) {
            if ($data->read === 'own' || $data->read === 'team') {
                if ($entity->hasLinkMultipleId('users', $user->id)) {
                    return true;
                }
            }
        }

        return false;
    }
}

