<template>
  <div>
    <h1>Our Rooms</h1>

    <div v-if="isLoading" class="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="!isLoading && !error" class="room-list">
      <div v-for="room in rooms" :key="room.id" class="room-card">
        <router-link :to="{ name: 'room', params: { roomId: room.id } }" class="room-link">
          <div class="room-image">
            <img :src="room.main_photo || 'default-room-image-url.jpg'" alt="Room Image" />
          </div>
          <div class="room-details">
            <h3 class="room-title">{{ room.room_name }}</h3>
            <p class="room-type">{{ room.room_type }}</p>
            <p class="room-description">{{ room.description }}</p>
            <p class="room-price">{{ room.price ? `${room.price} MAD` : 'Contact for Pricing' }}</p>
            <button class="view-button">View Details</button>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'RoomsList',
  computed: {
    ...mapGetters('rooms', {
      rooms: 'allRooms',  // Mapping `allRooms` from Vuex getter to `rooms` in the component
      isLoading: 'isLoading',  // Mapping `isLoading` from Vuex getter to `isLoading` in the component
      error: 'error',  // Mapping `error` from Vuex getter to `error` in the component
    }),
  },
  created() {
    this.fetchRooms();  // Dispatching the action to fetch rooms
  },
  methods: {
    ...mapActions('rooms', ['fetchRooms']),  // Mapping the `fetchRooms` action from Vuex
  },
};
</script>

<style scoped>
.error {
  color: red;
}

.loading {
  font-size: 1.5rem;
  color: #999;
}

.room-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  padding: 20px;
}

.room-card {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.room-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.room-link {
  display: block;
  text-decoration: none;
  color: inherit;
}

.room-image img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.room-details {
  padding: 20px;
}

.room-title {
  font-size: 1.2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.room-type {
  font-size: 1rem;
  color: #555;
  margin-bottom: 10px;
}

.room-description {
  font-size: 1rem;
  color: #555;
  margin-bottom: 15px;
}

.room-price {
  font-size: 1rem;
  font-weight: bold;
  color: #D0A047;
  margin-bottom: 10px;
}

.view-button {
  background-color: #D0A047;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.view-button:hover {
  background-color: #B6893A;
}
</style>
