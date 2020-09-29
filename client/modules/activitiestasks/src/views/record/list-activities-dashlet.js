

Espo.define('activitiestasks:views/record/list-activities-dashlet', ['views/record/list-expanded', 'activitiestasks:views/meeting/record/list', 'activitiestasks:views/task/record/list'], function (Dep, MeetingList, TaskList) {

    return Dep.extend({

        actionSetHeld: function (data) {
            MeetingList.prototype.actionSetHeld.call(this, data);
        },

        actionSetNotHeld: function (data) {
            MeetingList.prototype.actionSetNotHeld.call(this, data);
        },

        actionSetCompleted: function (data) {
            TaskList.prototype.actionSetCompleted.call(this, data);
        }

    });

});
