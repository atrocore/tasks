<?php

declare(strict_types=1);

namespace ActivitiesTasks\Listeners;

use Treo\Listeners\AbstractListener;
use Treo\Core\EventManager\Event;

/**
 * Class EntityManagerController
 *
 * @author m.kokhanskiy@treolabs.com
 */
class EntityManagerController extends AbstractListener
{

    protected $params = [
        'hasActivities',
        'hasTasks'
    ];

    /**
     * @param Event $event
     */
    public function afterActionCreateEntity(Event $event)
    {
        $data = $event->getArgument('data');
        $this->setParams($data);
    }

    /**
     * @param Event $event
     */
    public function beforeActionUpdateEntity(Event $event)
    {
        $data = $event->getArgument('data');
        $this->setParams($data);
    }

    /**
     * Set all $this->params in scope
     *
     * @param $data
     */
    protected function setParams($data)
    {
        $scope = $data->name;

        foreach ($this->params as $param) {
            $value = !empty($data->{$param});

            $this->setValueOfParam($scope, $param, $value);
        }
    }

    /**
     * Set param in metadata
     *
     * @param string $scope
     * @param string $param
     * @param bool $value
     */
    protected function setValueOfParam(string $scope, string $param, bool $value): void
    {
        //set value
        $data[$param] = $value;

        $this
            ->getContainer()
            ->get('metadata')
            ->set("scopes", $scope, $data);

        // save
        $this->getContainer()->get('metadata')->save();
    }
}
