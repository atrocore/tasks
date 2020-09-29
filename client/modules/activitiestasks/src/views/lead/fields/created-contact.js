

Espo.define('activitiestasks:views/lead/fields/created-contact', 'views/fields/link', function (Dep) {

    return Dep.extend({

        getSelectFilters: function () {
            if (this.model.get('createdAccountId')) {
                return {
                    'account': {
                        type: 'equals',
                        field: 'accountId',
                        value: this.model.get('createdAccountId'),
                        valueName: this.model.get('createdAccountName')
                    }
                };
            }
        },
    });

});
