

Espo.define('activitiestasks:views/meeting/record/detail', 'views/record/detail', function (Dep) {

    return Dep.extend({

        duplicateAction: true,

        setupActionItems: function () {
            Dep.prototype.setupActionItems.call(this);
            if (this.getAcl().checkModel(this.model, 'edit')) {
                if (['Held', 'Not Held'].indexOf(this.model.get('status')) == -1) {
                    this.dropdownItemList.push({
                        'label': 'Set Held',
                        'name': 'setHeld'
                    });
                    this.dropdownItemList.push({
                        'label': 'Set Not Held',
                        'name': 'setNotHeld'
                    });
                }
            }
        },

        manageAccessEdit: function (second) {
            Dep.prototype.manageAccessEdit.call(this, second);

            if (second) {
                if (!this.getAcl().checkModel(this.model, 'edit', true)) {
                    this.hideActionItem('setHeld');
                    this.hideActionItem('setNotHeld');
                }
            }
        },

        actionSetHeld: function () {
            this.model.save({
                status: 'Held'
            }, {
                patch: true,
                success: function () {
                    Espo.Ui.success(this.translate('Saved', 'labels', 'Meeting'));
                    this.removeButton('setHeld');
                    this.removeButton('setNotHeld');
                }.bind(this),
            });
        },

        actionSetNotHeld: function () {
            this.model.save({
                status: 'Not Held'
            }, {
                patch: true,
                success: function () {
                    Espo.Ui.success(this.translate('Saved', 'labels', 'Meeting'));
                    this.removeButton('setHeld');
                    this.removeButton('setNotHeld');
                }.bind(this),
            });
        },

    });
});

