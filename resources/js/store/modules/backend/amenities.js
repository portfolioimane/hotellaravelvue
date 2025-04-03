import axios from '../../../utils/axios.js';

const state = {
  amenities: [], // Store the list of amenities
  amenity: {     // Store the details of a single amenity
    id: null,
    name: '',
    description: '',
    icon: null,
  },
};

const mutations = {
  SET_AMENITIES(state, amenities) {
    state.amenities = amenities;
  },
  SET_AMENITY(state, amenity) {
    state.amenity = { ...amenity };
  },
  UPDATE_AMENITY(state, updatedAmenity) {
    const index = state.amenities.findIndex(amenity => amenity.id === updatedAmenity.id);
    if (index !== -1) {
      state.amenities.splice(index, 1, { ...updatedAmenity });
    }
  },
  DELETE_AMENITY(state, amenityId) {
    state.amenities = state.amenities.filter(amenity => amenity.id !== amenityId);
  },
};

const actions = {
  async fetchAmenities({ commit }) {
    try {
      const response = await axios.get('/admin/amenities');
      commit('SET_AMENITIES', response.data);
    } catch (error) {
      console.error('Error fetching amenities:', error);
    }
  },
  
  async fetchAmenityById({ commit }, amenityId) {
    try {
      const response = await axios.get(`/admin/amenities/${amenityId}`);
      commit('SET_AMENITY', response.data);
    } catch (error) {
      console.error('Error fetching amenity:', error);
    }
  },
  
  async addAmenity({ dispatch }, formData) {
    try {
      await axios.post('/admin/amenities', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      dispatch('fetchAmenities');
    } catch (error) {
      console.error('Error adding amenity:', error);
    }
  },

  async updateAmenity({ commit }, { id, formData }) {
    try {
      const response = await axios.post(`/admin/amenities/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        params: {
          _method: 'PUT',
        },
      });
      commit('UPDATE_AMENITY', response.data);
    } catch (error) {
      console.error('Error updating amenity:', error);
    }
  },

  async deleteAmenity({ commit }, amenityId) {
    try {
      await axios.delete(`/admin/amenities/${amenityId}`);
      commit('DELETE_AMENITY', amenityId);
    } catch (error) {
      console.error('Error deleting amenity:', error);
    }
  },
};

const getters = {
  allAmenities: (state) => state.amenities,
  currentAmenity: (state) => state.amenity,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
