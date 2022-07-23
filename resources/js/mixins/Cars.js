let mxCars = {
    methods: {
        getCars(){
            let app = this;
            axios.get("modele",{
            })
                .then(response=>{
                    app.cars = response.data.data;
                })
        },
        getBrands(){
            let app = this;
            axios.get("brand",{
            })
                .then(response=>{
                    app.brands = response.data.data;
                })
        },
        getModels(){
            let app = this;
            axios.get("modele",{
            })
                .then(response=>{
                    app.models = response.data.data;
                })
        },
        getCategories(modelId){
            let app = this;
            axios.get("modele/" + modelId + "/category",{
            })
                .then(response=>{
                    app.categories = response.data.data;
                })
        },
        getParts(modelId,categoryId){
            let app = this;
            axios.get("part?modele_id="+modelId+"&category_id="+categoryId,{
            })
                .then(response=>{
                    app.parts = response.data.data;
                })
        }
    }
};
export default mxCars;
