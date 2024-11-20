<template>
    <v-container fluid grid-list-xl>
      <v-row>
          <v-col cols="12" md="6" sm="6">
             <v-card>
              <div class="text-center text-grey-darken-3">
                  <v-avatar size="150" class="ma-6">
                    <v-img :src="getImageUrl(user.image)" />
                    <!-- <span v-if="user.image">
                        <v-img :src="getImageUrl(user.image)" />
                    </span> -->
                    <!-- <span v-else>
                        <v-img src="../../../assets/man_avatar.png" />
                    </span> -->
                  </v-avatar>
                  <h3>{{ user.last_name }} {{ user.first_name }}</h3>
              </div>
              <div class="ma-4 text-grey-darken-2">
                  <h4>Details</h4>
                  <v-divider></v-divider>
                  <v-list>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title><b>ID:</b> {{ user.em_id }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Gender:</b> {{ user.gender }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Email:</b> {{ user.email }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Phone:</b> {{ user.phone }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Address:</b> {{ user.address }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Department:</b> {{ user.dept_name }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Start Date:</b> {{ user.start_date }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Position:</b> <strong class="text-blue">{{ user.role_name }}</strong></v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list>
              </div>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog1"
                    persistent
                    width="400"
                    >
                    <template v-slot:activator="{ props }">
                        <v-btn  class="me-4" color="blue" variant="flat" v-bind="props">
                            Edit
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>Upload Profile</v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-file-input
                                v-model="image"
                                type="file"
                                accept="image/*"
                                label="File input"
                                @change="onFileChange"
                                ></v-file-input>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="grey"
                                variant="flat"
                                @click="dialog1 = false"
                            >
                                Close
                            </v-btn>
                            <v-btn
                                color="blue-darken-1"
                                variant="flat"
                                @click="submit"
                            >
                                Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                </v-card-actions>
             </v-card>
          </v-col>
          <v-col cols="12" md="6" sm="6">
              <v-card rounded="lg">
                <v-card-title class="text-center">
                        <span class="text-h5 text-indigo-accent-2">Change Password</span>
                </v-card-title>
                <v-container>
                    <v-form ref="form">
                        <label for="old_password">Old Password<a style="color: red;">*</a></label>
                        <v-text-field
                        v-model="pass.old_password"
                        variant="outlined"
                        :rules="[v => !!v || 'Old password is required']"
                        :type="visible ? 'text' : 'password'"
                        :append-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                        @click:append="visible = !visible"
                        required
                        ></v-text-field>

                        <label for="password">New Password<a style="color: red;">*</a></label>
                        <v-text-field
                        v-model="pass.password"
                        :rules="[v => !!v || 'Password is required']"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        @click:append="showConfirmPassword = !showConfirmPassword"
                        required
                        variant="outlined"
                        ></v-text-field>

                        <label for="con_password">Confirm Password<a style="color: red;">*</a></label>
                        <v-text-field
                            v-model="pass.con_password"
                            :rules="[v => !!v || 'Confirm password is required', v => pass.password === v || 'Passwords do not match']"
                            :type="showConfirmPassword ? 'text' : 'password'"
                            :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            @click:append="showConfirmPassword = !showConfirmPassword"
                            required
                            variant="outlined"
                        ></v-text-field>

                        <div class="mt-4">
                            <v-btn
                                class="me-4" color="blue"
                                @click="save"
                                >
                                save
                            </v-btn>
                            <v-btn @click="reset" color="grey">
                                clear
                            </v-btn>
                        </div>

                    </v-form>
                </v-container>
              </v-card>
          </v-col>
      </v-row>
    </v-container>
  </template>

  <script>
  import axios from 'axios';

  export default {
      data: () => ({
          visible: false,
          showConfirmPassword: false,
          dialog: false,
          dialog1: false,
          dialogDelete: false,
          headers: [
              { title: 'Leave Type', key: 'leave_name'},
              { title: 'Credit Leave', key: 'credit_leave'},
              { title: 'Actions', key: 'actions'}
          ],
          user: {},
          years: [
              {title: 'year_number'}
          ],
          leaves: [],
          id: '',
          em: [],
          pass: {
            old_password: '',
            password: '',
            con_password: ''
          },
          imageFile: null,
          image: null,
          staff: []
      }),

      watch: {
          dialog (val) {
            val || this.close()
          },
          dialogDelete (val) {
            val || this.closeDelete()
          },
      },
      created() {
        axios.get('http://localhost:8000/api/user', this.auth)
            .then(res => {
                this.user = res
                console.log(this.user)
            })
          this.id = this.$route.params.id;
          axios.get('http://localhost:8000/api/hr/HR/staff/getStaff/'+this.id)
          .then(res => {
              this.user = res.data[0];
              console.log('this user:', this.user);
          }).catch(err => {
              console.log("create error", err);
          })
      },
      methods: {
            reset () {
                this.$refs.form.reset()
            },
            save () {
                axios.post('http://localhost:8000/api/change-password', this.pass)
                .then(res => {
                   this.$swal({
                       title: "Success",
                       text: "Updated password Successfully!",
                       icon: "success",
                   });
                   setTimeout(function() {
                        alert('Logging out...');
                        localStorage.removeItem("token");
                        window.location.reload();
                        this.$nextTick(() => {
                            this.$router.push('/');
                        });
                    }, 3000);

                }).catch(err => {
                    console.log("create error", err);
                    this.$swal({
                        title: "Oops",
                        text: err.response.data.message,
                        icon: "error",
                    });
                })
             },

             onFileChange(event) {
                this.imageFile = event.target.files[0];
            },

            submit(){
                const formData = new FormData();
                console.log("user",this.id)
                formData.append('id', this.id);
                formData.append('image', this.imageFile);

                axios.post('http://localhost:8000/api/hr/HR/staff/uploadImg', formData)
                .then(res => {
                    this.staff = res.data
                    console.log(this.staff)
                    this.dialog1 = false
                    this.$swal({
                       title: "Success",
                       text: "Uploaded image Successfully!",
                       icon: "success",
                   });
                })
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            },

            getImageUrl(attachment) {
                return `/storage/${attachment}`;
            },


        }
    }

  </script>

  <style>

  </style>
