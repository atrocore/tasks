

Espo.define('activitiestasks:views/dashlets/tasks', 'views/dashlets/abstract/record-list', function (Dep) {

    return Dep.extend({

        listView: 'activitiestasks:views/task/record/list-expanded',

        rowActionsView: 'activitiestasks:views/task/record/row-actions/dashlet'

    });
});

