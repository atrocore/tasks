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

declare(strict_types=1);

namespace ActivitiesTasks\Migrations;

use Treo\Core\Migration\Base;

/**
 * Migration class for version 1.1.0
 */
class V1Dot1Dot0 extends Base
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        $this->execute("DROP INDEX IDX_ACCOUNT_ID ON `call`;");
        $this->execute("DROP INDEX IDX_ASSIGNED_USER ON `call`;");
        $this->execute("DROP INDEX IDX_ASSIGNED_USER_ID ON `call`;");
        $this->execute("DROP INDEX IDX_ASSIGNED_USER_STATUS ON `call`;");
        $this->execute("DROP INDEX IDX_CREATED_BY_ID ON `call`;");
        $this->execute("DROP INDEX IDX_DATE_START ON `call`;");
        $this->execute("DROP INDEX IDX_DATE_START_STATUS ON `call`;");
        $this->execute("DROP INDEX IDX_MODIFIED_BY_ID ON `call`;");
        $this->execute("DROP INDEX IDX_STATUS ON `call`;");
        $this->execute("ALTER TABLE `call` DROP name, DROP status, DROP date_start, DROP date_end, DROP direction, DROP description, DROP created_at, DROP modified_at, DROP account_id, DROP created_by_id, DROP modified_by_id, DROP assigned_user_id;");
        $this->execute("DROP INDEX IDX_ACCOUNT_ID ON `meeting`;");
        $this->execute("DROP INDEX IDX_ASSIGNED_USER ON `meeting`;");
        $this->execute("DROP INDEX IDX_ASSIGNED_USER_ID ON `meeting`;");
        $this->execute("DROP INDEX IDX_ASSIGNED_USER_STATUS ON `meeting`;");
        $this->execute("DROP INDEX IDX_CREATED_BY_ID ON `meeting`;");
        $this->execute("DROP INDEX IDX_DATE_START ON `meeting`;");
        $this->execute("DROP INDEX IDX_DATE_START_STATUS ON `meeting`;");
        $this->execute("DROP INDEX IDX_MODIFIED_BY_ID ON `meeting`;");
        $this->execute("DROP INDEX IDX_PARENT ON `meeting`;");
        $this->execute("DROP INDEX IDX_STATUS ON `meeting`;");
        $this->execute("ALTER TABLE `meeting` DROP name, DROP status, DROP date_start, DROP date_end, DROP description, DROP created_at, DROP modified_at, DROP parent_id, DROP parent_type, DROP account_id, DROP created_by_id, DROP modified_by_id, DROP assigned_user_id");
        $this->execute("DROP TABLE contact_call_status;");
        $this->execute("DROP TABLE contact_meeting_status;");
        $this->execute("DROP TABLE call_contact;");
        $this->execute("DROP TABLE call_user;");
        $this->execute("DROP TABLE contact_meeting;");
        $this->execute("DROP TABLE meeting_user;");
        $this->execute("DROP TABLE meeting");
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {
        $this->execute("CREATE TABLE `meeting` (`id` VARCHAR(24) NOT NULL COLLATE utf8mb4_unicode_ci, `name` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, `status` VARCHAR(255) DEFAULT 'Planned' COLLATE utf8mb4_unicode_ci, `date_start` DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, `date_end` DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, `description` MEDIUMTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, `created_at` DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, `modified_at` DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, `parent_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `parent_type` VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `account_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `created_by_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `modified_by_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `assigned_user_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX `IDX_PARENT` (parent_id, parent_type), INDEX `IDX_ACCOUNT_ID` (account_id), INDEX `IDX_CREATED_BY_ID` (created_by_id), INDEX `IDX_MODIFIED_BY_ID` (modified_by_id), INDEX `IDX_ASSIGNED_USER_ID` (assigned_user_id), INDEX `IDX_DATE_START_STATUS` (date_start, status), INDEX `IDX_DATE_START` (date_start, deleted), INDEX `IDX_STATUS` (status, deleted), INDEX `IDX_ASSIGNED_USER` (assigned_user_id, deleted), INDEX `IDX_ASSIGNED_USER_STATUS` (assigned_user_id, status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `contact_call_status` (`id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, `contact_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `call_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `status` VARCHAR(36) DEFAULT 'None' COLLATE utf8mb4_unicode_ci, `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, INDEX `IDX_D0740B80E7A1254A` (contact_id), INDEX `IDX_D0740B8050A89B2C` (call_id), UNIQUE INDEX `UNIQ_D0740B80E7A1254A50A89B2C` (contact_id, call_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `contact_meeting_status` (`id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, `contact_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `meeting_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `status` VARCHAR(36) DEFAULT 'None' COLLATE utf8mb4_unicode_ci, `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, INDEX `IDX_895DA9EE7A1254A` (contact_id), INDEX `IDX_895DA9E67433D9C` (meeting_id), UNIQUE INDEX `UNIQ_895DA9EE7A1254A67433D9C` (contact_id, meeting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `call_contact` (`id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, `contact_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `call_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, INDEX `IDX_99C77F0DE7A1254A` (contact_id), INDEX `IDX_99C77F0D50A89B2C` (call_id), UNIQUE INDEX `UNIQ_99C77F0DE7A1254A50A89B2C` (contact_id, call_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `contact_meeting` (`id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, `contact_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `meeting_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, INDEX `IDX_6F3AC0B8E7A1254A` (contact_id), INDEX `IDX_6F3AC0B867433D9C` (meeting_id), UNIQUE INDEX `UNIQ_6F3AC0B8E7A1254A67433D9C` (contact_id, meeting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `call_user` (`id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, `user_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `call_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `status` VARCHAR(36) DEFAULT 'None' COLLATE utf8mb4_unicode_ci, `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, INDEX `IDX_BA12B115A76ED395` (user_id), INDEX `IDX_BA12B11550A89B2C` (call_id), UNIQUE INDEX `UNIQ_BA12B115A76ED39550A89B2C` (user_id, call_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `meeting_user` (`id` INT AUTO_INCREMENT NOT NULL UNIQUE COLLATE utf8mb4_unicode_ci, `user_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `meeting_id` VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, `status` VARCHAR(36) DEFAULT 'None' COLLATE utf8mb4_unicode_ci, `deleted` TINYINT(1) DEFAULT '0' COLLATE utf8mb4_unicode_ci, INDEX `IDX_61622E9BA76ED395` (user_id), INDEX `IDX_61622E9B67433D9C` (meeting_id), UNIQUE INDEX `UNIQ_61622E9BA76ED39567433D9C` (user_id, meeting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;");
        $this->execute("ALTER TABLE `call` ADD name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD status VARCHAR(255) DEFAULT 'Planned' COLLATE utf8mb4_unicode_ci, ADD date_start DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD date_end DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD direction VARCHAR(255) DEFAULT 'Outbound' COLLATE utf8mb4_unicode_ci, ADD description MEDIUMTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD created_at DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD modified_at DATETIME DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD account_id VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD created_by_id VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD modified_by_id VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD assigned_user_id VARCHAR(24) DEFAULT NULL COLLATE utf8mb4_unicode_ci;");
        $this->execute("CREATE INDEX IDX_ACCOUNT_ID ON `call` (account_id);");
        $this->execute("CREATE INDEX IDX_CREATED_BY_ID ON `call` (created_by_id);");
        $this->execute("CREATE INDEX IDX_MODIFIED_BY_ID ON `call` (modified_by_id);");
        $this->execute("CREATE INDEX IDX_ASSIGNED_USER_ID ON `call` (assigned_user_id);");
        $this->execute("CREATE INDEX IDX_DATE_START_STATUS ON `call` (date_start, status);");
        $this->execute("CREATE INDEX IDX_DATE_START ON `call` (date_start, deleted);");
        $this->execute("CREATE INDEX IDX_STATUS ON `call` (status, deleted);");
        $this->execute("CREATE INDEX IDX_ASSIGNED_USER ON `call` (assigned_user_id, deleted);");
        $this->execute("CREATE INDEX IDX_ASSIGNED_USER_STATUS ON `call` (assigned_user_id, status)");
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
