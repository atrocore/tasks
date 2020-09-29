<?php

namespace ActivitiesTasks\SelectManagers;

class Lead extends \Espo\Core\SelectManagers\Base
{
    protected function filterActive(&$result)
    {
        $result['whereClause'][] = array(
            'status!=' => ['Converted', 'Recycled', 'Dead']
        );
    }

    protected function filterActual(&$result)
    {
        $result['whereClause'][] = array(
            'status!=' => ['Converted', 'Recycled', 'Dead']
        );
    }
}

