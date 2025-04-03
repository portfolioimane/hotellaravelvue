<template>
  <div class="edit-room">
    <h1>Edit Room</h1>
    <form @submit.prevent="submitForm" class="room-form">
      <div class="form-group">
        <label for="room_name">Room Name:</label>
        <input
          type="text"
          id="room_name"
          v-model="room.room_name"
          required
          placeholder="e.g., Deluxe Suite"
        />
      </div>

      <div class="form-group">
        <label for="room_type">Room Type:</label>
        <input
          type="text"
          id="room_type"
          v-model="room.room_type"
          required
          placeholder="e.g., Double Bed"
        />
      </div>

      <div class="form-group">
        <label for="price">Price (in MAD):</label>
        <input
          type="number"
          id="price"
          v-model="room.price"
          required
          placeholder="e.g., 1500"
          min="0"
        />
      </div>

      <div class="form-group">
        <label for="max_adults">Max Adults:</label>
        <input
          type="number"
          id="max_adults"
          v-model="room.max_adults"
          required
          placeholder="e.g., 2"
          min="1"
        />
      </div>

      <div class="form-group">
        <label for="max_children">Max Children:</label>
        <input
          type="number"
          id="max_children"
          v-model="room.max_children"
          required
          placeholder="e.g., 1"
          min="0"
        />
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea
          id="description"
          v-model="room.description"
          required
          placeholder="Describe the room"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="main_photo">Upload Main Photo (Max 2MB):</label>
        <input
          type="file"
          id="main_photo"
          @change="handleImageUpload"
          accept="image/*"
        />
        <small class="help-text">
          <span>For optimal performance, please upload compressed images with a maximum size of 2MB. Consider using a tool to reduce the file size before uploading.</span>
        </small>
        <p v-if="imageError" class="error-message">The image file size exceeds the 2MB limit. Please upload a smaller image.</p>
      </div>

      <!-- Preview Section -->
      <div v-if="room.main_photo" class="image-preview">
        <img :src="imagePreview" alt="Room Image Preview" />
      </div>

      <div class="form-group">
        <label for="amenities">Select Amenities:</label>
        <div v-for="amenity in amenities" :key="amenity.id">
          <label :for="'amenity_' + amenity.id">
            <input
              type="checkbox"
              :id="'amenity_' + amenity.id"
              :value="amenity.id"
              v-model="room.amenities"
            />
            {{ amenity.name }}
          </label>
        </div>
      </div>

      <!-- Photo Gallery Section -->
      <div class="form-group">
        <label for="photo_gallery">Upload Additional Photos:</label>
        <input
          type="file"
          id="photo_gallery"
          multiple
          @change="handleGalleryUpload"
          accept="image/*"
        />
        <small class="help-text">
          <span>You can upload multiple images for the photo gallery. Max 2MB each.</span>
        </small>
      </div>

      <!-- Preview of Gallery Photos -->
      <div class="image-gallery-preview">
        <div
          v-for="(image, index) in room.photo_gallery"
          :key="index"
          class="image-preview"
        >
          <img :src="image.preview" alt="Gallery Image" />
          <button type="button" @click="removePhoto(index)">Remove</button>
        </div>
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Save Changes</button>
        <router-link to="/admin/rooms" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  data() {
    return {
      room: {
        room_name: '',
        room_type: '',
        max_adults: 0,
        max_children: 0,
        price: 0.0,
        description: '',
        featured: false,
        main_photo: null,
        photo_gallery: [],
        amenities: [],
      },
      imageError: false,
      imagePreview: '',
          removedPhotos: [], // To track removed photos
    };
  },
  computed: {
    ...mapGetters('backendAmenities', ['allAmenities']),
    amenities() {
      return this.allAmenities;
    },
    ...mapGetters('backendRooms', ['currentRoom']),
  },
  async created() {
    const roomId = this.$route.params.id; // Assuming the room ID is passed as a route param
    try {
      await this.$store.dispatch('backendRooms/fetchRoomById', roomId);
      this.room = { ...this.currentRoom };

      // Set image preview for the main photo
      this.imagePreview = this.room.main_photo;

      // Populate photo gallery with preview URLs
      this.room.photo_gallery.forEach((image) => {
        image.preview = image.photo_url; // Assuming 'photo_url' is the property holding the image URL
      });

      // Make sure amenities are populated correctly if available
      if (this.room.amenities && Array.isArray(this.room.amenities)) {
        // Ensure that amenities are correctly matched with the amenity IDs
        this.room.amenities = [...this.room.amenities];
      }

      // Ensure the checkboxes are properly checked
      if (this.room.amenities && this.room.amenities.length > 0) {
        this.room.amenities = this.room.amenities.map(amenity => amenity.id);
      }

    } catch (error) {
      console.error('Error fetching room:', error);
    }
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        if (file.size > 2 * 1024 * 1024) {
          this.imageError = true;
          this.room.main_photo = null;
          this.imagePreview = '';
        } else {
          this.imageError = false;
          this.room.main_photo = file;

          const reader = new FileReader();
          reader.onload = (e) => {
            this.imagePreview = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      }
    },

    handleGalleryUpload(event) {
      const files = Array.from(event.target.files);
      files.forEach((file) => {
        if (file.size <= 2 * 1024 * 1024) {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.room.photo_gallery.push({ file, preview: e.target.result });
          };
          reader.readAsDataURL(file);
        }
      });
    },

 removePhoto(index) {
    const photoUrl = this.room.photo_gallery[index].photo_url; // Assuming the 'photo_url' is stored
    this.removedPhotos.push(photoUrl); // Add to removed photos list
    this.room.photo_gallery.splice(index, 1); // Remove from preview
  },

    async submitForm() {
      const roomId = this.$route.params.id; // Assuming the room ID is passed as a route param

      if (this.imageError) {
        alert("Please upload a valid image.");
        return;
      }

      const formData = new FormData();
      
  formData.append('removed_photos', JSON.stringify(this.removedPhotos));

        // Ensure amenities are appended as an array
      this.room.amenities.forEach((amenityId) => {
        formData.append('amenities[]', amenityId);  // Append each amenity as an individual item
      });

      // Append other form data
      Object.entries(this.room).forEach(([key, value]) => {
        if (key === 'photo_gallery') {
          value.forEach((fileData) => {
            formData.append('photo_gallery[]', fileData.file);
          });
        } else if (key !== 'amenities') {
          formData.append(key, value);
        }
      });

      try {
        await this.$store.dispatch('backendRooms/updateRoom', { id: roomId, formData });
        this.$router.push('/admin/rooms');
      } catch (error) {
        console.error('Error updating room:', error);
      }
    },
  },
  mounted() {
    this.$store.dispatch('backendAmenities/fetchAmenities');
  },
};
</script>

<style scoped>
.image-preview {
  margin-top: 1rem;
  text-align: left;
}

.image-preview img {
  width: 100px !important;
  height: 100px !important;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.edit-room {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.edit-room h1 {
  color: #D0A047;
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}

.room-form .form-group {
  margin-bottom: 0.5rem;
}

.room-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.25rem;
  color: #555;
}

.room-form input[type="text"],
.room-form input[type="number"],
.room-form input[type="file"],
.room-form textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}

.room-form input:focus,
.room-form select:focus,
.room-form textarea:focus {
  border-color: #d4af37;
  outline: none;
}

.room-form textarea {
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

.help-text {
  font-size: 0.85rem;
  color: #777;
  margin-top: 0.5rem;
}

.error-message {
  color: red;
  font-size: 0.85rem;
  margin-top: 0.5rem;
}

.image-gallery-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-top: 1rem;
}

.image-gallery-preview .image-preview {
  width: 100px;
  height: 100px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.image-gallery-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

</style>
