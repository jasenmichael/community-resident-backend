import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

const createStore = () => {
  return new Vuex.Store({
    strict: false,
    // strict: true,
    plugins: [createPersistedState()],
    state: () => ({
      id: null,
      uuid: null,
      token: null,
      directories: {}
    }),
    mutations: {
      login (state) {
        state.id = localStorage.getItem('id')
        state.uuid = localStorage.getItem('uuid')
        state.token = localStorage.getItem('token')
      },
      logout (state) {
        state.id = ''
        state.uuid = ''
        state.token = ''
      },
      loadFiles (state, payload) {
        let dir = Object.keys(payload)[0]
        state.directories[dir] = payload[Object.keys(payload)[0]]
      }
    }
  })
}

export default createStore