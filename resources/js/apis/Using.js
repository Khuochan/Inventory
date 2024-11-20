import Api from "./APi";
export default {
    list(){
        return Api().get("/using/all");
    },
    show(id){
        return Api().get(`/using/getid/${id}`);
    },
    create(data){
        return Api().post("/using/create", data);
    },
    update(id, data){
        return Api().put(`/using/update/${id}`, data);
    },
    delete(id){
        return Api().delete(`/using/delete/${id}`);
    },
    Showdata(data){
        return Api().post("/using/datatable", data);
    },
    getActive(){
        return Api().get("/using/active");
    },
    getNonactive(){
        return Api().get("/using/nonactive");
    },
    getTotal(){
        return Api().get("/using/total");
    },
    getType(){
        return Api().get("/using/type");
    },
    getdata(data){
        return Api().post("/using/fiterdata", data);
    },
    getData(data){
        return Api().post("/using/datatable", data);
    },
    getWarranty(id){
        return Api().get(`/using/warranty/${id}`);
    },
    getReport(){

        return Api().get("/using/report");
    },
    getExport(){
        return Api().get("/using/export");
    },
    getImport(data){
        return Api().post("/using/import", data);
    }


}
