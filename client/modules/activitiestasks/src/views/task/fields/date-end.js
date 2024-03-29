

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

Espo.define('activitiestasks:views/task/fields/date-end', 'views/fields/datetime-optional', function (Dep) {

    return Dep.extend({

        detailTemplate: 'activitiestasks:task/fields/date-end/detail',

        listTemplate: 'activitiestasks:task/fields/date-end/detail',

        data: function () {
            var data = Dep.prototype.data.call(this);

            if (!~['Completed', 'Canceled'].indexOf(this.model.get('status'))) {
                if (this.mode == 'list' || this.mode == 'detail') {
                    if (!this.isDate()) {
                        var value = this.model.get(this.name);
                        if (value) {
                            var d = this.getDateTime().toMoment(value);
                            var now = moment().tz(this.getDateTime().timeZone || 'UTC');
                            if (d.unix() < now.unix()) {
                                data.isOverdue = true;
                            }
                        }
                    } else {
                        var value = this.model.get(this.nameDate);
                        if (value) {
                            var d = moment.utc(value + ' 23:59', this.getDateTime().internalDateTimeFormat);
                            var now = this.getDateTime().getNowMoment();
                            if (d.unix() < now.unix()) {
                                data.isOverdue = true;
                            }
                        }
                    }
                }
            }
            return data;
        },

        setup: function () {
            Dep.prototype.setup.call(this);
            this.listenTo(this, 'change', function (e) {
                if (!this.model.get('dateEnd')) {
                    if (this.model.get('reminders')) {
                        this.model.set('reminders', []);
                    }
                }
            }, this);
        }

    });
});

