<template>
  <div class="contact-section">
    <!-- Title and Text Before the Columns -->
    <div class="contact-header">
      <h1>Get in Touch with Us</h1>
      <p>If you have any questions or inquiries, feel free to reach out. We would love to hear from you!</p>
    </div>

    <!-- Contact Columns -->
    <div class="contact-container">
      <!-- Column 1: Information Section -->
      <div class="info-column">
        <div class="info-item">
          <i class="fas fa-phone-alt"></i>
          <div>
            <h3>Phone</h3>
            <p>{{ phone }}</p>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-map-marker-alt"></i>
          <div>
            <h3>Address</h3>
            <p>{{ address }}</p>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-envelope"></i>
          <div>
            <h3>Gmail</h3>
            <p>{{ gmail }}</p>
          </div>
        </div>
      </div>

      <!-- Column 2: Contact Form Section -->
      <div class="form-column">
        <form @submit.prevent="submitForm">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" v-model="formData.name" placeholder="Enter your name" required />
          </div>
          <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" v-model="formData.email" placeholder="Enter your email" required />
          </div>
          <div class="form-group">
            <label for="message">Your Message</label>
            <textarea id="message" v-model="formData.message" placeholder="Write your message" required></textarea>
          </div>
          <button type="submit" class="submit-btn">Send Message</button>
        </form>

        <!-- Success Message -->
        <div v-if="formSubmitted" class="success-message">
          <h2>Thank You!</h2>
          <p>We have received your message, and we'll get back to you soon!</p>
        </div>
      </div>
    </div>

    <!-- Google Map Section -->
    
      <h3>Our Location</h3>
   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d425513.7307048038!2d-8.177915501474684!3d33.5708834841945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd4778aa113b%3A0xb06c1d84f310fd3!2sCasablanca!5e0!3m2!1sfr!2sma!4v1743719200305!5m2!1sfr!2sma" width="1200" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>


    <!-- Error Message -->
    <div v-if="errorMessage" class="error-message">
      <h2>Error!</h2>
      <p>{{ errorMessage }}</p>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  name: "ContactComponent",
  data() {
    return {
      formData: {
        name: "",
        email: "",
        message: "",
      },
      formSubmitted: false, // Flag to show the success message
    };
  },
  computed: {
    ...mapGetters("generalCustomize", ["getGeneralCustomize"]),
    phone() {
      return this.getGeneralCustomize("phone");
    },
    address() {
      return this.getGeneralCustomize("address");
    },
    gmail() {
      return this.getGeneralCustomize("gmail");
    },
    successMessage() {
      return this.$store.getters["contact/successMessage"];
    },
    errorMessage() {
      return this.$store.getters["contact/errorMessage"];
    }
  },
  methods: {
    ...mapActions("contact", ["sendContactMessage"]),
    submitForm() {
      this.sendContactMessage(this.formData).then(() => {
        this.formSubmitted = true; // Show success message after form submission
        this.formData = { name: "", email: "", message: "" }; // Clear form fields

        // Hide success message after 5 seconds
        setTimeout(() => {
          this.formSubmitted = false;
        }, 10000);
      });
    },
  },
  mounted() {
    this.$store.dispatch("generalCustomize/fetchGeneralCustomizes");
  },
};
</script>

<style scoped>
.contact-section {
  background-color: white;
  padding: 3rem;
}

.contact-header {
  text-align: center;
  margin-bottom: 2rem;
}

.contact-header h1 {
  font-size: 2.5rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 0.5rem;
}

.contact-header p {
  font-size: 1.1rem;
  color: #555;
}

.contact-container {
  display: flex;
  justify-content: space-between;
  gap: 2rem;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.info-column,
.form-column {
  flex: 1;
  padding: 2rem;
}

.info-item {
  display: flex;
  align-items: center;
  margin-bottom: 2rem;
}

.info-item i {
  font-size: 2rem;
  color: #D0A047;
  margin-right: 1rem;
}

h3 {
  font-size: 1.1rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 0.5rem;
}

p {
  font-size: 1rem;
  color: #555;
}

.form-column h2 {
  font-size: 2.2rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 1rem;
}

.form-description {
  font-size: 1.1rem;
  color: #555;
  margin-bottom: 1.5rem;
}

.form-column form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  font-weight: bold;
  font-size: 1.1rem;
  color: #333;
}

input,
textarea {
  width: 100%;
  padding: 1rem;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s;
}

input:focus,
textarea:focus {
  border-color: #D0A047;
  outline: none;
}

textarea {
  resize: vertical;
  min-height: 120px;
}

button {
  padding: 1rem;
  background-color: #D0A047;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #187bcd;
}

.success-message {
  margin-top: 2rem;
  background-color: #d4efdb;
  padding: 1rem;
  border-radius: 5px;
  color: #2a7d4d;
}

.error-message {
  margin-top: 2rem;
  background-color: #f8d7da;
  padding: 1rem;
  border-radius: 5px;
  color: #721c24;
}

.map-column {
  margin-top: 2rem;
  text-align: center;
}

.map-column iframe {
  border: none;
  max-width: 100%;
  height: auto;
}

@media (max-width: 768px) {
  .contact-container {
    flex-direction: column;
    padding: 1rem;
  }

  .info-column,
  .form-column {
    flex: 1 100%;
    margin-bottom: 2rem;
  }
}
</style>
