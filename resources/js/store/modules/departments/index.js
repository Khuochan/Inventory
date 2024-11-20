import mutations from './mutations';
import getters from './getters';
import actions from './actions';

export var state = () => ({
    department: [],
    // department: {},
})

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
