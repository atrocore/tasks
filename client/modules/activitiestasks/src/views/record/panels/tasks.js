

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
