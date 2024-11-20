<template>
    <v-navigation-drawer
    v-model="drawer"
    fixed app>
    <v-toolbar flat dark class="toolbar">
      <router-link :to="{ name: 'Dashboard' }">
        <!-- <img src="../../assets/HR_logo.png" width="70px"> -->
      </router-link>
      <router-link :to="{ name: 'Dashboard' }" class="text">
         HR Management
      </router-link>
    </v-toolbar>

    <v-divider></v-divider>

    <v-list nav active-color="primary">
            <v-list-item
                @click="changeRoute('Dashboard', 1)"
                prepend-icon="mdi-view-dashboard"
                value="dashbord"
                :class="[{'active': selectedIndex === 1}, 'item-title' ]"
            >
            {{ ('Dashboard') }}
            </v-list-item>

            <v-list-group value="Employees">
              <template v-slot:activator="{ props }">
                <v-list-item
                  v-bind="props"
                  prepend-icon="mdi-account-multiple"
                  class="item-title"
                >{{ ('Employee') }} </v-list-item>
              </template>
              <v-list-item
                    @click="changeRoute('AddEmployee', 2)"
                    value="addEmployee"
                    :class="[{'active': selectedIndex === 2}, 'item-title' ]"
                >{{ ('Add Employee') }}</v-list-item>
            </v-list-group>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    export default {
      data () {
        return {
            selectedIndex: 1,
            drawer: true,
        }
      },

      methods: {
        changeRoute(routeName, selectedIndex) {
          const vm = this;

          vm.selectedIndex = selectedIndex;

          return vm.$router.push({ name: routeName });
        }
      }
    }
</script>

<style>
  .toolbar {
    font-weight: bold;
    font-size: 18px;
  }

  .toolbar .text {
    padding-left: 15px;
    /* color: white; */
    text-decoration:none;
  }

  .item-title {
    font-size: 17px;
    font-weight: 500;
  }
  .item-sub-title {
    font-size: 15px;
    font-weight: 500;
  }

  .active {
    font-weight: bold;
  }
</style>
