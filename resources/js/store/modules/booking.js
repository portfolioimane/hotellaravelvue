import axios from "../../utils/axios.js";

const state = {
  bookings: [],
  availableSlots: [],
  availableRooms: [],
  disabledDates: [], // Store unavailable dates globally
  totalBookings: 0,
};

const mutations = {
  setBookings(state, bookings) {
    state.bookings = bookings;
  },
  addBooking(state, booking) {
    state.bookings.push(booking);
  },
  SET_AVAILABLE_SLOTS(state, slots) {
    state.availableSlots = slots;
  },
  SET_AVAILABLE_ROOMS(state, rooms) {
    state.availableRooms = rooms;
  },
 SET_UNAVAILABLE_DATES(state, { dates }) {
    state.disabledDates = { dates }; // Store the array of Date objects under the 'dates' key
  },
};

const actions = {
  async fetchBookings({ commit }) {
    try {
      const response = await axios.get("/bookings");
      commit("setBookings", response.data);
    } catch (error) {
      console.error("Error fetching bookings:", error);
    }
  },

  async fetchAvailableRooms({ commit }, { check_in, check_out, adults, children }) {
    if (!check_in || !check_out || adults < 1 || children < 0) return;
    console.log("Fetching available rooms...");
    try {
      const response = await axios.get("/search-rooms", {
        params: { check_in, check_out, adults, children },
      });
      commit("SET_AVAILABLE_ROOMS", response.data);
    } catch (error) {
      console.error("Error fetching available rooms:", error.response?.data || error.message);
    }
  },

async fetchUnavailableDates({ commit }, roomId) {
  try {
    const response = await axios.get(`/bookings/unavailable-dates/${roomId}`);
    
    // Convert the date strings into Date objects
    const transformedDates = response.data.map(dateStr => {
      const [year, month, day] = dateStr.split("-").map(Number);
      return new Date(year, month - 1, day); // Month is 0-indexed, so subtract 1
    });

    // Commit the transformed dates to Vuex in the required structure for the date picker
    commit("SET_UNAVAILABLE_DATES", {
      dates: transformedDates
    });

    console.log('Unavailable dates:', transformedDates);

  } catch (error) {
    console.error("Error fetching unavailable dates:", error);
  }
},


  async fetchPaginatedBookings({ commit }, { page = 1, perPage = 5 }) {
    try {
      const response = await axios.get(`/mybookings?page=${page}&per_page=${perPage}`);
      commit("setBookings", response.data.bookings.data);
    } catch (error) {
      console.error("Error fetching bookings:", error);
      commit("setBookings", []);
    }
  },

  async submitBooking({ commit }, bookingData) {
    try {
      const response = await axios.post("/bookings/create", bookingData);
      commit("addBooking", response.data.booking);
      return response.data.booking;
    } catch (error) {
      console.error("Error submitting booking:", error);
      throw error;
    }
  },

  async fetchBookingById(_, bookingId) {
    try {
      const response = await axios.get(`/bookings/${bookingId}`);
      return response.data;
    } catch (error) {
      console.error("Error fetching booking by ID:", error);
      throw error;
    }
  },

  async createStripePayment(_, totalAmount) {
    try {
      const response = await axios.post("/bookings/create-stripe-payment", { total: totalAmount });
      return response.data;
    } catch (error) {
      console.error("Error creating Stripe payment:", error);
      throw error;
    }
  },

  async confirmPayPalPayment(_, paypalOrderId) {
    try {
      const response = await axios.post("/bookings/confirm-paypal-payment", { paypalOrderId });
      return response.data;
    } catch (error) {
      console.error("Error confirming PayPal payment:", error);
      throw error;
    }
  },
};

const getters = {
  allBookings: (state) => state.bookings,
  bookingCount: (state) => state.totalBookings,
  availableSlots: (state) => state.availableSlots,
  availableRooms: (state) => state.availableRooms,
  disabledDates: (state) => state.disabledDates, // Getter for unavailable dates
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
