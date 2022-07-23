
let mxView = {
    methods:{
        setView(e,type){
            axios.post('changeView',{
                type:type,
                value:e.target.value
            }).then(()=>{
                this.$auth.fetch();
            })
        }
    }
}
export default mxView;
