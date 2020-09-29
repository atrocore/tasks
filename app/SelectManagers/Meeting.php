<?php

namespace ActivitiesTasks\SelectManagers;

class Meeting extends \Espo\Core\SelectManagers\Base
{
    protected function accessOnlyOwn(&$result)
    {
        $this->setDistinct(true, $result);
        $this->addJoin(['users', 'usersAccess'], $result);
        $result['whereClause'][] = [
            'OR' => [
                'usersAccessMiddle.userId' => $this->getUser()->id,
                'assignedUserId' => $this->getUser()->id
            ]
        ];
    }

    protected function accessOnlyTeam(&$result)
    {
        $this->setDistinct(true, $result);
        $this->addLeftJoin(['teams', 'teamsAccess'], $result);
        $this->addLeftJoin(['users', 'usersAccess'], $result);

        $result['whereClause'][] = [
            'OR' => [
                'teamsAccessMiddle.teamId' => $this->getUser()->getLinkMultipleIdList('teams'),
                'usersAccessMiddle.userId' => $this->getUser()->id,
                'assignedUserId' => $this->getUser()->id
            ]
        ];
    }

    protected function boolFilterOnlyMy(&$result)
    {
        $this->addJoin(['users', 'usersFilterOnlyMy'], $result);
        $result['whereClause'][] = [
            'usersFilterOnlyMyMiddle.userId' => $this->getUser()->id,
            'OR' => [
                'usersFilterOnlyMyMiddle.status!=' => 'Declined',
                'usersFilterOnlyMyMiddle.status=' => null
            ]
        ];
    }

    protected function filterPlanned(&$result)
    {
        $result['whereClause'][] = array(
            'status' => 'Planned'
        );
    }

    protected function filterHeld(&$result)
    {
        $result['whereClause'][] = array(
            'status' => 'Held'
        );
    }

    protected function filterTodays(&$result)
    {
        $result['whereClause'][] = $this->convertDateTimeWhere(array(
            'type' => 'today',
            'attribute' => 'dateStart',
            'timeZone' => $this->getUserTimeZone()
        ));
    }
}
