<template>
    <v-app id="login" class="secondary">
      <div class="backgruond mx-auto"></div>
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
        <v-col cols="10" lg="4" class="mx-auto">
          <v-card class="pa-4">
            <div class="text-center">
              <h3 > Login in to Your Account </h3>
            </div>
            <v-form @submit.prevent="submitHandler" ref="form">
              <v-card-text>
                <v-text-field
                v-model="auth.name"
                  type="name"
                  label="User Name"
                  :rules="UserName"
                  prepend-inner-icon="mdi-account"
                  color="blue"
                  required
                  outlined
                  dense
                />
                <div class="error" v-if="errors.name">
                    {{ errors.name }}
                </div>
                <v-text-field
                      v-model="auth.password"
                      :rules="passwordRules"
                        label="Password"
                        :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="visible ? 'text' : 'password'"
                        prepend-inner-icon="mdi-key"
                        required
                        @click:append-inner="visible = !visible"
                        color="blue"
                        outlined
                        />
                        <div class="error" v-if="errors.password">
                            {{ errors.password }}
                        </div>
                                  <v-row class="mb-0">
                                  <v-col cols="12"  >
                                    <p class="forgot-password text-right">
                                        <router-link to="/forgot-password" class="text-decoration-none">Forgot password?</router-link>
                                    </p>
                                  </v-col>
                                </v-row>
              </v-card-text>
                <div class="text-center mt-5">
                        <v-btn :loading="loading" type="submit" color="blue" @click="login">
                            <span class="px-8 font-weight-bold">Login</span>
                        </v-btn>
                </div>
            </v-form>
          </v-card>
        </v-col>
      </v-main>

    </v-app>
  </template>

  <script>
import { mapActions, mapState } from 'vuex';
  export default {
    computed: {
        ...mapState('auth', {
            firstName: (state) => state.name,
        }),
    },
    name: 'Login',
    data: () => ({
      loading:false,
      visible:false,
      error: false,
      errors: [],
      list: [],
      UserName: [
        v => !!v || 'user name is required',
    ],
      password: '',
      passwordRules: [
        v => !!v || 'Password is required',
        v => (v && v.length >= 6) || 'Password must be 6  characters or more!',
      ],
      auth: {
          first_name: "",
          password: "",
        //   device_name: "Browser"
        },
        processing:false,
        value: String,
    }),
    methods:{
        ...mapActions({
            signIn: 'auth/login'
        }),

      hide_success: function (event) {
        // `event` is the native DOM event
        window.setInterval(() => {
            this.error = false;
        }, 10000)
      },

      async login() {
        this.processing = true
        // this.$router.push('/hr-sys/dashboard');
        await axios.get('/sanctum/csrf-cookie');
        await axios.post('/api/login', this.auth)
          .then(response => {
                this.$root.$emit("login", true);
                // this.signIn()
              localStorage.setItem('token', response.data.data.token);
              this.error = false;
              setTimeout(() => {
                this.$router.push('/portal/dashboard');
              }, 1000)
          })
          .catch(error => {
            this.error=true
            this.processing = false
          }).finally(()=>{
                this.processing = false
            });
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
