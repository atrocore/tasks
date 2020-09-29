
Espo.define('activitiestasks:views/fields/ico', 'views/fields/base', function (Dep) {

    return Dep.extend({

        setup: function () {
            var tpl;

            var icoTpl;
            if (this.params.notRelationship) {
                icoTpl = '<span class="{iconClass} text-muted action icon" style="cursor: pointer" title="' + this.translate('View') + '" data-action="quickView" data-id="' + this.model.id + '" data-scope="' + this.model.name + '"></span>';
            } else {
                icoTpl = '<span class="{iconClass} text-muted action icon" style="cursor: pointer" title="' + this.translate('View') + '" data-action="quickView" data-id="' + this.model.id + '"></span>';
            }

            var iconClass = this.getMetadata().get(['clientDefs', this.model.name, 'iconClass']) || 'far fa-calendar-times';

            tpl = icoTpl.replace('{iconClass}', iconClass);


            this._template = tpl;
        }

    });

});
