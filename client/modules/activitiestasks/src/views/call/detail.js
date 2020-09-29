

Espo.define('activitiestasks:views/call/detail', ['views/detail', 'activitiestasks:views/meeting/detail'], function (Dep, MeetingDetail) {

    return Dep.extend({

        setup: function () {
            Dep.prototype.setup.call(this);

            MeetingDetail.prototype.controlSendInvitationsButton.call(this);
            this.listenTo(this.model, 'change', function () {
                if (
                    this.model.hasChanged('status')
                    ||
                    this.model.hasChanged('teamsIds')
                ) {
                    MeetingDetail.prototype.controlSendInvitationsButton.call(this);
                }
            }.bind(this));
        },

        actionSendInvitations: function () {
            this.confirm(this.translate('confirmation', 'messages'), function () {
                this.disableMenuItem('sendInvitations');
                this.notify('Sending...');
                $.ajax({
                    url: 'Call/action/sendInvitations',
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

