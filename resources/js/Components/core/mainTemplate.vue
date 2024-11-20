<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" app color="#8D99AF" class="rounded">
            <v-list nav  dense>
                    <v-list-item
                        @click="changeRoute('Dashboard', 1)"
                        prepend-icon="mdi-view-dashboard"
                        value="Dashboard"
                        style="font-size: 14px;"
                        :class="[{'active': selectedIndex === 1}, 'item-title' ]"
                    >
                    {{ ('Dashboard') }}
                    </v-list-item>
                    <span v-if="user.role_id != 2 && user.role_id != 5 && user.role_id != 6">
                    <v-list-group value="Employees">
                    <template v-slot:activator="{ props }">
                        <v-list-item
                        v-bind="props"
                        style="font-size: 14px;"
                        prepend-icon="mdi-account-multiple"
                        class="item-title"
                        >{{ ('Employees') }} </v-list-item>
                    </template>
                    <v-list-item
                            @click="changeRoute('AddEmployee', 2)"
                            value="AddEmployee"
                            style="font-size: 14px;"
                            :class="[{'active': selectedIndex === 2}, 'item-title' ]"
                    >{{ ('Add Employee') }}</v-list-item>
                    <v-list-item
                            @click="changeRoute('ManageEmployee', 3)"
                            value="ManageEmployee"
                            style="font-size: 14px;"
                            :class="[{'active': selectedIndex === 3}, 'item-title' ]"
                    >{{ ('Manage Employee') }}</v-list-item>
                    </v-list-group>
                    </span>
                    <v-list-group value="Terminals">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                            v-bind="props"
                            prepend-icon="mdi-warehouse"
                            class="item-title"
                            style="font-size: 14px;"
                            >{{ ('Terminals') }} </v-list-item>
                        </template>
                        <v-list-item
                            @click="changeRoute('allterminal_page', 13)"
                            value="allterminal_page"
                            style="font-size: 14px;"
                            :class="[{'active': selectedIndex === 13}, 'item-title' ]"
                        >{{ ('List Terminal') }}</v-list-item>
                            <v-list-item
                                    @click="changeRoute('addnewatm_page', 14)"
                                    value="addnewatm_page"
                                    style="font-size: 14px;"
                                    :class="[{'active': selectedIndex === 14}, 'item-title' ]"
                            >{{ ('New Terminal') }}</v-list-item>
                    </v-list-group>
                    <v-list-group value="Inventory">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                            v-bind="props"
                            style="font-size: 14px;"
                            prepend-icon="mdi-archive "
                            class="item-title"
                            >{{ ('Inventory') }} </v-list-item>
                        </template>
                        <span v-if="user.role_id != 2">
                            <v-list-item
                            @click="changeRoute('sparepart', 33)"
                                    value="sparepart"
                                     prepend-icon="mdi-clipboard-list"
                                    style="font-size: 14px;"
                                    :class="[{'active': selectedIndex === 33}, 'item-title' ]"
                            >{{ ('Spare Part') }}</v-list-item>
                            <v-list-item
                                @click="changeRoute('list_spare', 29)"
                                value="list_spare"
                                prepend-icon="mdi-archive-cog"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 29}, 'item-title' ]"
                            >
                            {{ ('Stocks') }}
                            </v-list-item>

                        </span>
                        <v-list-item
                        @click="changeRoute('request_spare', 30)"
                        value="request_spare"
                        style="font-size: 14px;"
                         prepend-icon="mdi-package-down"
                        :class="[{'active': selectedIndex === 30}, 'item-title' ]"
                    >
                    {{ ('Request Spare Part') }}
                    </v-list-item>
                    <v-list-item
                        @click="changeRoute('return_spare', 34)"
                        value="return_spare"
                        style="font-size: 14px;"
                        prepend-icon="mdi-package-up"
                        :class="[{'active': selectedIndex === 34}, 'item-title' ]"
                    >
                    {{ ('Retrun Spare Part') }}
                    </v-list-item>
                    <v-list-item
                        @click="changeRoute('job_sheet', 35)"
                        value="job_sheet"
                        style="font-size: 14px;"
                        prepend-icon="mdi-invoice-text-multiple"
                        :class="[{'active': selectedIndex === 35}, 'item-title' ]"
                    >
                    {{ ('Job Sheet') }}
                    </v-list-item>
                    <span v-if="user.role_id != 2">
                        <v-list-item
                        @click="changeRoute('stock_transfer', 36)"
                        value="stock_transfer"
                        style="font-size: 14px;"
                        prepend-icon="mdi-archive-refresh"
                        :class="[{'active': selectedIndex === 36}, 'item-title' ]"
                    >
                    {{ ('Stock Transfer') }}
                    </v-list-item>
                    </span>
                    </v-list-group>
                    <span v-if=" user.role_id == 1 || user.role_id == 4">
                        <v-list-group value="Setting">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                            v-bind="props"
                            style="font-size: 14px;"
                            prepend-icon="mdi-cog"
                            class="item-title"
                            >{{ ('Setting') }} </v-list-item>
                        </template>
                        <v-list-item
                                @click="changeRoute('Department', 7)"
                                value="Department"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 7}, 'item-title' ]"
                        >{{ ('Department') }}</v-list-item>
                        <v-list-item
                                @click="changeRoute('Position', 9)"
                                value="Position"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 9}, 'item-title' ]"
                        >{{ ('Position') }}</v-list-item>
                        <v-list-item
                                @click="changeRoute('MasterUsers', 11)"
                                value="MasterUsers"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 11}, 'item-title' ]"
                        >{{ ('Master Users') }}</v-list-item>
                        <v-list-group value="bank">
                            <template v-slot:activator="{ props }">
                            <v-list-item
                            v-bind="props"
                            class="item-title"
                            style="font-size: 14px;"
                            >{{ ('Banks Master') }} </v-list-item>
                        </template>
                        <v-list-item
                                @click="changeRoute('bank_page', 16)"
                                value="bank_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 16}, 'item-title' ]"
                        >{{ ('List Banks') }}</v-list-item>
                        <v-list-item
                                @click="changeRoute('banklocation_page', 17)"
                                value="banklocation_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 17}, 'item-title' ]"
                        >{{ ('Bank Location') }}</v-list-item>
                        <v-list-item
                                @click="changeRoute('warehouse_page', 23)"
                                value="warehouse_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 23}, 'item-title' ]"
                        >{{ ('Warehouses') }}</v-list-item>
                        </v-list-group>
                        <v-list-item
                                @click="changeRoute('engineer_page', 31)"
                                value="engineer_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 31}, 'item-title' ]"
                        >{{ ('Engineers Master') }}</v-list-item>
                        <v-list-item
                                @click="changeRoute('Site_page', 19)"
                                value="Site_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 19}, 'item-title' ]"
                        >{{ ('Site Master') }}</v-list-item>
                        <v-list-group value="terminal">
                            <template v-slot:activator="{ props }">
                            <v-list-item
                            v-bind="props"
                            style="font-size: 14px;"
                            class="item-title"
                            >{{ ('Terminal Master') }} </v-list-item>
                        </template>
                        <v-list-item
                                @click="changeRoute('Type_page', 16)"
                                value="Type_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 16}, 'item-title' ]"
                        >{{ ('Type') }}</v-list-item>
                        <v-list-item
                                @click="changeRoute('Model_page', 17)"
                                value="Model_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 17}, 'item-title' ]"
                        >{{ ('Model') }}</v-list-item>
                        <v-list-item
                                @click="changeRoute('status_page', 24)"
                                value="status_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 24}, 'item-title' ]"
                        >{{ ('Status') }}</v-list-item>
                        </v-list-group>
                        <v-list-item
                                @click="changeRoute('category_page', 24)"
                                value="category_page"
                                style="font-size: 14px;"
                                :class="[{'active': selectedIndex === 24}, 'item-title' ]"
                        >{{ ('Category') }}</v-list-item>
                    </v-list-group>
                </span>

            </v-list>
        </v-navigation-drawer>
        <v-app-bar
          app dark color="#1C2E6D"
        >
          <!-- <v-app-bar-nav-icon class="text-white" @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->

          <div class="d-flex align-center toolbar" @click.stop="drawer = !drawer">
                <v-img
                    width="150"
                    cover
                    src="../../../assets/BTI (2).png"
                 ></v-img>
          </div>
          <v-spacer></v-spacer>
        <v-menu offset-y transition="scale-transition">
            <template v-slot:activator="{ props }">
                <v-btn icon color="white" flat @click="notifications.map(x => x.isActive = false)">
                    <v-badge color="error" overlap v-bind="props">
                        <template v-slot:badge>{{ activeNotificationCount}}</template>
                        <v-icon>mdi-bell</v-icon>
                    </v-badge>
                </v-btn>
            </template>
            <v-card class="mx-auto" min-width="300">
                <v-toolbar
                    color="white"
                    dark
                    >
                    <v-toolbar-title class="text-blue font-weight-bold">Notifications</v-toolbar-title>
                </v-toolbar>
                <v-list two-line class="pa-0">
                    <template v-for="(item, index) in modifiedNotifications">
                        <v-divider v-if="item.isDivider" :key="index" inset></v-divider>
                            <v-list-item v-else :key="item.name" ripple @click="Notification">
                                <template v-slot:prepend>
                                    <v-avatar size="42px">
                                        <span v-if="item.image == null">
                                            <v-img src="../../../assets/man_avatar.png" />
                                        </span>
                                        <span v-else>
                                            <v-img :src="getImageUrl(item.image)" />
                                        </span>
                                    </v-avatar>
                                </template>
                                <v-list-item-content>
                                <v-list-item-title class="font-weight-bold">{{ item.last_name }} {{ item.first_name }}</v-list-item-title>
                                <v-list-item-subtitle class="font-weight-bold">{{ formatDate(item.date_request) }}</v-list-item-subtitle>
                                <v-list-item-text>{{ item.reason }}</v-list-item-text>
                                </v-list-item-content>
                            </v-list-item>
                    </template>
                </v-list>
            </v-card>
        </v-menu>
        <v-menu open-on-hover offset-y transition="scale-transition">
            <template v-slot:activator="{ props }">

                <v-btn icon large flat :ripple="false" v-bind="props">
                <v-avatar size="42px">
                    <v-img :src="displayedImage" />
                </v-avatar>
                </v-btn>
            </template>
            <v-list>
                <v-list-item color="primary">
                    <v-icon class="text-grey-darken-3">mdi-account-circle</v-icon>
                    <router-link
                    :to="'/portal/Profile/'+ user.id"
                    class="pr-4 ml-2 text-grey-darken-3"
                    style="text-decoration: none"
                    >
                    Profile</router-link
                    >
                </v-list-item>
                <v-list-item color="primary">
                    <v-icon class="text-grey-darken-3">mdi-exit-to-app</v-icon>
                    <span @click="logout" class="pr-4 ml-2 text-grey-darken-3" style="text-decoration: none"
                    >Log Out</span>
                </v-list-item>
            </v-list>
        </v-menu>
        <div class="mt-3 ml-2 mr-2 text-white text-capitalize">
            <p>{{ user.last_name }} {{ user.first_name }}</p>
        </div>
        </v-app-bar>

        <!-- page -->
      <v-main>
        <div class="content">
            <!-- <breadcrumbs /> -->
            <router-view></router-view>
        </div>
      </v-main>
    </v-app>
