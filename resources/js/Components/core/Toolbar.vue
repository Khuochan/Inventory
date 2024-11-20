<template>
      <v-app-bar
        dark
        app
        :elevation="2"
        >
        <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->
        <v-app-bar-nav-icon @click="toggleNavigationBar"></v-app-bar-nav-icon>

        <v-toolbar-title>Vuetify</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn
          target="_blank"
          text
        >
          <v-avatar color="indigo" size="30">
           <v-icon >
            mdi-account-circle
           </v-icon>
          </v-avatar>
          <span class="mr-2">Admin</span>

        </v-btn>
        
        <v-btn
          target="_blank"
          color="red"
        >
          <span class="mr-2">Sign out</span>
        </v-btn>
      </v-app-bar>
</template>

<script>
export default {
    data () {
        return {
            drawer: false,
        }
    },
    methods: {
        toggleNavigationBar() {
            const vm = this;

            vm.$emit('toggleNavigationBar');
        },
    }
}
</script>

<style>
  .toolbar-menu-item {
    padding-left: 5px;
  }
</style>
