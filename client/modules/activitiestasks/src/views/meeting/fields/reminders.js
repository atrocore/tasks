

Espo.define('activitiestasks:views/meeting/fields/reminders', 'views/fields/base', function (Dep) {

    return Dep.extend({

        detailTemplate: 'activitiestasks:meeting/fields/reminders/detail',

        listTemplate: 'activitiestasks:meeting/fields/reminders/detail',

        editTemplate: 'activitiestasks:meeting/fields/reminders/edit',

        events: {
            'click [data-action="addReminder"]': function () {
                var type = this.getMetadata().get('entityDefs.Reminder.fields.type.default');
                var seconds = this.getMetadata().get('entityDefs.Reminder.fields.seconds.default');

                var item = {
                    type: type,
                    seconds: seconds
                };

                this.reminderList.push(item);

                this.addItemHtml(item);

                this.trigger('change');
            },
            'click [data-action="removeReminder"]': function (e) {
                var $reminder = $(e.currentTarget).closest('.reminder');
                var index = $reminder.index();
                $reminder.remove();

                this.reminderList.splice(index, 1);
            },
        },

        getAttributeList: function () {
            return [this.name];
        },

        setup: function () {
            if (this.model.isNew() && !this.model.get(this.name) && this.model.name != 'Preferences') {
                this.reminderList = this.getPreferences().get('defaultReminders') || [];
            } else {
                this.reminderList = this.model.get(this.name) || [];
            }

            this.listenTo(this.model, 'change:' + this.name, function () {
                this.reminderList = this.model.get(this.name) || [];
            }, this);

            this.typeList = this.getMetadata().get('entityDefs.Reminder.fields.type.options');
            this.secondsList = this.getMetadata().get('entityDefs.Reminder.fields.seconds.options');
        },

        afterRender: function () {
            if (this.mode == 'edit') {
                this.$container = this.$el.find('.reminders-container');
                this.reminderList.forEach(function (item) {
                    this.addItemHtml(item);
                }, this);
            }
        },

        updateType: function (type, index) {
            this.reminderList[index].type = type;
            this.trigger('change');
        },

        updateSeconds: function (seconds, index) {
            this.reminderList[index].seconds = seconds;
            this.trigger('change');
        },

        addItemHtml: function (item) {
            var $item = $('<div>').addClass('input-group').addClass('reminder');

            var $type = $('<select>').attr('name', 'type').addClass('form-control');
            this.typeList.forEach(function (type) {
                var $o = $('<option>').attr('value', type).text(this.getLanguage().translateOption(type, 'reminderTypes'));
                $type.append($o);
            }, this);
            $type.val(item.type);

            $type.on('change', function () {
                this.updateType($type.val(), $type.closest('.reminder').index());
            }.bind(this));

            var $seconds = $('<select>').attr('name', 'seconds').addClass('form-control');
            this.secondsList.forEach(function (seconds) {
                var $o = $('<option>').attr('value', seconds).text(this.stringifySeconds(seconds));
                $seconds.append($o);
            }, this);
            $seconds.val(item.seconds);

            $seconds.on('change', function () {
                this.updateSeconds(parseInt($seconds.val()), $seconds.closest('.reminder').index());
            }.bind(this));

            var $remove = $('<button>').addClass('btn')
                .addClass('btn-link')
                .css('margin-left', '5px')
                .attr('type', 'button')
                .attr('tabindex', '-1')
                .attr('data-action', 'removeReminder')
                .html('<span class="fas fa-times"></span>');

            $item.append($('<div class="input-group-btn">').append($type))
                .append($seconds)
                .append($('<div class="input-group-btn">').append($remove));

            this.$container.append($item);
        },

        stringifySeconds: function (seconds) {
            if (!seconds) {
                return this.translate('on time', 'labels', 'Meeting');
            }
            var d = seconds;
            var days = Math.floor(d / (86400));
            d = d % (86400);
            var hours = Math.floor(d / (3600));
            d = d % (3600);
            var minutes = Math.floor(d / (60));

            var parts = [];
            if (days) {
                parts.push(days + '' + this.getLanguage().translate('d'));
            }
            if (hours) {
                parts.push(hours + '' + this.getLanguage().translate('h'));
            }
            if (minutes) {
                parts.push(minutes + '' + this.getLanguage().translate('m'));
            }
            return parts.join(' ') + ' ' + this.translate('before', 'labels', 'Meeting');
        },

        convertSeconds: function (seconds) {
            return seconds;
        },

        getDetailItemHtml: function (item) {
            var body = this.getLanguage().translateOption(item.type, 'reminderTypes') + ' ' + this.stringifySeconds(item.seconds);
            return '<div>' + body + '</div>';
        },

        getValueForDisplay: function () {
            if (this.mode == 'detail' || this.mode == 'list') {
                var html = '';
                this.reminderList.forEach(function (item) {
                    html += this.getDetailItemHtml(item);
                }, this);
                return html;
            }
        },

        fetch: function () {
            var data = {};
            data[this.name] = Espo.Utils.cloneDeep(this.reminderList);
            return data;
        },


    });

});
