const mutations =
{
    SET_EMPLOYEES(state, employees) {
        state.employee = employees;
    },

    CREATE_EMPLOYEES(state, data) {
        state.em = data;
    },
}

export default mutations;
