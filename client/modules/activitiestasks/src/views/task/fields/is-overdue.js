

Espo.define('activitiestasks:views/task/fields/is-overdue', 'views/fields/base', function (Dep) {

    return Dep.extend({

        readOnly: true,

        _template: '{{#if isOverdue}}<span class="label label-danger">{{translate "overdue"}}</span>{{/if}}',

        data: function () {
            var isOverdue = false;
            if (['Completed', 'Canceled'].indexOf(this.model.get('status')) == -1) {
                if (this.model.has('dateEnd')) {
                    if (!this.isDate()) {
                        var value = this.model.get('dateEnd');
                        if (value) {
                            var d = this.getDateTime().toMoment(value);
                            var now = moment().tz(this.getDateTime().timeZone || 'UTC');
                            if (d.unix() < now.unix()) {
                                isOverdue = true;
                            }
                        }
                    } else {
                        var value = this.model.get('dateEndDate');
                        if (value) {
                            var d = moment.utc(value + ' 23:59', this.getDateTime().internalDateTimeFormat);
                            var now = this.getDateTime().getNowMoment();
                            if (d.unix() < now.unix()) {
                                isOverdue = true;
                            }
                        }
                    }

                }
            }
            return {
                isOverdue: isOverdue
            };
        },

        setup: function () {
            this.mode = 'detail';
        },

        isDate: function () {
            var dateValue = this.model.get('dateEnd');
            if (dateValue && dateValue != '') {
                return true;
            }
            return false;
        },

    });

});
