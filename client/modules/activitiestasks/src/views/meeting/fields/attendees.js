

Espo.define('activitiestasks:views/meeting/fields/attendees', 'views/fields/link-multiple-with-role', function (Dep) {

    return Dep.extend({

        columnName: 'status',

        roleFieldIsForeign: false,

        emptyRoleValue: 'None'

    });

});
