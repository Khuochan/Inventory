import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from './modules/auth/index';
import employees from './modules/employees';
import departments from './modules/departments';
import attendances from './modules/attendances';
// import auth from '@/store/auth'

const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth,
        employees,
        departments,
        attendances
    }
})

export default store
