

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

