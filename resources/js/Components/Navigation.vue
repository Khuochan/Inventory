<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" app class="mx-auto" width="350">
            <v-sheet class="" height="150" width>
                <v-img src="../assets/logo.png" width="100%" height="100%" />
            </v-sheet>
            <v-list
                v-for="navigator in navigation"
                :key="navigator.title"
                class="list"
            >
                <div v-if="navigator.group1">
                    <v-list-group
                        v-for="item in navigator.items"
                        :key="item.title"
                        v-model="item.active"
                        :prepend-icon="item.action"
                        no-action
                    >
                        <template v-slot:activator="{ props }">
                            <v-list-item v-bind="props">
                                <v-list-item-title>{{
                                    item.title
                                }}</v-list-item-title>
                            </v-list-item>
                        </template>
                        <v-list-group
                            v-for="(item, i) in item.items"
                            :key="i"
                            :value="item"
                            link
                        >
                            <template v-slot:activator="{ props }">
                                <v-list-item v-bind="props">
                                    <v-list-item-title>{{
                                        item.title
                                    }}</v-list-item-title>
                                </v-list-item>
                            </template>
                            <v-list-item
                                v-for="(nestedItem, j) in item.items"
                                :key="j"
                                :title="nestedItem.title"
                                :prepend-icon="nestedItem.icons"
                                :value="nestedItem.title"
                                :to="nestedItem.link"
                            ></v-list-item>
                        </v-list-group>
                    </v-list-group>
                </div>
                <div v-else-if="navigator.group">
                    <v-list-group
                        v-for="item in navigator.items"
                        :key="item.title"
                        v-model="item.active"
                        :prepend-icon="item.action"
                        no-action
                    >
                        <template v-slot:activator="{ props }">
                            <v-list-item v-bind="props">
                                <v-list-item-title>{{
                                    item.title
                                }}</v-list-item-title>
                            </v-list-item>
                        </template>
                        <v-list-item
                            v-for="(item, i) in item.items"
                            :key="i"
                            :value="item"
                            link
                        >
                            <template v-slot:prepend>
                                <v-icon :icon="item.icons"></v-icon>
                            </template>

                            <v-list-item-title>
                                <router-link
                                    :to="item.link"
                                    style="
                                        text-decoration: none;
                                        color: inherit;
                                    "
                                    >{{ item.title }}</router-link
                                >
                            </v-list-item-title>
                        </v-list-item>
                    </v-list-group>
                </div>
                <div v-else>
                    <v-list-item
                        v-for="item in navigator.items"
                        :key="item.title"
                        :value="title"
                        :title="item.title"
                        :prepend-icon="item.icons"
                        :to="item.link"
                    >
                    </v-list-item>
                </div>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar app color="#1E90FF" dark>
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <div class="d-flex align-center">
                <div>INVENTORY MANAGEMENT SYSTEM</div>
            </div>
            <v-spacer></v-spacer>
            <v-menu
                offset-y
                origin="center center"
                :nudge-bottom="10"
                transition="scale-transition"
            >
                <template v-slot:activator="{ props }">
                    <span>{{ user.username }}</span>
                    <v-btn icon lagre flate v-bind="props" :ripple="false">
                        <v-avatar size="30px" color="indigo">
                            <v-icon> mdi-account-circle </v-icon>
                        </v-avatar>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item>
                        <v-icon>mdi-account</v-icon>
                        <!-- <router-link
                :to="{ name: 'profile_page' }"
                class="blue--text pr-4 ml-2"
                style="text-decoration: none"
              >
                Profile</router-link
              > -->
                        Profile
                    </v-list-item>
                    <v-list-item>
                        <v-icon>mdi-logout</v-icon>
                        <span
                            @click="logout"
                            class="blue--text pr-4 ml-2"
                            style="text-decoration: none"
                            >Log Out</span
                        >
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <v-main>
            <router-view></router-view>
        </v-main>
    </v-app>
