<template>
    <h1>{{ $t('bienvenue', {"expr": $auth.user().firstname + ' ' + $auth.user().lastname}) }}</h1>
</template>
<script>
export default {
    data() {
        return {
            researches: []
        }
    },
    mounted() {
        this.getResearchesOfUser();
    },
    methods: {
        getResearchesOfUser() {
            axios.get("researchesOfUser")
        }
    }
}
</script>
