

Espo.define('activitiestasks:views/lead/fields/acceptance-status', 'views/fields/enum-column', function (Dep) {

    return Dep.extend({

        searchTypeList: ['anyOf', 'noneOf'],

        setup: function () {
            this.params.options = this.getMetadata().get('entityDefs.Meeting.fields.acceptanceStatus.options');
            this.params.translation = 'Meeting.options.acceptanceStatus';

            Dep.prototype.setup.call(this);
        }

    });

});
