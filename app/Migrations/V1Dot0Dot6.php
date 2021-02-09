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
 *
 * AtroCore as well as EspoCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * AtroCore as well as EspoCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with EspoCRM. If not, see http://www.gnu.org/licenses/.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "EspoCRM" word
 * and "AtroCore" word.
 */

namespace ActivitiesTasks\Migrations;

use Treo\Core\Migration\Base;

/**
 * Class V1Dot0Dot6
 */
class V1Dot0Dot6 extends Base
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        $this->execute("DROP TABLE `call_lead`");
        $this->execute("DROP TABLE `lead_call_status`");
        $this->execute("DROP TABLE `lead_meeting`");
        $this->execute("DROP TABLE `lead_meeting_status`");
        $this->execute("DROP TABLE `lead`");
    }

    public function down(): void
    {
        $this->execute("CREATE TABLE `lead` (
            `id` VARCHAR(24) NOT NULL COLLATE utf8mb4_unicode_ci, 
            `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, 
            `status` VARCHAR(255) DEFAULT 'New' COLLATE utf8mb4_unicode_ci, 
            `salutation_name` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `first_name` VARCHAR(100) DEFAULT '' COLLATE utf8mb4_unicode_ci, 
            `last_name` VARCHAR(100) DEFAULT '' COLLATE utf8mb4_unicode_ci, 
            `title` VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `source` VARCHAR(255) DEFAULT '' COLLATE utf8mb4_unicode_ci, 
            `industry` VARCHAR(255) DEFAULT '' COLLATE utf8mb4_unicode_ci, 
            `website` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `address_street` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `address_city` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `address_state` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `address_country` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `address_postal_code` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `do_not_call` TINYINT(1) DEFAULT '0' NOT NULL COLLATE utf8mb4_unicode_ci, 
            `description` MEDIUMTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `created_at` DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `modified_at` DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `account_name` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `created_by_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `modified_by_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `assigned_user_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `created_account_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `created_contact_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            INDEX `IDX_CREATED_BY_ID` (created_by_id), 
            INDEX `IDX_MODIFIED_BY_ID` (modified_by_id), 
            INDEX `IDX_ASSIGNED_USER_ID` (assigned_user_id), 
            INDEX `IDX_CREATED_ACCOUNT_ID` (created_account_id), 
            INDEX `IDX_CREATED_CONTACT_ID` (created_contact_id), 
            INDEX `IDX_FIRST_NAME` (first_name, deleted), 
            INDEX `IDX_NAME` (first_name, last_name), 
            INDEX `IDX_STATUS` (status, deleted), 
            INDEX `IDX_CREATED_AT` (created_at, deleted), 
            INDEX `IDX_CREATED_AT_STATUS` (created_at, status), 
            INDEX `IDX_ASSIGNED_USER` (assigned_user_id, deleted), 
            INDEX `IDX_ASSIGNED_USER_STATUS` (assigned_user_id, status), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB");
        $this->execute("CREATE TABLE `lead_call_status` (
            `id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, 
            `call_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `lead_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `status` VARCHAR(36) DEFAULT 'None' COLLATE utf8mb4_unicode_ci, 
            `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, 
            INDEX `IDX_4749F04A50A89B2C` (call_id), 
            INDEX `IDX_4749F04A55458D` (lead_id), 
            UNIQUE INDEX `UNIQ_4749F04A50A89B2C55458D` (call_id, lead_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB");
        $this->execute("CREATE TABLE `lead_meeting_status` (
            `id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, 
            `lead_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `meeting_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `status` VARCHAR(36) DEFAULT 'None' COLLATE utf8mb4_unicode_ci, 
            `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, 
            INDEX `IDX_3B8DBDF855458D` (lead_id), 
            INDEX `IDX_3B8DBDF867433D9C` (meeting_id), 
            UNIQUE INDEX `UNIQ_3B8DBDF855458D67433D9C` (lead_id, meeting_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB");
        $this->execute("CREATE TABLE `lead_meeting` (
            `id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, 
            `lead_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `meeting_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, 
            INDEX `IDX_ACDBBD5755458D` (lead_id), 
            INDEX `IDX_ACDBBD5767433D9C` (meeting_id), 
            UNIQUE INDEX `UNIQ_ACDBBD5755458D67433D9C` (lead_id, meeting_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB");
        $this->execute("CREATE TABLE `call_lead` (
            `id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, 
            `call_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `lead_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, 
            `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, 
            INDEX `IDX_1F10069750A89B2C` (call_id), 
            INDEX `IDX_1F10069755458D` (lead_id), 
            UNIQUE INDEX `UNIQ_1F10069750A89B2C55458D` (call_id, lead_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB");
    }

    /**
     * @param string $sql
     */
    protected function execute(string $sql)
    {
        try {
            $this->getPDO()->exec($sql);
        } catch (\Throwable $e) {
            // ignore all
        }
    }
}
