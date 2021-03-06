import { ActionTree, MutationTree, GetterTree } from 'vuex'
import Cookies from 'js-cookie'
import axios from 'axios'
import { RootState } from '../types'
import { LoginApi } from '~/api/LoginApi'
import { LogoutApi } from '~/api/Logout'

export const state = (): RootState => ({
  token: null,
  user: null,
})

export const mutations: MutationTree<RootState> = {
  SET_TOKEN(state: RootState, token: string) {
    state.token = token
  },
  // SET_USER: (state: RootState, user: any) => (state.user = user),
  FETCH_USER_SUCCESS(state, user) {
    state.user = user
  },
  FETCH_USER_FAILURE(state) {
    state.token = null
  },
  LOGOUT(state) {
    state.user = null
    state.token = null
  },
}

export const getters: GetterTree<RootState, RootState> = {
  user: (state: RootState) => state.user,
  token: (state) => state.token,
  check: (state) => state.token !== null,
}

// actions
export const actions: ActionTree<RootState, RootState> = {
  saveToken({ commit }, { token, remember }) {
    commit('SET_TOKEN', token)

    Cookies.set('token', token, { expires: remember ? 365 : 0 })
  },

  fetchUser: async ({ commit }: any, data: any) => {
    try {
      const loginApi = new LoginApi()
      const res = await loginApi.post(data)
      return res
    } catch (e) {
      commit('FETCH_USER_FAILURE')
      throw e
    }
  },

  async fetchUserName({ commit }) {
    try {
      const { data } = await axios('/api/user')

      commit('FETCH_USER_SUCCESS', data)
    } catch (e) {
      Cookies.remove('token')

      commit('FETCH_USER_FAILURE')
    }
  },

  async logout({ commit }) {
    try {
      const logoutApi = new LogoutApi()
      await logoutApi.post()
    } catch (e) {
      //
    }

    Cookies.remove('token')

    commit('LOGOUT')
  },
}
