<template>
    <v-card class="max-auto widget-card" max-width="368" >
        <v-card-text class="py-2">
            <v-row no-gutters align="center">
                <v-col cols="6">
                    <v-card  :style="{ color: color }" size="60" variant="tonal" class="text-center mt-2" style="width: 90px; height: 90px;">
                        <v-icon size="70" :style="{ color: color }" class="mt-3">{{ icon }}</v-icon>
                    </v-card>
                </v-col>
                <v-col class="text-h2 text-right" cols="6" style="width: 140px;">{{ title }}</v-col>
            </v-row>
        </v-card-text>
        <v-card-text class="text-right">{{ supTitle }}</v-card-text>
    </v-card>
</template>

<script>
  export default {
    props: {
      supTitle: {
        type: String,
        required: false
      },
      subTitle: {
        type: String,
        required: false
      },
      title: {
        type: String,
        required: false
      },
      icon: {
        type: String,
        required: false
      },
      color: {
        type: String,
        required: false
      }
    },

    data() {
      return {
      }
    }
  }
</script>

<style>
    .widget-card {
      border-radius: 5%;
      box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.21);
      background-color: transparent;
    }
</style>
