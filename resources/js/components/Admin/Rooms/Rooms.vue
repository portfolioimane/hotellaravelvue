<template>
  <div class="rooms">
    <h1>Manage Rooms</h1>
    
    <table class="rooms-table">
      <thead>
        <tr>
          <th>Room ID</th>
          <th>Name</th>
          <th>Type</th>
          <th>Max Adults</th>
          <th>Max Children</th>
          <th>Price (MAD)</th>
          <th>Main Photo</th>
            <th>Amenities</th>
          <th>Featured</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="room in allRooms" :key="room.id">
          <td>{{ room.id }}</td>
          <td>{{ room.room_name }}</td>
          <td>{{ room.room_type }}</td>
          <td>{{ room.max_adults }}</td>
          <td>{{ room.max_children }}</td>
          <td>{{ parseFloat(room.price).toFixed(2) }}</td>

          <!-- Main Photo -->
          <td>
            <img 
              :src="room.main_photo ? `${room.main_photo}` : '/images/rooms/default-room.png'" 
              alt="Room Image" 
              class="room-image"
            />
          </td>
  <!-- Amenities -->
<td>
  <ul class="amenities-list">
    <li v-for="amenity in room.amenities" :key="amenity.id">
      <!-- Display the icon dynamically based on the icon string -->
      {{ amenity.name }}
    </li>
  </ul>
</td>


      



          <!-- Featured Checkbox -->
          <td>
            <input 
              type="checkbox" 
              :checked="room.featured" 
              @change="toggleFeatured(room)" 
            />
          </td>

          <!-- Actions -->
          <td class="action-buttons">
            <div class="edit-delete-btns">
              <button class="btn secondary" @click="editRoom(room)">Edit</button>
              <button class="btn danger" @click="openDeleteModal(room.id)">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Are you sure you want to delete this room?</h3>
        <div class="modal-actions">
          <button class="btn danger" @click="deleteRoom">Yes, Delete</button>
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
  name: 'Rooms',
    components: {
    Icon,  // Register the Icon component
  },
  data() {
    return {
      showDeleteModal: false,
      roomToDelete: null,
      message: null,
    };
  },
  computed: {
    ...mapGetters('backendRooms', ['allRooms']),
  },
  methods: {
    editRoom(room) {
      this.$router.push({ name: 'EditRoom', params: { id: room.id } });
    },
    openDeleteModal(roomId) {
      this.roomToDelete = roomId;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.roomToDelete = null;
    },
    async deleteRoom() {
      try {
        await this.$store.dispatch('backendRooms/deleteRoom', this.roomToDelete);
        this.showDeleteModal = false;
        this.roomToDelete = null;
        this.showMessage('Room deleted successfully', 'success');
      } catch (error) {
        this.showMessage('Error deleting room', 'error');
      }
    },
    async toggleFeatured(room) {
      try {
        room.featured = !room.featured; 
        await this.$store.dispatch('backendRooms/toggleFeatured', room.id);
        this.showMessage('Room featured status updated', 'success');
      } catch (error) {
        this.showMessage('Error updating featured status', 'error');
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
    this.$store.dispatch('backendRooms/fetchRooms');
  },
};
</script>

<style scoped>
.rooms-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  table-layout: auto; /* Ensures columns resize according to their content */
}

.rooms-table th, .rooms-table td {
  border: 1px solid #ddd;
  padding: 15px;
  text-align: left;
  word-wrap: break-word; /* Prevents long words from breaking the layout */
}

.rooms-table th {
  background-color: #D0A047;
  color: white;
}

.rooms-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

/* Optional: You can set a maximum width for certain columns if you want to control them */
.rooms-table td:nth-child(7), .rooms-table td:nth-child(8), .rooms-table td:nth-child(9) {
  max-width: 150px; /* Set a max-width for photo gallery or amenities column */
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap; /* Prevent content from wrapping into multiple lines */
}

.room-image {
  max-width: 100px;
  max-height: 100px;
  object-fit: cover;
}

.gallery {
  display: flex;
  gap: 5px;
  overflow-x: auto;
  max-width: 150px;
}

.gallery-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 5px;
}

.amenities-list {
  list-style: none;
  padding: 0;
  display: flex !important;
  flex-wrap: wrap !important;  /* Allow items to wrap to the next line */
  gap: 10px !important; /* Optional, adds space between items */
}

.amenities-list li {
  display: inline-flex !important;  /* Ensures the items are inline but can wrap */
  align-items: center !important;
  gap: 5px !important;
  flex-basis: 100%; /* Makes each item take full width of the container */
}




.room-image {
  max-width: 100px;
  max-height: 100px;
  object-fit: cover;
}

.gallery {
  display: flex;
  gap: 5px;
  overflow-x: auto;
  max-width: 150px;
}

.gallery-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 5px;
}

.amenities-list {
  list-style: none;
  padding: 0;
}

.amenities-list li {
  display: flex;
  align-items: center;
  gap: 5px;
}

.amenity-icon {
  width: 20px;
  height: 20px;
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
