<template>
  <div class="add-amenity">
    <h1>Add New Amenity</h1>
    <form @submit.prevent="submitForm" class="amenity-form">
      <div class="form-group">
        <label for="amenity_name">Amenity Name:</label>
        <input
          type="text"
          id="amenity_name"
          v-model="newAmenity.name"
          required
          placeholder="e.g., Swimming Pool"
        />
      </div>

      <div class="form-group">
        <label for="icon">Icon:</label>
        <!-- Click to open dropdown of icons -->
        <div class="icon-select" @click="toggleDropdown">
          <span v-if="newAmenity.icon" class="icon-preview">
            <Icon :icon="newAmenity.icon" width="30" height="30" />
          </span>
          <span v-else>Select Icon</span>
        </div>

        <!-- Icon dropdown -->
        <div v-if="dropdownVisible" class="icon-dropdown">
          <div
            class="icon-item"
            v-for="(icon, index) in iconList"
            :key="index"
            @click="selectIcon(icon)"
          >
            <Icon :icon="icon" width="30" height="30" />
            <span>{{ icon }}</span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea
          id="description"
          v-model="newAmenity.description"
          required
          placeholder="Describe the amenity"
        ></textarea>
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Add Amenity</button>
        <router-link to="/admin/amenities" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue';  // Correct import for Vue 4.x.x

export default {
  components: {
    Icon, // Use the correct component for Vue 4.x.x
  },
  data() {
    return {
      newAmenity: {
        name: '',
        icon: '',
        description: '',
      },
      iconList: [
        'mdi:swimming-pool',
        'mdi:wifi',
        'mdi:utensils',
        'mdi:bed',
        'mdi:car',
        'mdi:sofa',
        'mdi:television',
        'mdi:shower',
        // Add more icon names as needed
      ],
      dropdownVisible: false, // Controls visibility of the dropdown
    };
  },
  methods: {
    toggleDropdown() {
      // Toggle the visibility of the icon dropdown
      this.dropdownVisible = !this.dropdownVisible;
    },
    selectIcon(icon) {
      // Set the selected icon to the new amenity
      this.newAmenity.icon = icon;
      this.dropdownVisible = false; // Close the dropdown after selection
    },
    async submitForm() {
      // Validation: Ensure all fields are filled out
      if (!this.newAmenity.name || !this.newAmenity.icon || !this.newAmenity.description) {
        alert("Please fill out all fields.");
        return;
      }

      try {
        // Sending the new amenity data to the backend via a store action
        await this.$store.dispatch('backendAmenities/addAmenity', this.newAmenity);
        this.$router.push('/admin/amenities'); // Redirect to amenities list page
      } catch (error) {
        console.error('Error adding amenity:', error);
      }
    },
  },
};
</script>

<style scoped>
.add-amenity {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.add-amenity h1 {
  color: #D0A047;
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}

.amenity-form .form-group {
  margin-bottom: 0.5rem;
}

.amenity-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.25rem;
  color: #555;
}

.amenity-form input[type="text"],
.amenity-form textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}

.amenity-form input:focus,
.amenity-form textarea:focus {
  border-color: #d4af37;
  outline: none;
}

.amenity-form textarea {
  resize: vertical;
  min-height: 80px;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  font-size: 0.9rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  text-transform: uppercase;
}

.btn.primary {
  background-color: #D0A047;
  color: #fff;
}

.btn.secondary {
  background-color: #555;
  color: #fff;
}

.btn:hover {
  opacity: 0.9;
}

.icon-select {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f9f9f9;
}

.icon-select span {
  display: flex;
  align-items: center;
  font-size: 0.9rem;
}

.icon-preview {
  margin-right: 10px;
}

.icon-dropdown {
  margin-top: 10px;
  max-height: 200px;
  overflow-y: auto;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 0.5rem;
}

.icon-item {
  display: flex;
  align-items: center;
  padding: 0.5rem;
  cursor: pointer;
}

.icon-item:hover {
  background-color: #f0f0f0;
}

.icon-item span {
  margin-left: 10px;
  font-size: 0.9rem;
}
</style>
