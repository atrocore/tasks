<?php
/*
 * This file is part of EspoCRM and/or AtroCore.
 *
 * EspoCRM - Open Source CRM application.
 * Copyright (C) 2014-2019 Yuri Kuznetsov, Taras Machyshyn, Oleksiy Avramenko
 * Website: http://www.espocrm.com
 *
 * AtroCore is EspoCRM-based Open Source application.
 * Copyright (C) 2020 AtroCore UG (haftungsbeschränkt).
 *  Website: https://treolabs.com
 *
 *  AtroCore as well as EspoCRM is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  AtroCore as well as EspoCRM is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with EspoCRM. If not, see http://www.gnu.org/licenses/.
 *
 *  The interactive user interfaces in modified source and object code versions
 *  of this program must display Appropriate Legal Notices, as required under
 *  Section 5 of the GNU General Public License version 3.
 *
 *  In accordance with Section 7(b) of the GNU General Public License version 3,
 *  these Appropriate Legal Notices must retain the display of the "EspoCRM" word
 *  and "AtroCore" word.
 */

declare(strict_types=1);

namespace ActivitiesTasks\Listeners;

use Treo\Listeners\AbstractListener;
use Treo\Core\EventManager\Event;

/**
 * Class EntityManagerController
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
