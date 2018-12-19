<template>
  <v-app
    v-if="isLoggedIn ? isLoggedIn : $nuxt.$router.push('/login')"
    v-show="token"
    light
  >
    <v-navigation-drawer
      :clipped="clipped"
      v-model="drawer"
      fixed
      app
      width="400"
    >
      <v-list>
        <v-list
          two-line
          subheader
          class="hidden-md-and-up"
        >
          <v-subheader>Welcome {{ user.toUpperCase() }}</v-subheader>
          <v-list-tile
            @click="logoutModal = true"
          >
            <v-list-tile-content>
              <v-list-tile-sub-title>Logout</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
        <v-list-tile
          to="/"
          router
          exact
        >
          <v-list-tile-action>
            <v-icon>home</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Home</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-group
          prepend-icon="info"
        >
          <v-list-tile slot="activator">
            <v-list-tile-title>Documents, Files and Forms</v-list-tile-title>
          </v-list-tile>

          <v-list-group
            v-if="Object.keys(directories).length !== 0 && directories.constructor === Object"
            no-action
            sub-group
          >
            <v-list-tile slot="activator">
              <v-list-tile-title>Resident Archives</v-list-tile-title>
            </v-list-tile>

            <v-list-tile
              v-for="(item, i) in directories.files_archive.directories"
              :to="`/${directories.files_archive.path}/${item}`"

              :key="i"
            >

              <v-list-tile-action>
                <v-icon>folder</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title v-text="item.replace(/-/g, ' ').replace(/\b\w/g, x => x.toUpperCase())" />
              </v-list-tile-content>
            </v-list-tile>
            
          </v-list-group>

        </v-list-group>

      </v-list>
    </v-navigation-drawer>
    <v-toolbar
      :clipped-left="clipped"
      fixed
      app
    >
      <v-toolbar-side-icon @click="drawer = !drawer" />
      <v-toolbar-title>Greenbriar Resident Portal</v-toolbar-title>
      <v-spacer/>
      <v-toolbar-items class="hidden-sm-and-down">
        <v-btn
          disabled
          flat
        >Welcome {{ !user ? 'gbresident' : user }}</v-btn>
        <v-btn @click="logoutModal = true">Logout</v-btn>

        <v-dialog
          v-model="logoutModal"
          max-width="290"
        >
          <v-card>
            <v-card-title class="headline">Log Out</v-card-title>

            <v-card-text>
              Are you sure you want to log out?
            </v-card-text>

            <v-card-actions>
              <v-spacer/>

              <v-btn
                color="green darken-1"
                flat="flat"
                @click="logoutModal = false"
              >
                Cancel
              </v-btn>

              <v-btn
                color="green darken-1"
                flat="flat"
                @click="clickLogout()"
              >
                Logout
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

      </v-toolbar-items>
    </v-toolbar>
    <v-content>
      <v-container>
        <nuxt />
        <div 
          v-if="Object.keys(directories).length !== 0 && directories.constructor === Object"
        >
          <pre>{{ directoriesState }}</pre>
        </div>

      </v-container>
    </v-content>

    <v-footer>
      <h5>add footer component here</h5>  
    </v-footer>
  </v-app>
</template>

<script>

  export default {
    data() {
      return {
        token: this.$store.state.token,
        logoutModal: false,
        user: this.$store.state.uuid,
        clipped: true,
        drawer: false,
        fixed: false,
        directories: this.$store.state.directories
      }
    },
    computed: {
      directoriesState(){
        console.log('COMPUTED directoriesState')
        return this.$store.state.directories
      },
      isLoggedIn(){
        return this.$store.state.token
      }
    },
    beforeMount(){
      if(!this.user && !this.token){
        $nuxt.$router.push('/login')
      }
    },
    mounted() {
      if(this.user && this.token){
        this.loadFilesData().then(()=>{
            this.directories = this.$store.state.directories
        })
      }
    },
    methods: {
      clickLogout() {
        // clear local storage
        localStorage.clear()
        console.log('logged out, localstorage cleared')

        // clear state
        this.$store.commit('logout')
        this.error = null
        $nuxt.$router.push('/login')
      },
      async loadFilesData() {
        let rootFilesDir = 'files'
        
        // get the data for root rootFilesDir
        console.log('get root dir path - ', rootFilesDir)
        await this.getFilesAndFolders(rootFilesDir)

        // get directories and files in rootFilesDir
        let dirArr = this.$store.state.directories.files.directories
        for (let i = 0; i < dirArr.length; i++) {
            const e = dirArr[i];
            let path = rootFilesDir + "/" + e

            // get directories and files in sub directories
            let dirs = await this.getFilesAndFolders(path)
            console.log('directories found.. ', dirs.directories)
            let subDirArr = dirs
            for (let i = 0; i < subDirArr.directories.length; i++) {
              const e = subDirArr.directories[i];
              let subPath = subDirArr.path + "/" + e
              console.log('sub dir path...', subPath)
              let subDirs = await this.getFilesAndFolders(subPath)
              console.log('sub dirs found', subDirs)
              let subSubDirArr = subDirs
              for (let i = 0; i < subSubDirArr.directories.length; i++) {
                const e = subSubDirArr.directories[i];
                let subSubPath = subSubDirArr.path + "/" + e
                console.log('sub sub dir path...', subSubPath)
                let subSubDirs = await this.getFilesAndFolders(subSubPath)
                console.log('sub sub dirs found', subSubDirs)
              }
            }
        }
      this.directories = this.$store.state.directories  
      },
      async getFilesAndFolders(path){
        const apiUrl = `https://localhost/new-gb-resident/api`
        let url = apiUrl + "/" + path
  
        console.log('fetched files api--------', url)
        return await this.$axios.$post(url, {token: this.token})
          .then(res => {
            let slashToUnderscore = path.replace(/\//g, "_")
            // this.directories[hyphenPath] = res
            let payload = {[slashToUnderscore]: res}
            this.$store.commit('loadFiles', payload)
            return res
          })
      },
    }
  }
</script>
