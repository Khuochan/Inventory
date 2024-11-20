const mutations =
{
    SET_EMPLOYEES(state, employees) {
        state.atten = employees;
    },

    CREATE_ATTENDANCE(state, data) {
        state.atten = data;
    },
}

export default mutations;
