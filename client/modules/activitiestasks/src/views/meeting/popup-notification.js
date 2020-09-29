

Espo.define('activitiestasks:views/meeting/popup-notification', 'views/popup-notification', function (Dep) {

    return Dep.extend({

        type: 'event',

        style: 'primary',

        template: 'activitiestasks:meeting/popup-notification',

        closeButton: true,

        setup: function () {
            this.wait(true);

            if (this.notificationData.entityType) {
                this.getModelFactory().create(this.notificationData.entityType, function (model) {

                    var dateAttribute = 'dateStart';
                    if (this.notificationData.entityType === 'Task') {
                        dateAttribute = 'dateEnd';
                    }

                    this.dateAttribute = dateAttribute;

                    model.set(dateAttribute, this.notificationData[dateAttribute]);

                    this.createView('dateField', 'views/fields/datetime', {
                        model: model,
                        mode: 'detail',
                        el: this.options.el + ' .field[data-name="' + dateAttribute + '"]',
                        defs: {
                            name: dateAttribute
                        },
                        readOnly: true
                    });

                    this.wait(false);
                }, this);
            }
        },

        data: function () {
            return _.extend({
                header: this.translate(this.notificationData.entityType, 'scopeNames'),
                dateAttribute: this.dateAttribute
            }, Dep.prototype.data.call(this));
        },

        onCancel: function () {
            $.ajax({
                url: 'Activities/action/removePopupNotification',
                type: 'POST',
                data: JSON.stringify({
                    id: this.notificationId
                })
            });
        },

    });
});

