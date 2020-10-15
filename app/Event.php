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

namespace ActivitiesTasks;

use Treo\Core\ModuleManager\AbstractEvent;

/**
 * Class Event
 */
class Event extends AbstractEvent
{
    const ACTIVITY_GROUP_ID = '_delimiter_activity';
    const ACTIVITY_GROUP_NAME = 'Activity';

    /**
     * @var array
     */
    protected $tabList = [];

    /**
     * @var array
     */
    protected $twoLevelTabList = [];

    /**
     * @var array
     */
    protected $items
        = [
            "Task",
            "Meeting",
            "Call"
        ];

    /**
     * @inheritdoc
     */
    public function afterInstall(): void
    {
        $this->prepareNavMenu();
    }

    /**
     * @inheritdoc
     */
    public function afterDelete(): void
    {
    }


    /**
     * Prepare NavMenu
     */
    protected function prepareNavMenu(): void
    {
        // get config
        $config = $this->getContainer()->get('config');

        // prepare data
        $this->tabList = $config->get('tabList');
        $this->twoLevelTabList = $config->get('twoLevelTabList');

        // prepare TabList
        $this->prepareTabList();

        // prepare twoLevelTabList
        $this->prepareTwoLevelTabList();

        // save
        $config->set('tabList', $this->tabList);
        $config->set('twoLevelTabList', $this->twoLevelTabList);
        $config->save();
    }

    /**
     * Prepare tab list
     */
    protected function prepareTabList(): void
    {
        foreach ($this->items as $v) {
            if (!in_array($v, $this->tabList)) {
                $this->tabList[] = $v;
            }
        }
    }

    /**
     * Prepare two level tab list
     */
    protected function prepareTwoLevelTabList(): void
    {
        // create group
        $this->createGroup();

        foreach ($this->twoLevelTabList as $k => $item) {
            if (!is_string($item) && $item->id == self::ACTIVITY_GROUP_ID) {
                foreach ($this->items as $v) {
                    if (!in_array($v, $item->items)) {
                        $this->twoLevelTabList[$k]->items[] = $v;
                    }
                }
            }
        }
    }

    /**
     * Create group
     *
     * @return bool
     */
    protected function createGroup(): bool
    {
        foreach ($this->twoLevelTabList as $item) {
            if (!is_string($item) && $item->id == self::ACTIVITY_GROUP_ID) {
                return false;
            }
        }

        $this->twoLevelTabList[] = (object)[
            "id" => self::ACTIVITY_GROUP_ID,
            "name" => self::ACTIVITY_GROUP_NAME,
            "items" => [],
            "iconClass" => "fas fa-list-alt"
        ];

        return true;
    }
}
