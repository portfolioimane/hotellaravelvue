<template>
  <div class="home-page">
    <!-- Home Page Header Section -->
    <HomePageHeader />

    <!-- Services Section -->
    <ServicesSection />

    <!-- Product List Section -->
    <FeaturedRoom />

    <!-- YouTube Video Section -->
    <div class="video-container">
      <iframe
        width="1200"
        height="400"
        src="https://www.youtube.com/embed/lkWY2Mdm5UU?si=Ape65ufB2UFtd8bk"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
      </iframe>
    </div>

    <FeaturedReviews />
  </div>
</template>

<script>
// Import the components
import HomePageHeader from './HomePageHeader.vue';
import ServicesSection from './ServicesSection.vue';  // Import the new ServicesSection component
import FeaturedRoom from './Rooms/FeaturedRoom.vue';
import FeaturedReviews from './FeaturedReviews.vue';

export default {
  name: 'Home',
  components: {
    HomePageHeader,
    ServicesSection, // Register the ServicesSection component
    FeaturedRoom,
    FeaturedReviews,
  },
};
</script>

<style scoped>
.home-page {
  display: flex;
  flex-direction: column;
  padding: 0;
}

/* Video Container Styling */
.video-container {
  margin-top: 2rem; /* Add margin to separate from other content */
  text-align: center; /* Center the video */
}

.video-container iframe {
  border: none;
  max-width: 100%; /* Ensure the iframe is responsive */
  height: 450;
}
</style>
