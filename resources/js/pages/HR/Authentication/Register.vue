<template>
    <v-app id="register">
      <div class="backgruond"></div>
      <v-sheet>
        <v-alert
          v-model="error"
          variant="tonal"
          density="compact"
          title="Error"
          type="error"
          text="Invalid register, please try again">
        </v-alert>
      </v-sheet>
      <v-main class="d-flex justify-center align-center">

        <v-col cols="12" lg="5" class="mx-auto ">
          <v-card class="pr-10 pl-10  pt-25">
            <div class="text-center ">
              <v-img
              src="../../../assets/HR_logo.jpg"
              contain
              height="130"/>
              <h3> Create new Account</h3>
            </div>
            <v-form @submit.prevent="submitHandler" ref="form">
                             <v-row >
                             <v-col cols="8" sm="6">
                              <v-text-field
                                label="User Name"
                                v-model="user.name"
                                :rules="nameRules"
                                required
                                outlined
                                dense
                                color="blue"
                                autocomplete="false"
                                class="mt-5 mx-1"/>
                            </v-col>
                              <v-col  cols="8" md="6">
                                <v-text-field
                                v-model="user.password"
                                :rules="passwordRules"
                                :append-inner-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="passwordShow?'text':'password'"
                                label="Password"
                                required
                                dense
                                 outlined
                                color="blue"
                                class="mt-5 mx-1"
                                @click:append-inner=" passwordShow= !passwordShow"
                              /></v-col>
                            <v-col  cols="12" md="6">
                              <v-text-field
                                label="Phone "
                                v-model="user.phone"
                                required
                                outlined
                                dense
                                color="blue"
                                autocomplete="false"
                                class=" mx-1"
                              /></v-col>
                              <v-col  cols="12" md="6">
                              <v-text-field
                                label="Email "
                                v-model="user.email"
                                :rules="[rules.email]"
                                required
                                outlined
                                dense
                                color="blue"
                                autocomplete="false"
                                class=" mx-1"
                              /></v-col>
                              <v-col  cols="12" md="6">
                              <v-text-field
                                label="Address"
                                v-model="user.address"
                                required
                                outlined
                                dense
                                color="blue"
                                autocomplete="false"
                                class=" mx-1"
                              /></v-col>
                              <v-col  cols="12" md="6" >
                              <v-select
                                :items="items1"
                                label="Department "
                                v-model="user.department"
                                required
                                outlined
                                dense
                                color="blue"
                                class=" mx-1"
                              ></v-select>
                              </v-col >
                             </v-row>

                            <v-row class="mt-0">
                                <v-col cols="12" class="d-flex justify-center align-baseline">
                                  <p>Already have account? &nbsp;</p>
                                  <!-- <p class="white--text">d</p> -->

                                  <router-link :to="{name: 'Login'}" class="text-blue text-decoration-none">Log In</router-link>
                                </v-col>

                            </v-row>
                            <div class="text-center mt-5 mb-5">
                                <v-btn
                                  type="submit"
                                  color="blue"
                                  @click="register"
                                  class= "font-weight-bold"
                                  dark >Register</v-btn>
                              </div>
            </v-form>

                            </v-card>
                            </v-col>
      </v-main>
      </v-app>
  </template>

  <script>
import { mapActions } from 'vuex';

    export default {
      name:'Register',
      data() {
        return {
        passwordShow: false,
          success: false,
          error: false,
          user: {
            name:"",
            phone:"",
            email:"",
            department:"",
            password:"",
          },
          valid:true,
          nameRules: [
            v => !!v || 'Name is required',
            v => (v && v.length <= 10) || 'Name must be less than 10 characters',
            ],
            passwordRules: [
                v => !!v || 'Password is required',
                v => (v && v.length >= 6) || 'Password must be 6  characters or more!',
            ],
          rules: {
            email: v => !!(v || '').match(/@/) || 'Please enter a valid email',
            length: len => v => (v || '').length >= len || `Invalid character length, required ${len}`,
            password: v => !!(v || '').match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/) ||
            'Password must contain an upper case letter, a numeric character, and a special character',
            required: v => !!v || 'This field is required',
            phone: v => !!(v || '').match(v?.length >9 && /[0-9-]+/.test(v)) || 'Phone number needs to be at least 9 digits.',
          },
          email: undefined,
          password: undefined,
          phone: undefined,

          items2: ['I1','I2','I3','I4','I5','T1','T2',],
          items1: ['GIC','GCI','GCA','GIM','GEE','GAC','GRU','TC','AMS','GGG','GTR',],
          items3: ['M', 'F'],

          errors:[]
        };
      },
      methods: {
        ...mapActions({
            signIn: 'auth/login'
        }),

        hide_success: function (event) {
        // `event` is the native DOM event
        window.setInterval(() => {
            this.success = false;
            this.error = false;
        }, 10000)
       },
        async register() {
            console.log(this.user)
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('api/register', this.user)
            .then(() => {
                this.signIn()
              this.success=true
              setTimeout(()=> {
                this.$router.push({ name: "Login" });
              },2000)
            })
            .catch(error => {
              this.error=true
              if (error.response.status === 422) {
                this.errors = error.response.data.errors;
                console.log(error.response.data.errors);
              }
            });
       },
        validate() {
          if (this.$refs.form.validate()) {
            this.snackbar = true;
          }
        },
    submitHandler(){
        if (this.$refs.form.validate()){
            this.loading = true
          setTimeout(()=> {
            this.loading = false
          },1000)
        }
      }
      },
      mounted: function () {
      if(alert){
        this.hide_success();
      }
     }
    }
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
  </style>
