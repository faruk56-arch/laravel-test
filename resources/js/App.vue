<template>
    <div class="app-layers">

        <div v-if="$auth.ready()" class="app-content">
            <router-view></router-view>
        </div>
        <div v-if="!$auth.ready()">
            <span class="loader"></span>
        </div>
    </div>
</template>

