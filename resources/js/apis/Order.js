import Api from "./APi";
export default {
    list(){
        return Api().get("/order/all");
    },
    create(data){
        return Api().post("/order/create", data);
    },
    update(id, data){
        return Api().put(`/order/update/${id}`, data);
    },
    delete(id){
        return Api().delete(`/order/delete/${id}`);
    },
    show(id){
        return Api().get(`/order/ownorder/${id}`);
    },
    getTotal(){
        return Api().get('/order/total');
    },
    deleteOrder(id){
        return Api().delete(`/order/deleteorder/${id}`);
    }


}
