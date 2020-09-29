

Espo.define('activitiestasks:views/task/detail', 'views/detail', function (Dep) {

    return Dep.extend({

        setup: function () {
            Dep.prototype.setup.call(this);
            if (!~['Completed', 'Canceled'].indexOf(this.model.get('status'))) {
                if (this.getAcl().checkModel(this.model, 'edit')) {
                    this.menu.buttons.push({
                        'label': 'Complete',
                        'action': 'setCompletedMain',
                        'iconHtml': '<span class="fas fa-check fa-sm"></span>',
                        'acl': 'edit',
                    });
                }
                this.listenTo(this.model, 'sync', function () {
                    if (~['Completed', 'Canceled'].indexOf(this.model.get('status'))) {
                        this.$el.find('[data-action="setCompletedMain"]').remove();
                    }
                }, this);
            }
        },

        actionSetCompletedMain: function (data) {
            var id = data.id;

            this.model.save({
                status: 'Completed'
            }, {
                patch: true,
                success: function () {
                    Espo.Ui.success(this.translate('Saved'));
                    this.$el.find('[data-action="setCompletedMain"]').remove();
                }.bind(this),
            });
        },
    });

});
