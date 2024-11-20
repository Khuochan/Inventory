<template>
        <v-tabs v-model="tab" align-tabs="title">
          <v-tab
            v-for="item in items"
            :key="item.value"
            :text="item.text"
            :value="item.value"
            @click="navigate(item.route)"
          >
            {{ item.text }}
          </v-tab>
        </v-tabs>
        <v-window v-model="tab" class="mt-4">
            <v-window-item v-for="item in items" :key="item.value" :value="item.value">
                <component :is="currentComponent" />
            </v-window-item>
          </v-window>
</template>
<script>
import HR from '../pages/HR/Dashboard.vue'
import Inventory from '../pages/Inventory/Dashboard.vue'
export default {
    name: 'dashbord',
    components: {
        HR,
        Inventory,
    },
  data: () => ({
    terminals: [],
    tab: '',
    items: [
        { text: 'HR', value: 'hr', route: '/portal/hr-dashboard' },
        { text: 'Inventory', value: 'inventory', route: '/portal/inventory-dashboard' },
      ]


  }),
  methods: {
    navigate(route) {
      this.$router.push(route); this.$router.push(route).catch(err => {
        if (err.name !== 'NavigationDuplicated') {
          throw err;
      }
    });
  },

  },
  created() {
    this.navigate('/portal/hr-dashboard'); // Navigate to the default route on creation
  },
  computed: {
    currentComponent() {
      switch (this.tab) {
        case 'hr':
          return HR; // Render HR component for 'hr' tab
        case 'inventory':
          return Inventory; // Render Inventory component for 'inventory' tab
        // Add more cases for additional tabs
        default:
          return HR; // Default to HR component
      }
    }
  },
}
</script>

<style scoped>
/* .pieChart {
    height: 500px;
    margin-top: 20px;
    width: 30%;
} */

.content {
    background-color: #E8EAF6;
    width: 100%;
    height: 100%;
  }
</style>

