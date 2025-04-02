<template>
  <div class="search-results">
    <h1 class="title">Available Rooms</h1>

    <!-- Show loader when fetching -->
    <div v-if="loading" class="loader">Loading rooms...</div>

    <!-- Show rooms if available -->
    <div v-else-if="availableRooms.length > 0" class="room-list">
      <div v-for="room in availableRooms" :key="room.id" class="room-card">
        <img :src="room.image" :alt="room.name" class="room-image" />

        <div class="room-details">
          <h2 class="room-title">{{ room.name }}</h2>
          <p class="room-description">{{ room.description }}</p>
          <p class="room-price">Price: {{ room.price }} MAD per night</p>

          <!-- Change button text from 'Book Now' to 'View Details' -->
          <router-link :to="`/room/${room.id}`" class="view-details-btn">View Details</router-link>
        </div>
      </div>
    </div>

    <!-- Show message if no rooms found -->
    <div v-else class="no-rooms">No rooms available for the selected dates.</div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  data() {
    return {
      loading: true,
    };
  },
  computed: {
    ...mapGetters("booking", ["availableRooms"]), // Get rooms from Vuex
  },
  mounted() {
    this.fetchRooms();
  },
  methods: {
    ...mapActions("booking", ["fetchAvailableRooms"]),

    async fetchRooms() {
      const { checkIn, checkOut, adults, children } = this.$route.query;
      try {
        this.loading = true;
        await this.fetchAvailableRooms({
          check_in: checkIn,
          check_out: checkOut,
          adults: adults,
          children: children,
        });
      } catch (error) {
        console.error("Error fetching available rooms:", error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.search-results {
  max-width: 900px;
  margin: 2rem auto;
  text-align: center;
}

.title {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.loader {
  font-size: 1.2rem;
  color: #555;
}

.room-list {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  justify-content: center;
}

.room-card {
  background: #fff;
  border-radius: 8px;
  padding: 1rem;
  width: 280px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.room-image {
  width: 100%;
  height: 160px;
  border-radius: 8px;
  object-fit: cover;
}

.room-details {
  text-align: left;
  margin-top: 1rem;
}

.room-title {
  font-size: 1.3rem;
  font-weight: bold;
}

.room-description {
  font-size: 0.9rem;
  color: #777;
  margin: 0.5rem 0;
}

.room-price {
  font-size: 1.1rem;
  font-weight: bold;
  color: #d0a047;
}

.view-details-btn {
  display: inline-block;
  margin-top: 0.5rem;
  padding: 0.7rem 1.5rem;
  background: #001c55;
  color: white;
  text-decoration: none;
  border-radius: 20px;
  transition: 0.3s;
}

.view-details-btn:hover {
  background: #b6893a;
}
.no-rooms {
  font-size: 1.2rem;
  color: #888;
  margin-top: 2rem;
}
</style>
