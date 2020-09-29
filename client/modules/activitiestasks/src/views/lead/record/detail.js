


Espo.define('activitiestasks:views/lead/record/detail', 'views/record/detail', function (Dep) {

    return Dep.extend({

        selfAssignAction: true,

        sideView: 'activitiestasks:views/lead/record/detail-side',

        setup: function () {
            Dep.prototype.setup.call(this);
        },

        getSelfAssignAttributes: function () {
            if (this.model.get('status') === 'New') {
                if (~(this.getMetadata().get(['entityDefs', 'Lead', 'fields', 'status', 'options']) || []).indexOf('Assigned')) {
                    return {
                        'status': 'Assigned'
                    };
                }
            }
        }

    });
});


