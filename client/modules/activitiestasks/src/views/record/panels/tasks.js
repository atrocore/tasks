

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

Espo.define('activitiestasks:views/record/panels/tasks', 'views/record/panels/relationship', function (Dep) {

    return Dep.extend({

        name: 'tasks',

        template: 'activitiestasks:record/panels/tasks',

        tabList: ['actual', 'completed'],

        defaultTab: 'actual',

        sortBy: 'createdAt',

        asc: false,

        buttonList: [
            {
                action: 'createTask',
                title: 'Create Task',
                acl: 'create',
                aclScope: 'Task',
                html: '<span class="fas fa-plus"></span>',
            }
        ],

        listLayout: {
            rows: [
                [
                    {
                        name: 'name',
                        link: true,
                    },
                    {
                        name: 'isOverdue'
                    }
                ],
                [
                    {name: 'assignedUser'},
                    {name: 'status'},
                    {name: 'dateEnd'},
                ]
            ]
        },


        events: _.extend({
            'click button.tab-switcher': function (e) {
                var $target = $(e.currentTarget);
                this.$el.find('button.tab-switcher').removeClass('active');
                $target.addClass('active');

                this.currentTab = $target.data('tab');

                this.collection.where = this.where = [
                    {
                        type: 'primary',
                        value: this.currentTab
                    }
                ];

                this.listenToOnce(this.collection, 'sync', function () {
                    this.notify(false);
                }.bind(this));
                this.notify('Loading...');
                this.collection.fetch();

                this.getStorage().set('state', this.getStorageKey(), this.currentTab);
            }
        }, Dep.prototype.events),

        data: function () {
            return {
                currentTab: this.currentTab,
                tabList: this.tabList
            };
        },

        getStorageKey: function () {
            return 'tasks-' + this.model.name + '-' + this.name;
        },

        setup: function () {
            this.scope = this.model.name;

            this.link = 'tasks';

            if (this.scope == 'Account') {
                this.link = 'tasksPrimary';
            }

            this.currentTab = this.getStorage().get('state', this.getStorageKey()) || this.defaultTab;

            this.where = [
                {
                    type: 'primary',
                    value: this.currentTab
                }
            ];
        },

        afterRender: function () {

            var url = this.model.name + '/' + this.model.id + '/' + this.link;

            if (!this.getAcl().check('Task', 'read')) {
                this.$el.find('.list-container').html(this.translate('No Access'));
                this.$el.find('.button-container').remove();
                return;
            }
            ;

            this.getCollectionFactory().create('Task', function (collection) {
                this.collection = collection;
                collection.seeds = this.seeds;
                collection.url = url;
                collection.where = this.where;
                collection.sortBy = this.sortBy;
                collection.asc = this.asc;
                collection.maxSize = this.getConfig().get('recordsPerPageSmall') || 5;

                var rowActionsView = 'activitiestasks:views/record/row-actions/tasks';

                this.createView('list', 'views/record/list-expanded', {
                    el: this.getSelector() + ' > .list-container',
                    pagination: false,
                    type: 'listRelationship',
                    rowActionsView: rowActionsView,
                    checkboxes: false,
                    collection: collection,
                    listLayout: this.listLayout,
                    skipBuildRows: true
                }, function (view) {
                    view.getSelectAttributeList(function (selectAttributeList) {
                        if (selectAttributeList) {
                            this.collection.data.select = selectAttributeList.join(',');
                        }
                        this.collection.fetch();
                    }.bind(this));
                });
            }, this);
        },

        actionCreateTask: function (data) {
            var self = this;
            var link = this.link;
            if (this.scope === 'Account') {
                link = 'tasks';
            }
            var scope = 'Task';
            var foreignLink = this.model.defs['links'][link].foreign;

            this.notify('Loading...');

            var viewName = this.getMetadata().get('clientDefs.' + scope + '.modalViews.edit') || 'views/modals/edit';

            this.createView('quickCreate', viewName, {
                scope: scope,
                relate: {
                    model: this.model,
                    link: foreignLink,
                }
            }, function (view) {
                view.render();
                view.notify(false);
                this.listenToOnce(view, 'after:save', function () {
                    this.collection.fetch();
                    this.model.trigger('after:relate');
                }, this);
            });

        },

        actionRefresh: function () {
            this.collection.fetch();
        },

        actionComplete: function (data) {
            var id = data.id;
            if (!id) {
                return;
            }
            var model = this.collection.get(id);
            model.save({
                status: 'Completed'
            }, {
                patch: true,
                success: function () {
                    this.collection.fetch();
                }.bind(this)
            });
        }

    });
});
