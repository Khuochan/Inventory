<template>
    <div>
    
        <router-view></router-view>
    </div>
</template>

<script>

export default {
    name: 'app',
    data() {
        return {
        drawer: true
        }
    }
}
</script>

<style>
  .v-btn:hover:before{
    color: transparent !important;
  }
</style>
