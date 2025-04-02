import axios from '../../utils/axios.js';

const state = {
    rooms: [], // Array to hold room data
    selectedRoom: null, // Store the selected room data
    loading: false,
    error: null,
    featuredRooms: [], // New state property for featured rooms
};

const getters = {
    allRooms: (state) => state.rooms,
    selectedRoom: (state) => state.selectedRoom,
    isLoading: (state) => state.loading,
    error: (state) => state.error,
    featuredRooms: (state) => state.featuredRooms, // Getter for featured rooms
};

const actions = {
    // Action to fetch all rooms
    async fetchRooms({ commit }) {
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get('/rooms'); // Assuming your API endpoint is '/rooms'
            commit('setRooms', response.data); // Store fetched rooms
            console.log('rooms', response.data);
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },

    // Action to fetch featured rooms
    async fetchFeaturedRooms({ commit }) {
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get('/rooms/featured'); // Assuming your API endpoint is '/rooms/featured'
            commit('setFeaturedRooms', response.data); // Store fetched featured rooms
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },

    // Action to fetch a single room by ID
    async fetchRoomById({ commit }, roomId) {
        console.log('fetchingroombyDI');
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get(`/rooms/${roomId}`); // Fetch a single room by ID
            commit('setSelectedRoom', response.data); // Store the selected room
                        console.log('roomid', response.data);

        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },
};

const mutations = {
    setRooms(state, rooms) {
        state.rooms = rooms; // Set the fetched room data
    },
    setSelectedRoom(state, room) {
        state.selectedRoom = room; // Set the selected room data
    },
    setFeaturedRooms(state, featuredRooms) {
        state.featuredRooms = featuredRooms; // Set the fetched featured rooms
    },
    setLoading(state, loading) {
        state.loading = loading; // Set the loading state
    },
    setError(state, error) {
        state.error = error; // Set any errors encountered
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
