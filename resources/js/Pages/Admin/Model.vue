<template>
    <div>
        <Navbar />
        <h1 class="text-6xl">MODELS</h1>
        <div class="flex">
            <div class="w-1/2 pr-8">
                <!-- Table showing all MODELS -->
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Image</th>
                        <th class="border border-gray-300 px-4 py-2">Tag Type</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="model in models" :key="model.id">
                        <td class="border border-gray-300 px-4 py-2">{{ model.name }}</td>
                        <td class="border border-gray-300 px-4 py-2"><img :src="'/storage/' + model.images" alt="Tag Image" class="h-30 border"></td>
                        <td class="border border-gray-300 px-4 py-2">{{ model.tags }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <button @click="editModel(model)" class="button is-primary">Edit</button>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Add Model form -->
            <div v-if="!editingModel" class="w-1/2 pl-8">
                <h1 class="text-3xl">Add Model</h1>
                <div class="flex border p-3 mr-4">
                    <div class="w-1/2 pr-4">
                        <form @submit.prevent="addModel" class="p-4 border border-gray-300 rounded-md">
                            <label for="name" class="block">Name:</label>
                            <input type="text" id="name" v-model="name" required class="block w-full p-2 border border-gray-300 rounded-md mb-4">
                            <label for="image" class="block">Image:</label>
                            <input type="file" id="image" @change="handleImageChange" accept="image/*" required class="block w-full p-2 border border-gray-300 rounded-md mb-4">
                            <div v-if="imageUrl" class="mb-4">
                                <img :src="imageUrl" alt="Uploaded Image" class="max-w-full h-40">
                            </div>
                            <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded-md">Add Model</button>
                        </form>
                    </div>
                    <div class="w-1/2 pl-4">
                        <div class="p-4 border border-gray-300 rounded-md">
                            <label for="tags" class="block">Tags:</label>
                            <div v-for="(tagData, tagType) in tagsByType" :key="tagType" class="mb-4">
                                <h2>{{ tagData.tag }}</h2>
                                <select v-model="selectedTags[tagData.tag]" required class="block w-full p-2 border border-gray-300 rounded-md">
                                    <option v-for="tag in tagData.tags.split(',')" :key="tag" :value="tag">{{ tag }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Model form -->
            <div v-if="editingModel" class="w-1/2 pl-8">
                <h1 class="text-3xl">Edit Model</h1>
                <div class="flex border p-3 mr-4">
                    <div class="w-1/2 pr-4">
                        <form @submit.prevent="updateModel" class="p-4 border border-gray-300 rounded-md">
                            <label for="editName" class="block">Name:</label>
                            <input type="text" id="editName" v-model="selectedModel.name" required class="block w-full p-2 border border-gray-300 rounded-md mb-4">
                            <label for="editImage" class="block">Image:</label>
                            <input type="file" id="editImage" @change="handleEditImageChange" accept="image/*" class="block w-full p-2 border border-gray-300 rounded-md mb-4">
                            <div v-if="selectedModel.images" class="mb-4">
                                <img :src="'/storage/' + selectedModel.images" alt="Uploaded Image" class="max-w-full h-40">
                            </div>
                            <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded-md">Update Model</button>
                            <button @click="cancelEdit" class="mt-4 bg-red-500 text-white p-2 rounded-md">Cancel</button>
                        </form>
                    </div>
                    <div class="w-1/2 pl-4">
                        <div class="p-4 border border-gray-300 rounded-md">
                            <label for="editTags" class="block">Tags:</label>
                            <div v-for="(tagData, tagType) in tagsByType" :key="tagType" class="mb-4">
                                <h2>{{ tagData.tag }}</h2>
                                <select v-model="selectedTags[tagData.tag]" required class="block w-full p-2 border border-gray-300 rounded-md">
                                    <option v-for="tag in tagData.tags.split(',')" :key="tag" :value="tag">{{ tag }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END EDIT -->
        </div>
    </div>
</template>

<script>
    import Navbar from '@/Components/Navbar.vue';
    import axios from 'axios';

    export default {
        components: {
            Navbar,
        },
        data() {
            return {
                name: '',
                imageUrl: '',
                imageFile: null,
                tags: [],
                selectedTags: {},
                loading: false,
                csrfToken: document.querySelector('meta[name="csrf-token"]').content,
                tagsByType: {},
                selectedModel: null,
                models: [], // Your models data
                editingModel: false, // Flag to determine if edit form should be visible
            };
        },
        mounted() {
            this.fetchModels();
            this.fetchTags();
        },
        methods: {
            fetchModels() {
                axios.get('/models-index')
                    .then(response => {
                        this.models = response.data;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            },
            fetchTags() {
                axios.get('/tags-by-type-admin')
                    .then(response => {
                        this.tagsByType = response.data;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            },
            addModel() {
                this.loading = true;
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('image', this.imageFile);
                formData.append('tags', JSON.stringify(Object.entries(this.selectedTags).map(([key, value]) => ({ key, value }))));

                axios.post('/add-model', formData)
                    .then(response => {
                        console.log(response.data);
                        // Reset form fields after successful submission
                        this.name = '';
                        this.imageUrl = '';
                        this.imageFile = null;
                        this.selectedTags = {};
                    })
                    .catch(error => {
                        console.error(error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            handleImageChange(event) {
                this.imageFile = event.target.files[0];
                this.imageUrl = URL.createObjectURL(this.imageFile);
            },

            editModel(model) {
                // Set the selected model and show the edit form
                this.selectedModel = model;
                this.editingModel = true;
            },
            cancelEdit() {
                // Reset selected model and hide the edit form
                this.selectedModel = null;
                this.editingModel = false;
            },
        }
    };
</script>

<style>
/* Add your custom styles here */
</style>
