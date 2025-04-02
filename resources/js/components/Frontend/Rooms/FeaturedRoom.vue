<template>
  <div class="container mt-5">
    <h2 class="section-title">Featured Rooms</h2>
    <div class="row">
      <div v-for="room in rooms" :key="room.id" class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded-lg h-100 position-relative">
          <!-- Use room.main_photo for the image source -->
          <img :src="room.main_photo" class="card-img-top rounded-top" alt="Room" />
          <div class="card-body p-3">
            <h5 class="card-title text-dark font-weight-bold">{{ room.room_name }}</h5>
            <p class="card-text text-muted" style="font-size: 0.875rem;">{{ room.description || 'No description available.' }}</p>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <p class="font-weight-bold text-golden" style="font-size: 1rem;">${{ room.price }}</p>
              <p class="font-weight-bold text-success" style="font-size: 0.9rem;">Max Adults: {{ room.max_adults }} | Max Children: {{ room.max_children }}</p>
            </div>
            <button @click="viewDetails(room.id)" class="btn btn-golden mt-3">View Details</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  computed: {
    // Fetch featured rooms from the Vuex store
    rooms() {
      return this.$store.getters['rooms/featuredRooms'];
    }
  },
  methods: {
    ...mapActions({
      fetchFeaturedRooms: 'rooms/fetchFeaturedRooms',  // Action to fetch featured rooms from Vuex
    }),

    // Navigate to the specific room details page
    viewDetails(roomId) {
      this.$router.push(`/room/${roomId}`);
    }
  },
  created() {
    // Fetch featured rooms when the component is created
    this.fetchFeaturedRooms();
  },
  mounted() {
    // Fetch featured rooms when the component is mounted
    this.fetchFeaturedRooms();
  }
};
</script>

<style scoped>
.section-title {
  font-size: 2rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 2.5rem;
  color: #333;
}

.card-img-top {
  height: 200px;
  object-fit: cover;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.card-body {
  padding: 1rem;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #333;
}

.card-text {
  font-size: 0.875rem;
  color: #666;
}

.text-muted {
  font-size: 0.8rem;
}

.text-primary {
  color: #0066cc;
}

.text-success {
  color: #28a745;
}

.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 10px;
}

.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.text-golden {
  color: #ffd700 !important;
  font-weight: bold;
}

.btn-outline-dark {
  border-color: #333;
  color: #333;
  transition: all 0.3s ease;
}

.btn-outline-dark:hover {
  background-color: #333;
  color: #fff;
}

.text-center.text-muted {
  font-size: 0.9rem;
  color: #999;
  margin-top: 1rem;
  font-style: italic;
}

@media (max-width: 767px) {
  .card-body {
    padding: 0.75rem;
  }

  .card-title {
    font-size: 1rem;
  }

  .text-golden {
    font-size: 1rem;
  }
}
</style>
