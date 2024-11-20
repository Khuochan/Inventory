import mutations from './mutations';
import getters from './getters';
import actions from './actions';

export var state = () => ({
    employee: [],
    em: {}
})

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
