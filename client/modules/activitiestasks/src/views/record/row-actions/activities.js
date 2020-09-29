

Espo.define('activitiestasks:views/record/row-actions/activities', 'views/record/row-actions/relationship', function (Dep) {

    return Dep.extend({

        getActionList: function () {
            var list = [{
                action: 'quickView',
                label: 'View',
                data: {
                    id: this.model.id
                },
                link: '#' + this.model.name + '/view/' + this.model.id
            }];
            if (this.options.acl.edit) {
                list.push({
                    action: 'quickEdit',
                    label: 'Edit',
                    data: {
                        id: this.model.id
                    },
                    link: '#' + this.model.name + '/edit/' + this.model.id
                });
                if (this.model.name == 'Meeting' || this.model.name == 'Call') {
                    list.push({
                        action: 'setHeld',
                        html: this.translate('Set Held', 'labels', 'Meeting'),
                        data: {
                            id: this.model.id
                        }
                    });
                    list.push({
                        action: 'setNotHeld',
                        html: this.translate('Set Not Held', 'labels', 'Meeting'),
                        data: {
                            id: this.model.id
                        }
                    });
                }
            }
            if (this.options.acl.delete) {
                list.push({
                    action: 'removeRelated',
                    label: 'Remove',
                    data: {
                        id: this.model.id
                    }
                });
            }
            return list;
        }

    });

});

