<template>
    <v-app>
        <v-navigation-drawer :width="336" v-model="drawer" app>
            <v-sheet class="" height="150" width>
                <v-img src="../assets/logo.png" width="100%" height="100%" />
            </v-sheet>
            <v-list v-model:closed="close">
                <v-list-item prepend-icon="mdi-home" title="Home"></v-list-item>

                <v-list-group value="Users">
                    <template v-slot:activator="{ props }">
                        <v-list-item
                            v-bind="props"
                            prepend-icon="mdi-account-circle"
                            title="Users"
                        ></v-list-item>
                    </template>

                    <v-list-group value="Admin">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                                v-bind="props"
                                prepend-icon="mdi-account-circle"
                                title="Admin"
                            ></v-list-item>
                        </template>
                        <v-list-item
                            v-for="(item, i) in admins"
                            :key="i"
                            :value="item"
                            link
                        >
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

                    <v-list-group value="Actions">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                                v-bind="props"
                                title="Actions"
                            ></v-list-item>
                        </template>
                        <v-list-item
                            v-for="([title, icon], i) in cruds"
                            :key="i"
                            :value="title"
                            :title="title"
                            :prepend-icon="icon"
                            link
                        ></v-list-item>
                    </v-list-group>
                </v-list-group>
            </v-list>
        </v-navigation-drawer>
    </v-app>
</template>
<script>
export default {
    name: "navigation",
    data: () => ({
        drawer: null,
        close: ["Users"],
        admins: [
     
                            {
                                title: "Dashboard",
                                link: "/inventory-sys/dashboard",
                                icons: "mdi-view-dashboard",
                            },
                            {
                                title: "Add Machine ",
                                link: "/inventory-sys/addnewatm",
                                icons: "mdi-home-plus",
                            },
        ],
        cruds: [
            ["Create", "mdi-plus-outline"],
            ["Read", "mdi-file-outline"],
            ["Update", "mdi-update"],
            ["Delete", "mdi-delete"],
        ],
    }),
};
</script>
