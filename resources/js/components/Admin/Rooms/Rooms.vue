<template>
  <div class="container">
    <div class="rooms">
      <h1>Manage Rooms</h1>

      <table class="rooms-table">
        <thead>
          <tr>
            <th>Room ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>capacity</th>
            <th>Price (MAD)</th>
            <th>Photo</th>
            <th>Amenities</th>
             <th>Photo Gallery</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="room in allRooms" :key="room.id">
            <td>{{ room.id }}</td>
            <td>{{ room.room_name }}</td>
            <td>{{ room.room_type }}</td>
            <td>{{ room.max_adults }} adults-{{ room.max_children }}child</td>
            <td>{{ parseFloat(room.price).toFixed(2) }}</td>

            <!-- Main Photo -->
            <td>
              <img
                :src="room.main_photo ? `${room.main_photo}` : '/images/rooms/default-room.png'"
                alt="Image"
                class="room-image"
              />
            </td>

            <!-- Amenities -->
            <td>
              <ul class="amenities-list">
                <li v-for="amenity in room.amenities" :key="amenity.id">
                  {{ amenity.name }}
                </li>
              </ul>
            </td>

                      <!-- Photo Gallery -->
      <td>
  <div class="gallery">
    <img v-for="photo in room.photo_gallery" 
         :key="photo.id" 
         :src="photo.photo_url" 
         class="room-image" />
  </div>
</td>


            <!-- Featured Checkbox -->
            <td>
              <input type="checkbox" :checked="room.featured" @change="toggleFeatured(room)" />
            </td>

            <!-- Actions -->
            <td class="action-buttons">
              <button class="btn secondary" @click="editRoom(room)">Edit</button>
              <button class="btn danger" @click="openDeleteModal(room.id)">Delete</button>
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
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'Rooms',
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
/* Container */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Table */
.rooms-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  table-layout: auto; /* Allows columns to fit content */
}

.rooms-table th,
.rooms-table td {
  border: 1px solid #ddd;
  padding: 8px !important;
  text-align: left;
  white-space: nowrap; /* Prevents wrapping */
}

.rooms-table th {
  background-color: #d0a047;
  color: white;
  font-size:12px;
}

.rooms-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}



.gallery {
  display: flex;
  flex-direction: column; /* Stack images vertically */
  gap: 10px; /* Adds spacing between images */
}

.room-image {
  width: 100px; /* Adjust width as needed */
  height: auto; /* Maintain aspect ratio */
  display: block; /* Ensures each image is on a new line */
    max-width: 50px;
  max-height: 50px;
  object-fit: cover;
  border-radius: 5px;
}

/* Amenities */
.amenities-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.amenities-list li {
  display: flex;
  align-items: center;
  gap: 5px;
}

/* Buttons */
.btn {
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn.secondary {
  background-color: #d0a047;
  color: white;
}

.btn.danger {
  background-color: #e74c3c;
  color: white;
}

.btn.primary {
  background-color: #d0a047;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}

/* Actions */
.action-buttons {
  display: flex;
  flex-direction: column; /* Stack buttons vertically */
  gap: 10px; /* Space between buttons */
}

.action-buttons .btn {
  width: 100%; /* Ensure full-width buttons */
  display: block; /* Forces buttons to appear on a new line */
}


/* Modal */
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
