

Espo.define('activitiestasks:views/record/panels/history', 'activitiestasks:views/record/panels/activities', function (Dep) {

    return Dep.extend({

        name: 'history',

        sortBy: 'dateStart',

        asc: false,

        rowActionsView: 'activitiestasks:views/record/row-actions/history',

        actionList: [],

        listLayout: {
            'Email': {
                rows: [
                    [
                        {name: 'ico', view: 'activitiestasks:views/fields/ico'},
                        {
                            name: 'name',
                            link: true,
                        },
                    ],
                    [
                        {name: 'status'},
                        {name: 'dateSent'},
                        {name: 'hasAttachment', view: 'views/email/fields/has-attachment'}
                    ]
                ]
            },
        },

        where: {
            scope: false
        },

        setupActionList: function () {
            Dep.prototype.setupActionList.call(this);
            this.actionList.push({
                action: 'archiveEmail',
                label: 'Archive Email',
                acl: 'create',
                aclScope: 'Email'
            });
        },

        getArchiveEmailAttributes: function (scope, data, callback) {
            data = data || {};
            var attributes = {
                dateSent: this.getDateTime().getNow(15),
                status: 'Archived',
                from: this.model.get('emailAddress'),
                to: this.getUser().get('emailAddress')
            };

            if (this.model.name == 'Contact') {
                if (this.getConfig().get('b2cMode')) {
                    attributes.parentType = 'Contact';
                    attributes.parentName = this.model.get('name');
                    attributes.parentId = this.model.id;
                } else {
                    if (this.model.get('accountId')) {
                        attributes.parentType = 'Account',
                            attributes.parentId = this.model.get('accountId');
                        attributes.parentName = this.model.get('accountName');
                    }
                }
            } else if (this.model.name == 'Lead') {
                attributes.parentType = 'Lead',
                    attributes.parentId = this.model.id
                attributes.parentName = this.model.get('name');
            }

            attributes.nameHash = {};
            attributes.nameHash[this.model.get('emailAddress')] = this.model.get('name');

            if (scope) {
                if (!attributes.parentId) {
                    if (this.checkParentTypeAvailability(scope, this.model.name)) {
                        attributes.parentType = this.model.name;
                        attributes.parentId = this.model.id;
                        attributes.parentName = this.model.get('name');
                    }
                } else {
                    if (attributes.parentType && !this.checkParentTypeAvailability(scope, attributes.parentType)) {
                        attributes.parentType = null;
                        attributes.parentId = null;
                        attributes.parentName = null;
                    }
                }
            }
            callback.call(this, attributes);
        },

        actionArchiveEmail: function (data) {
            var self = this;
            var link = 'emails';
            var scope = 'Email';

            var relate = null;
            if ('emails' in this.model.defs['links']) {
                relate = {
                    model: this.model,
                    link: this.model.defs['links']['emails'].foreign
                };
            }

            this.notify('Loading...');

            var viewName = this.getMetadata().get('clientDefs.' + scope + '.modalViews.edit') || 'views/modals/edit';

            this.getArchiveEmailAttributes(scope, data, function (attributes) {
                this.createView('quickCreate', viewName, {
                    scope: scope,
                    relate: relate,
                    attributes: attributes
                }, function (view) {
                    view.render();
                    view.notify(false);
                    this.listenToOnce(view, 'after:save', function () {
                        this.collection.fetch();
                        this.model.trigger('after:relate');
                    }, this);
                });
            });
        },

        actionReply: function (data) {
            var id = data.id;
            if (!id) {
                return;
            }

            Espo.require('EmailHelper', function (EmailHelper) {
                var emailHelper = new EmailHelper(this.getLanguage(), this.getUser(), this.getDateTime(), this.getAcl());

                this.notify('Please wait...');

                this.getModelFactory().create('Email', function (model) {
                    model.id = id;
                    this.listenToOnce(model, 'sync', function () {
                        var attributes = emailHelper.getReplyAttributes(model, data, this.getPreferences().get('emailReplyToAllByDefault'));
                        var viewName = this.getMetadata().get('clientDefs.Email.modalViews.compose') || 'views/modals/compose-email';
                        this.createView('quickCreate', viewName, {
                            attributes: attributes,
                        }, function (view) {
                            view.render();

                            this.listenToOnce(view, 'after:save', function () {
                                this.collection.fetch();
                                this.model.trigger('after:relate');
                            }, this);

                            view.notify(false);
                        }.bind(this));
                    }, this);
                    model.fetch();
                }, this);
            }, this);
        }
    });
});

