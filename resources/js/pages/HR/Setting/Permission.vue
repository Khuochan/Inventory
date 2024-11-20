<template>
    <div>
      <form @submit.prevent="submitForm">
        <input type="file" @change="onFileChange">
        <button type="submit">Upload</button>
      </form>
      <p v-if="isLoading">Uploading...</p>
      <p v-if="error">Error occurred: {{ error }}</p>
      <div v-if="imageUrl">
        <img :src="imageUrl" alt="Uploaded Image">
      </div>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        imageFile: null,
        imageUrl: null,
        isLoading: false,
        error: null
      }
    },
    methods: {
      onFileChange(event) {
        this.imageFile = event.target.files[0];
      },
      async submitForm() {
        this.isLoading = true;
        this.error = null;

        try {
          const formData = new FormData();
          formData.append('attachment', this.imageFile);

          const response = await axios.post('api/v1/attendance/upload', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });

          this.imageUrl = response.data.path;
        } catch (error) {
          if (error.response && error.response.status === 422) {
            this.error = 'Invalid image format or size. Please try again.';
          } else {
            this.error = 'An error occurred while uploading the image. Please try again later.';
          }
        } finally {
          this.isLoading = false;
        }
      }
    }
  }
  </script>
