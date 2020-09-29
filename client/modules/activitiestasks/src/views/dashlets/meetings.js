

Espo.define('activitiestasks:views/dashlets/meetings', 'views/dashlets/abstract/record-list', function (Dep) {

    return Dep.extend({

        name: 'Meetings',

        scope: 'Meeting',

        listView: 'activitiestasks:views/meeting/record/list-expanded',

        rowActionsView: 'activitiestasks:views/meeting/record/row-actions/dashlet'

    });
});

