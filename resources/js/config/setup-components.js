import Navigation from "../Components/core/NavigationDrawer.vue";
import Breadcrumbs from "../Components/core/Breadcrumbs.vue";
import Toolbar from '../components/core/Toolbar.vue';
import MainTemplate from '../Components/core/mainTemplate.vue'

import Widget from '../Components/Widget.vue';
import DataTable from '../Components/DataTable.vue';
import Table from '../Components/Table.vue';

import HRmaintemplate from '../Components/HR/HRmainTemplate.vue';

function setupComponents(Vue){
    Vue.component('navigation', Navigation);
    Vue.component('breadcrumbs', Breadcrumbs);
    Vue.component('toolbar', Toolbar);
    Vue.component('maintemplate', MainTemplate);
    Vue.component('hr-maintemplate', HRmaintemplate)

    Vue.component('widget', Widget);
    Vue.component('data-table', DataTable);
    Vue.component('s-table', Table);
}

export {
    setupComponents
}
