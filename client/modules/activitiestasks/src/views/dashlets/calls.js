

Espo.define('activitiestasks:views/dashlets/calls', 'views/dashlets/abstract/record-list', function (Dep) {

    return Dep.extend({

        name: 'Calls',

        scope: 'Call',

        listView: 'activitiestasks:views/call/record/list-expanded',

        rowActionsView: 'activitiestasks:views/call/record/row-actions/dashlet'

    });
});

