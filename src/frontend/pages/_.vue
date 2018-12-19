<template>
  <div>
    <h1>files yo</h1>
    <pre>{{ files }}</pre>
  </div>
</template>

<script>
export default {
  layout: 'index',
  components: {},
  data() {
    return {
      id: this.$store.state.id,
      uuid: this.$store.state.uuid,
      token: this.$store.state.token,
      files: this.$store.state.directories[$nuxt.$route.path.replace(/\//g, "_").replace("_", "")]
    }
  },
  beforeCreate() {
    if(!this.$store.state.id || !this.$store.state.uuid || !this.$store.state.token){
      $nuxt.$router.push('/login')
    }
  }
}
</script>
