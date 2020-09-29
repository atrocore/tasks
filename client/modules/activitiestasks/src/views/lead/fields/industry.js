

Espo.define('activitiestasks:views/lead/fields/industry', 'views/fields/enum', function (Dep) {

    return Dep.extend({

        setup: function () {
            this.params.options = this.getMetadata().get('entityDefs.Account.fields.industry.options');
            this.params.translation = 'Account.options.industry';

            Dep.prototype.setup.call(this);
        }

    });

});
