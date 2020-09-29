

Espo.define('activitiestasks:views/task/record/list', 'views/record/list', function (Dep) {

    return Dep.extend({

        rowActionsView: 'activitiestasks:views/task/record/row-actions/default',

        actionSetCompleted: function (data) {
            var id = data.id;
            if (!id) {
                return;
            }
            var model = this.collection.get(id);
            if (!model) {
                return;
            }

            model.set('status', 'Completed');

            this.listenToOnce(model, 'sync', function () {
                this.notify(false);
                this.collection.fetch();
            }, this);

            this.notify('Saving...');
            model.save();
        }

    });

});
