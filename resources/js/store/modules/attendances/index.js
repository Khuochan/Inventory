import mutations from './mutations';
import getters from './getters';
import actions from './actions';

export var state = () => ({
    attendances: [],
    atten: {}
})

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