</template>
<script>
// import User from "../apis/User";
export default {
    name: "navigation",
    data: () => ({
        isLoggedIn: false,
        drawer: null,
        user: {},
        navigation: [
            {
                group: false,
                title: "Dashboard",
                items: [
                    {
                        title: "Dashboard",
                        link: "/inventory-sys/dashboard",
                        icons: "mdi-view-dashboard",
                    },
                ],
            },
            {
                group: true,
                title: "Users",
                items: [
                    {
                        title: "Users",
                        action: "mdi-account-group",
                        items: [
                                    {
                                        title: "List User  ",
                                        link: "/inventory-sys/users",
                                        icons: "mdi-account-multiple",
                                    },
                                    {
                                        title: "Add User  ",
                                        link: "/inventory-sys/adduser",
                                        icons: "mdi-account-plus",
                                    },
                                ],
                    },
                ],
            },

            {
                group: false,
                title: "Order",
                items: [
                    {
                        title: "Incoming Machine ",
                        link: "/inventory-sys/order",
                        icons: "mdi-text-box-check",
                    },
                ],
            },
            {
                group: true,
                title: "ATM",
                items: [
                    {
                        title: "Terminals",
                        action: "mdi-warehouse",
                        items: [
                            {
                                title: "List Terminal",
                                link: "/inventory-sys/atm",
                                icons: "mdi-home-group",
                            },
                            {
                                title: "New Terminal ",
                                link: "/inventory-sys/addnewatm",
                                icons: "mdi-home-plus",
                            },
                        ],
                    },
                ],
            },
            {
                group: false,
                title: "Report",
                items: [
                    {
                        title: "Report ",
                        link: "/inventory-sys/report",
                        icons: "mdi-calendar-export",
                    },
                ],
            },
            {
                group: false,
                title: "Spare parts",
                items: [
                    {
                        title: "Stock Spare Parts",
                        link: "/inventory-sys/spare_part",
                        icons: "mdi-toolbox",
                    },
                ],
            },
            {
                group1: true,
                items: [
                    {
                        title: "Setting ",
                        action: "mdi-cog",
                        items: [
                            {
                                title: "Banks Master",
                                action: "mdi-account-group",
                                items: [
                                    {
                                        title: "Banks",
                                        link: "/inventory-sys/bank",
                                        icons: "mdi-bank",
                                    },
                                    {
                                        title: " Bank Locations",
                                        link: "/inventory-sys/banklocation",
                                        icons: "mdi-map-marker",
                                    },
                                    {
                                        title: "Warehouses",
                                        link: "/inventory-sys/warehouse",
                                        icons: "mdi-store-marker",
                                    },
                                ],
                            },
                            {
                                title: "Engineers Master",
                                action: "mdi-account-group",
                                items: [
                                    {
                                        title: "Engineers",
                                        link: "/inventory-sys/engineer",
                                        icons: "mdi-account-multiple",
                                    },

                                ],
                            },
                            {
                                title: "Site Master",
                                action: "mdi-account-group",
                                items: [
                                    {
                                        title: "Sites",
                                        link: "/inventory-sys/site",
                                        icons: "mdi-account-multiple",
                                    },

                                ],
                            },
                            {
                                title: "Terminal Master",
                                items: [
                                    {
                                        title: "Types",
                                        link: "/inventory-sys/type",
                                        icons: "mdi-home-group",
                                    },
                                    {
                                        title: "Models",
                                        link: "/inventory-sys/model",
                                        icons: "mdi-home-plus",
                                    },
                                    {
                                        title: "Status",
                                        link: "/inventory-sys/status",
                                        icons: "mdi-home-plus",
                                    },
                                ],
                            },
                            {
                                title: "Category Master",
                                items: [
                                    {
                                        title: "Category",
                                        link: "/inventory-sys/category",
                                        icons: "mdi-home-group",
                                    },
                                ],
                            },
                        ],
                    },
                ],
            },
        ],
    }),
    created() {
        // User.auth()
        // .then((response) => {
        //   this.user = response.data;
        //   console.log(this.user);
        // })
        // .catch(error=>{
        //     localStorage.removeItem("token");
        //     this.$router.push({ name: "login" });
        // });
    },
    mounted() {
        // this.startTimer();
        // this.$root.$on("login", () => {
        //   this.isLoggedIn = true;
        // });
        // console.log(localStorage);
        // this.isLoggedIn = !!localStorage.getItem("token");
    },

    methods: {
        logout() {
            localStorage.removeItem("token");
            this.isLoggedIn = false;
            this.$router.push({ name: "mainPage" });
        },
        startTimer() {
            this.timer = setTimeout(() => {
                alert("Session expired. You will be logged out.");
                localStorage.removeItem("token");
                this.$router.push("/");
            }, 1800000); // 30 minutes in milliseconds
        },
    },
};
</script>
<style lang="scss" scoped>
.list {
    padding: 0px;
    margin: 0px;
    color: #1979e7;
}
.navbar {
    display: flex;
    align-items: center;
    width: 100%;
    justify-content: space-between;
    padding: 0px 30px;
}
</style>
