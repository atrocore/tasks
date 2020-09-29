

Espo.define('activitiestasks:views/meeting/fields/acceptance-status', 'views/fields/enum', function (Dep) {

    return Dep.extend({

        searchTypeList: ['anyOf', 'noneOf']

    });

});
