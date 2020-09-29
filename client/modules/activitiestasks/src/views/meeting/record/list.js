

Espo.define('activitiestasks:views/meeting/record/list', 'views/record/list', function (Dep) {

    return Dep.extend({

        rowActionsView: 'activitiestasks:views/meeting/record/row-actions/default',

        setup: function () {
            Dep.prototype.setup.call(this);
            this.massActionList.push('setHeld');
            this.massActionList.push('setNotHeld');
        },

        actionSetHeld: function (data) {
            var id = data.id;
            if (!id) {
                return;
            }
            var model = this.collection.get(id);
            if (!model) {
                return;
            }

            model.set('status', 'Held');

            this.listenToOnce(model, 'sync', function () {
                this.notify(false);
                this.collection.fetch();
            }, this);

            this.notify('Saving...');
            model.save();
        },

        actionSetNotHeld: function (data) {
            var id = data.id;
            if (!id) {
                return;
            }
            var model = this.collection.get(id);
            if (!model) {
                return;
            }

            model.set('status', 'Not Held');

            this.listenToOnce(model, 'sync', function () {
                this.notify(false);
                this.collection.fetch();
            }, this);

            this.notify('Saving...');
            model.save();
        },

        massActionSetHeld: function () {
            this.notify('Please wait...');
            var data = {};
            data.ids = this.checkedList;
            $.ajax({
                url: this.collection.url + '/action/massSetHeld',
                type: 'POST',
                data: JSON.stringify(data)
            }).done(function (result) {
                this.notify(false);
                this.listenToOnce(this.collection, 'sync', function () {
                    data.ids.forEach(function (id) {
                        if (this.collection.get(id)) {
                            this.checkRecord(id);
                        }
                    }, this);
                }, this);
                this.collection.fetch();
            }.bind(this));
        },

        massActionSetNotHeld: function () {
            this.notify('Please wait...');
            var data = {};
            data.ids = this.checkedList;
            $.ajax({
                url: this.collection.url + '/action/massSetNotHeld',
                type: 'POST',
                data: JSON.stringify(data)
            }).done(function (result) {
                this.notify(false);
                this.listenToOnce(this.collection, 'sync', function () {
                    data.ids.forEach(function (id) {
                        if (this.collection.get(id)) {
                            this.checkRecord(id);
                        }
                    }, this);
                }, this);
                this.collection.fetch();
            }.bind(this));
        },

    });

});
