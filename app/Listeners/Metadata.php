<?php

declare(strict_types=1);


namespace ActivitiesTasks\Listeners;

use Treo\Core\EventManager\Event;
use Treo\Listeners\AbstractListener;

/**
 * Class Metadata
 *
 * @author r.ratsun <r.ratsun@treolabs.com>
 */
class Metadata extends AbstractListener
{
    /**
     * Modify
     *
     * @param Event $event
     */
    public function modify(Event $event): void
    {
        // get data
        $data = $event->getArgument('data');

        // add dashlets
        $this->addDashlets($data);

        // add activities
        $this->addActivities($data);

        // add tasks
        $this->addTasks($data);

        $this->addLinkContactEntityStatus($data, 'Meeting');
        $this->addLinkContactEntityStatus($data, 'Call');

        $this->addFieldsToSettings($data);

        $event->setArgument('data', $data);
    }

    /**
     * @param array $data
     */
    protected function addDashlets(array &$data): void
    {
        // add dashlets
        if (!isset($data['dashlets']['Activities']) && isset($data['hidedDashlets']['Activities'])) {
            $data['dashlets']['Activities'] = $data['hidedDashlets']['Activities'];
        }
        if (!isset($data['dashlets']['Tasks']) && isset($data['hidedDashlets']['Tasks'])) {
            $data['dashlets']['Tasks'] = $data['hidedDashlets']['Tasks'];
        }
        if (!isset($data['dashlets']['Calls']) && isset($data['hidedDashlets']['Calls'])) {
            $data['dashlets']['Calls'] = $data['hidedDashlets']['Calls'];
        }
        if (!isset($data['dashlets']['Emails']) && isset($data['hidedDashlets']['Emails'])) {
            $data['dashlets']['Emails'] = $data['hidedDashlets']['Emails'];
        }
        if (!isset($data['dashlets']['Meetings']) && isset($data['hidedDashlets']['Meetings'])) {
            $data['dashlets']['Meetings'] = $data['hidedDashlets']['Meetings'];
        }
    }

    /**
     * Add activities
     *
     * @param array $data
     */
    protected function addActivities(array &$data): void
    {
        foreach ($data['entityDefs'] as $entity => $row) {
            if (!empty($data['scopes'][$entity]['hasActivities'])) {
                // push to entityList
                if (!in_array($entity, $data['entityDefs']['Meeting']['fields']['parent']['entityList'])) {
                    $data['entityDefs']['Meeting']['fields']['parent']['entityList'][] = $entity;
                }
                // push to entityList
                if (!in_array($entity, $data['entityDefs']['Call']['fields']['parent']['entityList'])) {
                    $data['entityDefs']['Call']['fields']['parent']['entityList'][] = $entity;
                }

                // add link to entity
                if (!isset($data['entityDefs'][$entity]['links']['meetings'])) {
                    $data['entityDefs'][$entity]['links']['meetings'] = [
                        "type" => "hasChildren",
                        "entity" => "Meeting",
                        "foreign" => "parent",
                        "layoutRelationshipsDisabled" => true,
                        "audited" => true
                    ];
                }
                // add link to entity
                if (!isset($data['entityDefs'][$entity]['links']['calls'])) {
                    $data['entityDefs'][$entity]['links']['calls'] = [
                        "type" => "hasChildren",
                        "entity" => "Call",
                        "foreign" => "parent",
                        "layoutRelationshipsDisabled" => true,
                        "audited" => true
                    ];
                }
                // add to client defs
                foreach (['detail', 'detailSmall'] as $panel) {
                    $panelData = [];
                    if (!empty($data['clientDefs'][$entity]['sidePanels'][$panel])) {
                        foreach ($data['clientDefs'][$entity]['sidePanels'][$panel] as $item) {
                            $panelData[$item['name']] = $item;
                        }
                    }

                    $panelData["activities"] = [
                        "name" => "activities",
                        "label" => "Activities",
                        "view" => "activitiestasks:views/record/panels/activities",
                        "aclScope" => "Activities"
                    ];
                    $panelData["history"] = [
                        "name" => "history",
                        "label" => "History",
                        "view" => "activitiestasks:views/record/panels/history",
                        "aclScope" => "Activities"
                    ];

                    $data['clientDefs'][$entity]['sidePanels'][$panel] = array_values($panelData);
                }
            }
        }
    }

