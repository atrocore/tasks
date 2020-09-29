<?php

namespace ActivitiesTasks\Entities;

class Lead extends \Espo\Core\Entities\Person
{

    protected function _getName()
    {
        if (!array_key_exists('name', $this->valuesContainer) || !$this->valuesContainer['name']) {
            if ($this->get('accountName')) {
                return $this->get('accountName');
            } else {
                if ($this->get('emailAddress')) {
                    return $this->get('emailAddress');
                } else {
                    if ($this->get('phoneNumber')) {
                        return $this->get('phoneNumber');
                    }
                }
            }
        }
        return $this->valuesContainer['name'];
    }

    protected function _hasName()
    {
        if (array_key_exists('name', $this->valuesContainer)) {
            return true;
        }
        if ($this->has('accountName')) {
            return true;
        } else {
            if ($this->has('emailAddress')) {
                return true;
            } else {
                if ($this->has('phoneNumber')) {
                    return true;
                }
            }
        }
    }
}
