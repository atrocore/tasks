

Espo.define('activitiestasks:views/task/record/list-expanded', ['views/record/list-expanded', 'activitiestasks:views/task/record/list'], function (Dep, List) {

    return Dep.extend({

        rowActionsView: 'activitiestasks:views/task/record/row-actions/default',

        actionSetCompleted: function (data) {
            List.prototype.actionSetCompleted.call(this, data);
        },

    });

});
