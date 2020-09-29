

Espo.define('activitiestasks:views/meeting/fields/users', 'activitiestasks:views/meeting/fields/attendees', function (Dep) {

    return Dep.extend({

        selectPrimaryFilterName: 'active',

        init: function () {
            this.assignmentPermission = this.getAcl().get('assignmentPermission');
            if (this.assignmentPermission == 'no') {
                this.readOnly = true;
            }
            Dep.prototype.init.call(this);
        },

        getSelectBoolFilterList: function () {
            if (this.assignmentPermission == 'team') {
                return ['onlyMyTeam'];
            }
        },

        getIconHtml: function (id) {
            var iconHtml = this.getHelper().getAvatarHtml(id, 'small', 14, 'avatar-link');
            if (iconHtml) iconHtml += ' ';
            return iconHtml;
        }

    });

});