// import api from '../../../services/api'
import Swal from 'sweetalert2';
import router from '../../../router';

const actions = {
    // async createEmployees({ commit }, data) {
    //     console.log(data);
    //     return await api.createEmployees(data)
    //         .then(({ data }) => {
    //             console.log("create employee", data);
    //             commit("CREATE_EMPLOYEES", data);
    //             if (data == null) {
    //                 Swal.fire({
    //                     title: "Oops",
    //                     text: "Fail to register, something went wrong!",
    //                     icon: "error",
    //                 });
    //             }else {
    //                 Swal.fire({
    //                     title: "Success",
    //                     text: "Create Successfully!",
    //                     icon: "success",
    //                 });
    //             }
    //             // router.push('/manageEmployee');
    //         })
    //         .catch((err) => {
    //             console.log("create error", err);
    //             Swal.fire({
    //                 title: "Oops",
    //                 text: "Fail to register, something went wrong!",
    //                 icon: "error",
    //             });
    //         });
    // },

    async getDepartment({ commit }) {
        return await api.getDept()
        .then((res) => {
            console.log("All department", res.data);
            commit("SET_DEPARTMENTS", res.data)
        })
    }
}

export default actions;
