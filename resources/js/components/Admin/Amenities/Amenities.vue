<template>
  <div class="amenities">
    <h1>Manage Amenities</h1>
    
    <table class="amenities-table">
      <thead>
        <tr>
          <th>Amenity ID</th>
          <th>Name</th>
          <th>Icon</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="amenity in allAmenities" :key="amenity.id">
          <td>{{ amenity.id }}</td>
          <td>{{ amenity.name }}</td>
          <td>
            <!-- Use the Icon component to display the icon -->
            <Icon :icon="amenity.icon" width="30" height="30" />
          </td>
          <td>{{ amenity.description }}</td>
          <td class="action-buttons">
            <div class="edit-delete-btns">
              <button class="btn secondary" @click="editAmenity(amenity)">Edit</button>
              <button class="btn danger" @click="openDeleteModal(amenity.id)">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Are you sure you want to delete this amenity?</h3>
        <div class="modal-actions">
          <button class="btn danger" @click="deleteAmenity">Yes, Delete</button>
          <button class="btn primary" @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { Icon } from '@iconify/vue';  // Import the Icon component

export default {
  name: 'Amenities',
  components: {
    Icon,  // Register the Icon component
  },
  data() {
    return {
      showDeleteModal: false,
      amenityToDelete: null,
      message: null,
    };
  },
  computed: {
    ...mapGetters('backendAmenities', ['allAmenities']),
  },
  methods: {
    editAmenity(amenity) {
      this.$router.push({ name: 'EditAmenity', params: { id: amenity.id } });
    },
    openDeleteModal(amenityId) {
      this.amenityToDelete = amenityId;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.amenityToDelete = null;
    },
    async deleteAmenity() {
      try {
        await this.$store.dispatch('backendAmenities/deleteAmenity', this.amenityToDelete);
        this.showDeleteModal = false;
        this.amenityToDelete = null;
        this.showMessage('Amenity deleted successfully', 'success');
      } catch (error) {
        this.showMessage('Error deleting amenity', 'error');
      }
    },
    showMessage(text, type) {
      this.message = { text, type };
      setTimeout(() => {
        this.message = null;
      }, 5000);
    },
  },
  mounted() {
    this.$store.dispatch('backendAmenities/fetchAmenities');
  },
};
</script>

<style scoped>
.amenities {
  padding: 30px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #D0A047;
  margin-bottom: 20px;
  text-align: center;
}

.amenities-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.amenities-table th, .amenities-table td {
  border: 1px solid #ddd;
  padding: 15px;
  text-align: left;
}

.amenities-table th {
  background-color: #D0A047;
  color: white;
}

.amenities-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.amenity-icon {
  width: 30px;
  height: 30px;
}

.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn.secondary {
  background-color: #D0A047;
  color: white;
}

.btn.danger {
  background-color: #e74c3c;
  color: white;
}

.btn.primary {
  background-color: #D0A047;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}

.action-buttons {
  display: flex;
  gap: 10px;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
}

.modal-actions button {
  margin: 0 10px;
}
</style>
