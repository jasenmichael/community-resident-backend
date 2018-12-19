<template>
  <div>
    <v-responsive
      class="banner"
    >
      <v-container fill-height>
        <v-layout
          column
          justify-center
          align-center>
          <v-flex
            xs12
            sm8
            md6
          >
            <h4 
              class="display-2 text-lg-center" 
            >
              Greenbriar Community Resident Portal</h4>
            <p class="subheading text-lg-center">Information, Forms and Documents.</p>
          </v-flex>
        </v-layout>
      </v-container>
    </v-responsive>
  </div>

</template>

<script>
// import VuexPersistence from 'vuex-persist'

export default {
  layout: 'index',
  components: {},
  data() {
    return {
      id: this.$store.state.id,
      uuid: this.$store.state.uuid,
      token: this.$store.state.token,
    }
  },
  computed: {},
  beforeCreate() {
    if(!this.$store.state.id || !this.$store.state.uuid || !this.$store.state.token){
      $nuxt.$router.push('/login')
    }
  },
  mounted() {
  }
}  
</script>

<style>
  .banner {
    background: lightgrey;
    border-radius: 1em;
    max-height: 40vh;
  }
</style>
