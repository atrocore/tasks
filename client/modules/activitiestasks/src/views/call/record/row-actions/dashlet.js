

Espo.define('activitiestasks:views/call/record/row-actions/dashlet', ['views/record/row-actions/view-and-edit'], function (Dep) {

    return Dep.extend({

        getActionList: function () {
            var actionList = Dep.prototype.getActionList.call(this);

            if (this.options.acl.edit && !~['Held', 'Not Held'].indexOf(this.model.get('status'))) {
                actionList.push({
                    action: 'setHeld',
                    label: 'Set Held',
                    data: {
                        id: this.model.id
                    }
                });
                actionList.push({
                    action: 'setNotHeld',
                    label: 'Set Not Held',
                    data: {
                        id: this.model.id
                    }
                });
            }
            if (this.options.acl.delete) {
                actionList.push({
                    action: 'quickRemove',
                    label: 'Remove',
                    data: {
                        id: this.model.id,
                        scope: this.model.name
                    }
                });
            }

            return actionList;
        }
    });

});
