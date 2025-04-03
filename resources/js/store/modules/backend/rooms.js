import axios from '../../../utils/axios.js';

const state = {
  rooms: [],
  room: {
    id: null,
    room_name: '',
    room_type: '',
    main_photo: null,
    max_adults: 0,
    max_children: 0,
    price: 0.0,
    description: '',
    featured: false,
    amenities: [],
    photoGallery: [],
  },
};

const mutations = {
  SET_ROOMS(state, rooms) {
    state.rooms = rooms;
  },
  SET_ROOM(state, room) {
    state.room = { ...room };
  },
  UPDATE_ROOM(state, updatedRoom) {
    const index = state.rooms.findIndex(room => room.id === updatedRoom.id);
    if (index !== -1) {
      state.rooms.splice(index, 1, { ...updatedRoom });
    }
  },
  TOGGLE_FEATURED(state, roomId) {
    const room = state.rooms.find(room => room.id === roomId);
    if (room) {
      room.featured = !room.featured;
    }
  },
};

const actions = {
  async fetchRooms({ commit }) {
    try {
      const response = await axios.get('/admin/rooms');
      commit('SET_ROOMS', response.data);
    } catch (error) {
      console.error('Error fetching rooms:', error);
    }
  },
  async fetchRoomById({ commit }, roomId) {
    try {
      const response = await axios.get(`/admin/rooms/${roomId}`);
      commit('SET_ROOM', response.data);
            console.log('fetching', response.data);

    } catch (error) {
      console.error('Error fetching room:', error);
    }
  },
  async addRoom({ dispatch }, formData) {
    try {
      await axios.post('/admin/rooms', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      dispatch('fetchRooms');
    } catch (error) {
      console.error('Error adding room:', error);
    }
  },
  async updateRoom({ dispatch }, { id, formData }) {
    try {
      await axios.post(`/admin/rooms/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        params: {
          _method: 'PUT',
        },
      });

      dispatch('fetchRooms');
    } catch (error) {
      console.error('Error updating room:', error);
    }
  },
  async deleteRoom({ dispatch }, roomId) {
    try {
      await axios.delete(`/admin/rooms/${roomId}`);
      dispatch('fetchRooms');
    } catch (error) {
      console.error('Error deleting room:', error);
    }
  },
  async toggleFeatured({ commit, dispatch }, roomId) {
    try {
      await axios.put(`/admin/rooms/${roomId}/toggle-featured`);
      commit('TOGGLE_FEATURED', roomId);
      dispatch('fetchRooms');
    } catch (error) {
      console.error('Error toggling featured room:', error);
    }
  },
};

const getters = {
  allRooms: (state) => state.rooms,
  currentRoom: (state) => state.room,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
