<template>
  <section class="services-section">
    <div class="service" v-for="(service, index) in services" :key="index">
      <div class="icon">
        <i :class="service.icon"></i>
      </div>
      <div class="text">
        <h3>{{ service.title }}</h3>
        <p>{{ service.description }}</p>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "HotelServicesSection",
  data() {
    return {
      services: [
        {
          title: "Free Wi-Fi",
          icon: "fas fa-wifi", // FontAwesome icon for Wi-Fi
          description: "Stay connected with free high-speed Wi-Fi available throughout the hotel.",
        },
        {
          title: "24/7 Room Service",
          icon: "fas fa-concierge-bell", // FontAwesome icon for room service
          description: "Enjoy round-the-clock room service for meals and beverages right to your door.",
        },
        {
          title: "Comfortable Beds",
          icon: "fas fa-bed", // FontAwesome icon for comfortable bed
          description: "Sleep soundly in our luxurious, comfortable beds designed for the ultimate relaxation.",
        }
      ],
    };
  },
};
</script>

<style scoped>
.services-section {
  display: flex;
  justify-content: space-between;
  text-align: center;
  padding: 20px;
  background-color: #f8f8f8;
}

.service {
  flex: 1;
  padding: 15px;
  border-radius: 8px;
  background: white;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
  margin: 10px;
  transition: transform 0.3s ease-in-out;
}

.service:hover {
  transform: translateY(-5px);
}

.icon {
  font-size: 40px;
  color: #001C55; /* Lighter blue */
  margin-bottom: 10px;
}

.text h3 {
  font-size: 18px;
  margin-bottom: 5px;
}

.text p {
  font-size: 14px;
  color: #555;
}
</style>