    /**
     * @param $data
     */
    protected function addFieldsToSettings($data) :void
    {
        $data['entityDefs']['Settings']['fields'][] =
            [
                "activitiesEntityList" => [
                    "type" => "multiEnum",
                    "view" => "views/settings/fields/activities-entity-list"
                ]
            ];

        $data['entityDefs']['Settings']['fields'][] =
            [
                "historyEntityList" => [
                    "type" => "multiEnum",
                    "view" => "views/settings/fields/history-entity-list"
                ]
            ];
    }

    /**
     * @param $data
     * @param string $entity
     */
    protected function addLinkContactEntityStatus(&$data, string $entity) :void
    {
        $entityStatus = mb_strtolower($entity) . 'sStatus';
        $data['entityDefs'][$entity]['fields']['contactsStatus'] =
            [
                'type' => 'linkMultiple',
                'layoutDetailDisabled' => true,
                'layoutListDisabled' => true,
                'view' => 'activitiestasks:views/meeting/fields/contacts',
                'columns' =>
                    [
                        'status' => 'acceptanceStatus',
                    ],
                'orderBy' => 'name',
                'exportDisabled' => true,
            ];

        $data['entityDefs'][$entity]['links']['contactsStatus'] =
            [
                'type' => 'hasMany',
                'relationName' => "contact{$entity}Status",
                'entity' => 'Contact',
                'foreign' => $entityStatus,
                'additionalColumns' => [
                    'status' => [
                        'type' => 'varchar',
                        'len' => '36',
                        "default" => "None"
                    ]
                ]
            ];
        $data['entityDefs']['Contact']['links'][$entityStatus] =
            [
                'type' => 'hasMany',
                'relationName' => "contact{$entity}Status",
                'entity' => $entity,
                'foreign' => 'contactsStatus',
            ];
    }

    /**
     * Add tasks
     *
     * @param array $data
     */
    protected function addTasks(array &$data): void
    {
        foreach ($data['entityDefs'] as $entity => $row) {
            if (!empty($data['scopes'][$entity]['hasTasks'])) {
                // push to entityList
                if (!in_array($entity, $data['entityDefs']['Task']['fields']['parent']['entityList'])) {
                    $data['entityDefs']['Task']['fields']['parent']['entityList'][] = $entity;
                }
                $name = $entity != 'Task' ? lcfirst($entity) : lcfirst($entity) . 'Child';
                // add field
                $data['entityDefs']['Task']['fields'][lcfirst($entity)] = [
                    "type" => "link",
                    "readOnly" => true
                ];

                // add link
                $data['entityDefs']['Task']['links'][$name] = [
                    "type" => $name,
                    "entity" => $entity,
                ];
                // add link to entity
                $data['entityDefs'][$entity]['links']['tasks'] = [
                    "type" => "hasChildren",
                    "entity" => "Task",
                    "foreign" => "parent",
                    "layoutRelationshipsDisabled" => true
                ];

                // add to client defs
                foreach (['detail', 'detailSmall'] as $panel) {
                    $panelData = [];
                    if (!empty($data['clientDefs'][$entity]['sidePanels'][$panel])) {
                        foreach ($data['clientDefs'][$entity]['sidePanels'][$panel] as $item) {
                            $panelData[$item['name']] = $item;
                        }
                    }

                    $panelData["tasks"] = [
                        "name" => "tasks",
                        "label" => "Tasks",
                        "view" => "activitiestasks:views/record/panels/tasks",
                        "aclScope" => "Task"
                    ];

                    $data['clientDefs'][$entity]['sidePanels'][$panel] = array_values($panelData);
                }
            }
        }
    }
}
