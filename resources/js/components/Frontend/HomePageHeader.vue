<template>
  <div v-if="header" class="hero-section">
    <div class="hero-bg" :style="{ backgroundImage: `url(${header.image})` }">
      <div class="hero-overlay"></div>

      <div class="hero-content">
        <h1 class="hero-title">{{ header.title }}</h1>
        <p class="hero-subtitle">{{ header.subtitle }}</p>

        <!-- Search Form -->
        <div class="search-form">
          <vue3-datepicker 
            v-model="search.checkIn" 
            placeholder="Check-in Date" 
            :enable-time-picker="false"
            :min-date="today"
            @update:model-value="updateCheckOut"
          />
          <vue3-datepicker 
            v-model="search.checkOut" 
            placeholder="Check-out Date" 
            :enable-time-picker="false"
            :min-date="checkInOrToday"
          />
          <select v-model="search.adults">
            <option v-for="n in 10" :key="n" :value="n">{{ n }} Adult{{ n > 1 ? 's' : '' }}</option>
          </select>
          <select v-model="search.children">
            <option v-for="n in 10" :key="n" :value="n">{{ n }} Child{{ n > 1 ? 'ren' : '' }}</option>
          </select>
          <button class="search-btn" @click="handleSearch">Search</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Vue3Datepicker from "vue3-datepicker";

export default {
  components: {
    Vue3Datepicker,
  },
  data() {
    return {
      search: {
        checkIn: null,
        checkOut: null,
        adults: 1,
        children: 0,
      },
      today: new Date(new Date().setHours(0, 0, 0, 0)), // Start at midnight
    };
  },
  computed: {
    ...mapGetters("backendHomePageHeader", ["getHeader"]),
    header() {
      return this.getHeader;
    },
    checkInOrToday() {
      return this.search.checkIn ? new Date(this.search.checkIn) : this.today;
    },
  },
  mounted() {
    this.fetchHeaderFront();
  },
  methods: {
    ...mapActions("booking", ["fetchAvailableRooms"]), // Fetch available rooms

    async fetchHeaderFront() {
      try {
        await this.$store.dispatch("backendHomePageHeader/fetchHeaderFront");
      } catch (error) {
        console.error("Error fetching homepage header:", error);
      }
    },

    updateCheckOut() {
      if (this.search.checkOut && this.search.checkOut <= this.search.checkIn) {
        this.search.checkOut = null; // Reset check-out if before check-in
      }
    },

    async handleSearch() {
      console.log("Search Parameters:", this.search);

      // Navigate to the search results page with query parameters
      this.$router.push({
        path: "/search-results",
        query: {
          checkIn: this.search.checkIn,
          checkOut: this.search.checkOut,
          adults: this.search.adults,
          children: this.search.children,
        },
      });
    },
  },
};
</script>



<style scoped>
.hero-section {
  width: 100%;
  height: 80vh;
  position: relative;
  overflow: visible; /* Change from hidden to visible */
}

.hero-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  z-index: 0;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 1;
}

.hero-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  color: white;
  z-index: 2;
  max-width: 90%;
}

.hero-title {
  font-size: 3rem;
  font-weight: 700;
  margin: 0;
}

.hero-subtitle {
  font-size: 1.4rem;
  font-weight: 400;
  margin-top: 1rem;
}

.search-form {
  display: flex;
  gap: 1rem;
  justify-content: center;
  align-items: center;
  margin-top: 2rem;
  background: rgba(255, 255, 255, 0.9);
  padding: 1rem;
  border-radius: 8px;
  background-color: #001C55;
  z-index: 2;
}

.search-form input, .search-form select {
  padding: 0.8rem;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 1rem;
}

.search-btn {
  padding: 0.8rem 2rem;
  background-color: #D0A047;
  color: white;
  border: none;
  border-radius: 30px;
  font-size: 1.2rem;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s ease;
}

.search-btn:hover {
  background-color: #B6893A;
  transform: translateY(-3px);
}
</style>
