

Espo.define('activitiestasks:views/meeting/detail', 'views/detail', function (Dep) {

    return Dep.extend({
        setup: function () {
            Dep.prototype.setup.call(this);
            this.controlSendInvitationsButton();
            this.listenTo(this.model, 'change', function () {
                if (
                    this.model.hasChanged('status')
                    ||
                    this.model.hasChanged('teamsIds')
                ) {
                    this.controlSendInvitationsButton();
                }
            }.bind(this));
        },

        controlSendInvitationsButton: function () {
            var show = true;
            ;

            if (
                ~['Held', 'Not Held'].indexOf(this.model.get('status'))
            ) {
                show = false;
            }

            if (show && (!this.getAcl().checkModel(this.model, 'edit') || !this.getAcl().checkScope('Email', 'create'))) {
                show = false;
            }

            if (show) {
                var userIdList = this.model.getLinkMultipleIdList('users');
                var contactIdList = this.model.getLinkMultipleIdList('contactsStatus');
                var leadIdList = this.model.getLinkMultipleIdList('leadsStatus');

                if (!contactIdList.length && !leadIdList.length && !userIdList.length) {
                    show = false;
                }
            }
            if (show && this.getMetadata().get('clientDefs.Meeting.viewSendInvitations')) {
                this.addMenuItem('buttons', {
                    label: 'Send Invitations',
                    action: 'sendInvitations',
                    acl: 'edit',
                });
            } else {
                this.removeMenuItem('sendInvitations');
            }
        },

        actionSendInvitations: function () {
            this.confirm(this.translate('confirmation', 'messages'), function () {
                this.disableMenuItem('sendInvitations');
                this.notify('Sending...');
                $.ajax({
                    url: 'Meeting/action/sendInvitations',
                    type: 'POST',
                    data: JSON.stringify({
                        id: this.model.id
                    }),
                    success: function (result) {
                        if (result) {
                            this.notify('Sent', 'success');
                        } else {
                            Espo.Ui.warning(this.translate('nothingHasBeenSent', 'messages', 'Meeting'));
                        }

                        this.enableMenuItem('sendInvitations');
                    }.bind(this),
                    error: function () {
                        this.enableMenuItem('sendInvitations');
                    }.bind(this),
                });
            }, this);
        }

    });
});

