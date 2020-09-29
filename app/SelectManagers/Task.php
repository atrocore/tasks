<?php

namespace ActivitiesTasks\SelectManagers;

class Task extends \Espo\Core\SelectManagers\Base
{
    protected function boolFilterActual(&$result)
    {
        $this->filterActual($result);
    }

    protected function boolFilterCompleted(&$result)
    {
        $this->filterCompleted($result);
    }

    protected function filterActual(&$result)
    {
        $result['whereClause'][] = array(
            'status!=' => ['Completed', 'Canceled', 'Deferred']
        );
    }

    protected function filterDeferred(&$result)
    {
        $result['whereClause'][] = array(
            'status=' => 'Deferred'
        );
    }

    protected function filterActualStartingNotInFuture(&$result)
    {
        $result['whereClause'][] = array(
            array(
                'status!=' => ['Completed', 'Canceled', 'Deferred']
            ),
            array(
                'OR' => array(
                    array(
                        'dateStart' => null
                    ),
                    array(
                        'dateStart!=' => null,
                        'OR' => array(
                            $this->convertDateTimeWhere(array(
                                'type' => 'past',
                                'attribute' => 'dateStart',
                                'timeZone' => $this->getUserTimeZone()
                            )),
                            $this->convertDateTimeWhere(array(
                                'type' => 'today',
                                'attribute' => 'dateStart',
                                'timeZone' => $this->getUserTimeZone()
                            ))
                        )
                    )
                )
            )
        );
    }

    protected function filterCompleted(&$result)
    {
        $result['whereClause'][] = array(
            'status' => ['Completed']
        );
    }

    protected function filterOverdue(&$result)
    {
        $result['whereClause'][] = [
            $this->convertDateTimeWhere(array(
                'type' => 'past',
                'attribute' => 'dateEnd',
                'timeZone' => $this->getUserTimeZone()
            )),
            [
                array(
                    'status!=' => ['Completed', 'Canceled']
                )
            ]
        ];
    }

    protected function filterTodays(&$result)
    {
        $result['whereClause'][] = $this->convertDateTimeWhere(array(
            'type' => 'today',
            'attribute' => 'dateEnd',
            'timeZone' => $this->getUserTimeZone()
        ));
    }

    public function convertDateTimeWhere($item)
    {
        $result = parent::convertDateTimeWhere($item);

        if (empty($result)) {
            return null;
        }
        $attribute = null;
        if (!empty($item['field'])) { // for backward compatibility
            $attribute = $item['field'];
        }
        if (!empty($item['attribute'])) {
            $attribute = $item['attribute'];
        }

        if ($attribute != 'dateStart' && $attribute != 'dateEnd') {
            return $result;
        }

        $attributeDate = $attribute . 'Date';

        $dateItem = array(
            'attribute' => $attributeDate,
            'type' => $item['type']
        );
        if (!empty($item['value'])) {
            $dateItem['value'] = $item['value'];
        }

        $result = array(
            'OR' => array(
                'AND' => [
                    $result,
                    $attributeDate . '=' => null
                ],
                $this->getWherePart($dateItem)
            )
        );

        return $result;
    }
}

