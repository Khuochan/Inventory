<template>
    <v-app id="login" class="secondary">
      <div class="backgruond mx-auto"></div>
      <div>
           <v-img
              src="../../../assets/bti.jpg"
              :width="200"/>
      </div>
      <v-sheet>
        <v-alert
          v-model="error"
          variant="tonal"
          density="compact"
          title="Error"
          type="error"
          text="Invalid login, please try again">
        </v-alert>
      </v-sheet>
      <v-main class="d-flex justify-center align-center">
        <!-- <p class="text-red" v-if="error">{{ error }}</p> -->
        <v-col cols="10" lg="4" class="mx-auto">
          <v-card class="pa-4">
            <div class="text-center">
              <!-- <v-img
              src="../../../assets/HR_logo.jpg"
              class="my-3"
              contain
              height="150"/> -->
              <h3 class="mb-5"> Forgot Your Password?</h3>
              <h7>Enter the email address associated with you account.</h7>
            </div>
            <v-form @submit.prevent="submitHandler" ref="form">
              <v-card-text>
                <v-text-field
                  v-model="email"
                  type="email"
                  :rules="[rules.email]"
                  label="Enter Email Address"
                  color="blue"
                  required
                  outlined
                  dense
                />
                <div class="error" v-if="errors.email">
                    {{ errors.email }}
                </div>
              </v-card-text>
              <div class="mt-5">
                <router-link :to="{name: 'Login'}">
                    <v-btn color="red" class="mr-4">
                        <span class="px-8 font-weight-bold">Cancel</span>
                    </v-btn>
                </router-link>
                <v-btn :loading="loading" type="submit" color="blue" @click="submit">
                    <span class="px-8 font-weight-bold">Submit</span>
                </v-btn>
              </div>
            </v-form>
          </v-card>
        </v-col>
      </v-main>

    </v-app>
  </template>

  <script>
  export default {
    name: 'ForgotPass',
    data: () => ({
      loading:false,
      visible:false,
      error: false,
      errors: [],
      list: [],
      rules: {
            email: v => !!(v || '').match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) || 'Please enter a valid email',
      },
        processing:false,
        value: String,
    }),
    methods:{
      hide_success: function (event) {
        // `event` is the native DOM event
        window.setInterval(() => {
            this.error = false;
        }, 10000)
      },
      submitHandler(){
        if (this.$refs.form.validate()){
            this.loading = true
          setTimeout(()=> {
            this.loading = false
          },1000)
        }
      },
    },
    mounted: function () {
      if(alert){
        this.hide_success();
      }
    //   this.test();
     }
  };
  </script>
  <style>
    .backgruond{
      background-image: url(../../../assets/background.jpg) !important;
      height: 60%;
      width: 100%;
      display: block;
      position: absolute;
      top: 0;
      background-size: cover;
    }
    ::v-deep .v-alert {
    width: 300px;
    margin-top: 20px;
    position: relative;
    right: 0%;
    z-index: 1;

  }
  </style>
