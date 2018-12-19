<template>
  <v-container v-show="!loggedin">
    <v-layout 
      column
      justify-center
      align-center
      class="text-xs-center"
    >
      <v-flex 
        xs4
        class="grey lighten-4"
      >
        <v-container 
          class="text-xs-center"
        >
          <v-card 
            flat
            class="login"
            @keyup.enter="clickLogin"
          >
            <v-card-title primary-title>
              <div>
                <h4>Login</h4>
              </div>
            </v-card-title>
            <v-card-title>
              <div>
                <h6 
                  v-if="error"
                  class="error"
                >Error: {{ error }}</h6>
                <h6 v-else/>
              </div>
            </v-card-title>
            <v-form>
              <v-text-field
                v-model="uuid"
                prepend-icon="person"
                name="Username"
                label="Username/Email"
              />
              <v-text-field
                v-model="pass"
                prepend-icon="lock"
                name="Password"
                label="Password"
                type="password"
              />
              <v-card-actions>
                <v-btn
                  primary
                  large
                  block
                  @click="clickLogin"
                >
                  Login
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-container>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  // props: {
  //   loggedin: {
  //     type: Boolean, 
  //     required: true
  //   }
  // },
  data() {
    return {
      loggedin: false,
      id:  this.$store.state.id,
      uuid: this.$store.state.uuid,
      token: this.$store.state.token,
      pass: "",
      error: null,
    }
  },
  mounted() {
    if((!this.id == '') || (!this.uuid == '') || (!this.token == '')){
      $nuxt.$router.push('/')
    } else this.loggedin = false
    
  },
  methods: {
    clickLogin: function () {
        if(this.uuid != "" && this.pass != "") {
          let body = {
            user: this.uuid,
            pass: this.pass
          }
          this.$axios.$post("https://localhost/new-gb-resident/api/resident/login", body)
            .then(res => {
              // console.log(res)
              if(!res.token || res.error){
                this.error = res.error
              }else {
                // set localstorage
                localStorage.setItem('id', res.id)
                localStorage.setItem('token', res.token)
                localStorage.setItem('uuid', this.uuid)

                // update state wth localstorage
                this.$store.commit('login')
                // remove any previous errors
                this.error = null
                $nuxt.$router.push('/')
              }

            })
        } else {
            this.error = "user/email and password required"
        }
    },
    enterLogin: function() {
      this.clickLogin()
    }
  }
}
</script>


<style>
.login {
 padding: 3em;
}
.error {
  color: red;
}
</style>
