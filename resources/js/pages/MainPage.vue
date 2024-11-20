<template>
    <v-layout>
        <v-navigation-drawer width="600" color="#001133" theme="dark" permanent>
            <div class="text-center pa-16 text-uppercase">
               <h1>
                    Welcome to BTI Portal
               </h1>
            </div>
            <div class="text-center">
                <v-btn color="#3E3D83" size="large"  class="text-white font-weight-bold">
                        <router-link :to="{ name: 'Login' }" class="text-white text-decoration-none">
                            Log in
                        </router-link>
                </v-btn>
            </div>

        </v-navigation-drawer>
        <v-main>
            <div class="d-flex flex-row-reverse ">
                <v-sheet max-width="200" class="ma-2 pa-2">
                    <v-img
                        :width="300"
                        cover
                        src="../../assets/BTI-Payments.png"
                    ></v-img>
                </v-sheet>
            </div>
            <v-sheet>
                <v-img
                        cover
                        src="../../assets/backgroud_mainpage.jpg"
                ></v-img>
            </v-sheet>
        </v-main>
    </v-layout>
</template>

<style>
h1 {
border-right: solid 3px #ffff;
  white-space: nowrap;
  overflow: hidden;
  font-family: 'Source Code Pro', monospace;
  font-size: 28px;
  color: #ffff;
}

h1 {
    animation: animated-text 4s steps(29,end) 1s 1 normal both,
    animated-cursor 600ms steps(29,end) infinite;
}

/* text animation */

@keyframes animated-text{
  from{width: 0;}
  to{width: 472px;}
}

/* cursor animations */

@keyframes animated-cursor{
  from{border-right-color: #ffff;}
  to{border-right-color: transparent;}
}
</style>