</template>

<script>
import axios from 'axios';
export default {
      name: "MainTemplate",
      data: () => ({
            selectedIndex: 1,
            drawer: null,
            loading: false,
            loaded: false,
            notifications: [],
            user: {},
      }),
      computed: {
        activeNotificationCount() {
             return this.notifications.filter(x => x.active).length;
         },

        modifiedNotifications() {
            const modifiedList = [];

            for (let i = 0; i < this.notifications.length; i++) {
                modifiedList.push(this.notifications[i]);

                if (i !== this.notifications.length - 1) {
                modifiedList.push({ isDivider: true });
                }
            }

            return modifiedList;
        },

        displayedImage() {
            return this.user.image ? this.getImageUrl(this.user.image) : "../../../assets/man_avatar.png";
        },
      },
      mounted() {
        this.startTimer()
        document.addEventListener('mousemove', this.resetTimer)
        document.addEventListener('keypress', this.resetTimer)
        console.log(localStorage)
        this.isLoggedIn = !!localStorage.getItem("token");
        const token = localStorage.getItem("token");

// Create an auth object with headers containing the token
        const auth = {
            headers: {
                Authorization: `Bearer ${token}`
            }
        };
        axios.get('/api/user', auth)
            .then(res => {
                this.user = res.data
                // console.log('user',this.user)
                if (this.user.role_id  == 2) {
                    this.$router.push('/hr-sys/applyLeave');
                }
                axios.get('/api/hr/HR/staff/notification/' + this.user.id)
                .then(res => {
                    this.notifications = res.data
                    console.log(this.notifications)
                })
            })
            .catch(error => {
                localStorage.removeItem("token");
                this.$router.push('/hr-sys/login-hr');
            })

            const count = this.activeNotificationCount;
            // console.log(count);
      },
      methods: {
        logout () {
            localStorage.removeItem("token");
            this.$router.push('/');
        },
        formatDate(time) {
            const formattedDate = moment(time).format('ddd, MMM D');
            return formattedDate;
        },
        startTimer() {
            this.timer = setTimeout(() => {
                alert('Session expired. You will be logged out.');
                localStorage.removeItem("token");
                this.$router.push('/hr-sys/login-hr');
            }, 1800000) // 30 minutes in milliseconds
        },
        resetTimer() {
            clearTimeout(this.timer)
            this.startTimer()
        },
        Profile() {
            this.$router.push({ name: 'Profile', params: {id: this.user.id} } );
        },
        changeRoute(routeName, selectedIndex) {
          const vm = this;

          vm.selectedIndex = selectedIndex;

          return vm.$router.push({ name: routeName });
        },
        onClick () {
            this.loading = true

            setTimeout(() => {
            this.loading = false
            this.loaded = true
            }, 2000)
        },

        Notification() {
            this.$router.push('/hr-sys/manangeLeave');
        },

        getImageUrl(attachment) {
                return `/storage/${attachment}`;
            },
      },
  };
  </script>
  <style>
  .navbar {
    display: flex;
    align-items: center;
    width: 100%;
    /* font-size: 8px; */

  }
  .toolbar-menu-item {
    padding-left: 5px;
  }
  .toolbar {
    font-weight: bold;
    font-size: 18px;
  }
  .text {
    padding-left: 15px;
    color: white;
    text-decoration:none;
  }
  .content {
    background-color: #E8EAF6;
    width: 100%;
    height: 100%;
  }
/*
  .v-list {
    font-size: 8px;
  } */

  </style>
