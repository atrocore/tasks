

Espo.define('activitiestasks:views/record/row-actions/activities-dashlet', 'views/record/row-actions/view-and-edit', function (Dep) {

    return Dep.extend({

        getActionList: function () {
            var actionList = Dep.prototype.getActionList.call(this);

            var scope = this.model.name;

            actionList.forEach(function (item) {
                item.data = item.data || {};
                item.data.scope = this.model.name
            }, this);


            if (scope === 'Task') {
                if (this.options.acl.edit && !~['Completed', 'Canceled'].indexOf(this.model.get('status'))) {
                    actionList.push({
                        action: 'setCompleted',
                        label: 'Complete',
                        data: {
                            id: this.model.id
                        }
                    });
                }
            } else {
                if (this.options.acl.edit && !~['Held', 'Not Held'].indexOf(this.model.get('status'))) {
                    actionList.push({
                        action: 'setHeld',
                        label: 'Set Held',
                        data: {
                            id: this.model.id,
                            scope: this.model.name
                        }
                    });
                    actionList.push({
                        action: 'setNotHeld',
                        label: 'Set Not Held',
                        data: {
                            id: this.model.id,
                            scope: this.model.name
                        }
                    });
                }
            }
            if (this.options.acl.edit) {
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
