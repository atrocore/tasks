

Espo.define('activitiestasks:views/call/record/list-expanded', ['views/record/list-expanded', 'activitiestasks:views/call/record/list'], function (Dep, List) {

    return Dep.extend({

        actionSetHeld: function (data) {
            List.prototype.actionSetHeld.call(this, data);
        },

        actionSetNotHeld: function (data) {
            List.prototype.actionSetNotHeld.call(this, data);
        },

    });

});
