// import api from '../../../services/api'
import Swal from 'sweetalert2';
import router from '../../../router';
import axios from 'axios';

const actions = {
    async createEmployee({ commit }, data) {
        console.log(data);

        try {
            const response = await axios.post('/api/hr/HR/staff/create', data);
            console.log("create employee", response.data);

            commit("CREATE_EMPLOYEES", response.data);

            if (response.data.data == null) {
                Swal.fire({
                    title: "Oops",
                    text: "Fail to register, something went wrong!",
                    icon: "error",
                });
            } else {
                Swal.fire({
                    title: "Success",
                    text: "Create Successfully!",
                    icon: "success",
                });

                // Assuming `router` is properly imported and available
                router.push('/hr-sys/manageEmployee');

                // Reload after a delay
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }
        } catch (error) {
            console.log("create error", error);

            Swal.fire({
                title: "Oops",
                text: "Fail to register, something went wrong!",
                icon: "error",
            });
        }
    },

    // async createEmployees({ commit }, data) {
    //     console.log(data);
    //     return await axios.post('/api/hr/HR/staff/create', data)
    //     // api.createEmployees(data)
    //         .then(({ data }) => {
    //             console.log("create employee", data);
    //             commit("CREATE_EMPLOYEES", data);
    //             if (data.data == null) {
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
    //                 setTimeout(function() {
    //                     window.location.reload();
    //                 }, 2000);
    //                 router.push('/hr-sys/manageEmployee');
    //             }
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

    async getEmployees({ commit }) {
        return await api.getEmployees()
        .then((res) => {
            console.log("All employee", res.data);
            commit("SET_EMPLOYEES", res.data)
        })
    }
}

export default actions;
