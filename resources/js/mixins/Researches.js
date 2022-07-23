let mxResearches = {
    methods: {
        saveResearch(idUser, idCar, idTerm) {
            let app = this;
            // axios.get("term?parent="+this.$route.params.idCar,{
            axios.post("research", {
                user_id: idUser,
                modele_id: idCar,
                part_id: idTerm
            })
                .then(response => {

                })
        },
        searchModele(input) {
            return axios.get('/modele?name=' + input).then(res => res.data)
        },
        getResultValueModele(result) {
            return result.brand.name + ' - ' + result.name
        },
        selectCar(event) {
            this.carSelected = event.id
            this.modele = event
        },
        selectPart(event) {
            this.partSelected = event.id
            this.categorySelected = event.category_id
        },
        searchPart(input) {
            return axios.get(
                '/modele/' + this.carSelected +
                '/part?name=' + input
            ).then(res => Object.values(res.data));
        },
        getResultValuePart(result) {
            return this.translation('name',result) + ' - ' + this.translation('name', result.category)
        },
    }
};
export default mxResearches;
