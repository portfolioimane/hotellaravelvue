<template>
  <div class="room-detail">
    <!-- Check if room is available before rendering -->
    <div v-if="room" class="room-content">
      <div class="room-header">
        <!-- Room Main Image -->
        <div v-if="room.main_photo" class="room-image">
          <img :src="room.main_photo" :alt="room.room_name" class="img-fluid" />
        </div>

        <div class="room-info">
          <h1 class="room-title">{{ room.room_name }}</h1>
          <p class="room-description">{{ room.description }}</p>

          <div class="room-meta">
            <span class="room-amenities">
              <i class="fa fa-cogs"></i> 
              <span v-for="(amenity, index) in room.amenities" :key="index">{{ amenity.name }}{{ index < room.amenities.length - 1 ? ', ' : '' }}</span>
            </span>
            <span class="room-price">
              <i class="fa fa-tag"></i> {{ room.price ? `${room.price} MAD` : 'Price not available' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Photo Gallery -->
      <div class="photo-gallery">
        <h3>Photo Gallery</h3>
        <div class="gallery-images">
          <div v-for="(photo, index) in room.photoGallery" :key="index" class="gallery-item">
            <img :src="photo.photo_url" :alt="`Photo ${index + 1}`" class="gallery-img" />
          </div>
        </div>
      </div>

      <!-- Book Now Button -->
      <div class="book-button">
        <button @click="bookNow" class="btn-book">
          <i class="fa fa-check"></i> Book Now
        </button>
      </div>
    </div>

    <!-- Optionally show loading spinner if room is not yet loaded -->
    <div v-else class="loading">
      <i class="fa fa-spinner fa-spin"></i> Loading room details...
    </div>

            <Review v-if="roomLoaded" :roomId="Number(roomId)" />

  </div>
</template>

<script>

import Review from './Review.vue';

import { mapGetters, mapActions } from 'vuex';

export default {
      components: {
    Review
  },
  data() {
    return {
      roomLoaded: false, // Track if the room has finished loading
    };
  },
  computed: {
    ...mapGetters({
      room: 'rooms/selectedRoom', // The room fetched from the Vuex store
    }),
    
    roomId() {
      return this.$route.params.roomId;  // Capture the room ID from the URL
    },
  },
  created() {
    this.fetchRoomById(this.roomId);
  },
  methods: {
    ...mapActions({
      fetchRoomById: 'rooms/fetchRoomById',  // Action to fetch the room by ID
    }),

    // Redirect to booking page
    bookNow() {
      if (this.room && this.room.id) {
        this.$router.push({ name: 'checkout', params: { roomId: this.room.id } });
      }
    },
  },
  watch: {
    room(newRoom) {
      if (newRoom) {
        this.roomLoaded = true;
      }
    },
  },
};
</script>

<style scoped>
.room-detail {
  padding: 40px;
  max-width: 100%;
  margin: 50px auto;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.room-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.room-header {
  display: flex;
  flex-direction: row;
  align-items: center;
  width: 100%;
  margin-bottom: 30px;
}

.room-image {
  flex: 1;
  margin-right: 30px;
}

.room-image img {
  width: 100%;
  max-width: 400px;
  height: auto;
  border-radius: 8px;
}

.room-info {
  flex: 2;
}

.room-title {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.room-description {
  font-size: 1.2rem;
  color: #555;
  margin-bottom: 20px;
}

.room-meta {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.room-meta span {
  font-size: 1.1rem;
  color: #777;
}

.room-meta i {
  margin-right: 8px;
  color: #D0A047;
}

.photo-gallery {
  width: 100%;
  margin-top: 30px;
}

.gallery-images {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.gallery-item {
  width: 200px;
  height: 150px;
  overflow: hidden;
  border-radius: 8px;
}

.gallery-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

.book-button {
  margin-top: 30px;
}

.btn-book {
  background-color: #D0A047;
  color: white;
  padding: 15px 25px;
  border: none;
  border-radius: 50px;
  font-size: 1.1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
}

.btn-book i {
  margin-right: 8px;
}

.btn-book:hover
{
  background-color: #187bcd;
}

.loading {
  font-size: 1.5rem;
  color: #999;
  text-align: center;
}

.fa {
  font-size: 1.2rem;
}

.fa-spinner {
  font-size: 3rem;
  color: #D0A047;
  margin-right: 10px;
}
</style>
