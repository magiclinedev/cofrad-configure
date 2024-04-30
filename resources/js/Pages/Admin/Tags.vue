<template>
    <div>
        <Navbar />
        <h1 class="text-6xl">TAGS</h1>
        <div class="flex">
            <div class="w-1/2 pr-8">
                <!-- Table showing all tags -->
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Image</th>
                        <th class="border border-gray-300 px-4 py-2">Tag Type</th>
                        <th class="border border-gray-300 px-4 py-2">Parent Tag</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="tag in tags" :key="tag.id">
                        <td class="border border-gray-300 px-4 py-2">{{ tag.name }}</td>
                        <td class="border border-gray-300 px-4 py-2"><img :src="'/storage/' + tag.image" alt="Tag Image" class="h-32 border">
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ tag.tag }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ getParentTagName(tag.parent_tag_id) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-1/2 pl-8">
                <!-- Add Tag form -->
                <h1 class="text-3xl">Add Tag</h1>
                <form @submit.prevent="addTag" class="mb-8 mr-4 border p-5">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <label for="name">Name:</label>
                    <input type="text" id="name" v-model="name" required class="mb-4 p-2 border border-gray-300 rounded-md w-full">

                    <label for="image" class="mb-2">Image:</label>
                    <input type="file" id="image" ref="image" @change="handleImageChange" accept="image/*" required class="mb-4 p-2 border border-gray-300 rounded-md w-full">

                    <div v-if="imageUrl" class="mb-4">
                    <img :src="imageUrl" alt="Selected Image" class="mb-2" style="max-width: 200px; max-height: 200px;">
                    </div>

                    <div class="mb-4">
                    <label for="tag">Tag Type:</label><br>
                    <select id="tag" v-model="tag" required class="border border-gray-300 rounded-md p-2 w-full">
                        <option value="Gender">Gender</option>
                        <option value="Pose">Pose</option>
                        <option value="Head">Head</option>
                        <option value="Arms">Arms</option>
                        <option value="Finish">Finish</option>
                        <option value="Color">Color</option>
                    </select>
                    </div>

                    <div class="mb-4">
                    <label for="parent_tag_id">Parent Tag:</label><br>
                    <select id="parent_tag_id" v-model="parent_tag_id" class="border border-gray-300 rounded-md p-2 w-full">
                        <option value="">No Parent Tag</option>
                        <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
                    </select>
                    </div>

                    <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded-md" :disabled="loading">
                    <span v-if="loading">Loading...</span>
                    <span v-else>Add Tag</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Navbar from '@/Components/Navbar.vue';
    import axios from 'axios';

    export default {
        components: {
            Navbar
        },
        data() {
            return {
                name: '',
                imageUrl: '',
                imageFile: null,
                tag: '',
                parent_tag_id: '',
                loading: false,
                csrfToken: document.querySelector('meta[name="csrf-token"]').content,
                tags: []
            };
        },
        mounted() {
            axios.get('/tags-index')
                .then(response => {
                this.tags = response.data;
            })
                .catch(error => {
                console.error(error);
            });
        },
        methods: {
            addTag() {
                this.loading = true;
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('image', this.imageFile);
                formData.append('tag', this.tag);
                formData.append('parent_tag_id', this.parent_tag_id);

                axios.post('/tags-add', formData)
                .then(response => {
                    console.log(response.data);
                    this.name = '';
                    this.imageUrl = '';
                    this.tag = '';
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
            getParentTagName(parentTagId) {
                const parentTag = this.tags.find(tag => tag.id === parentTagId);
                return parentTag ? parentTag.name : ' ';
            }
        }
    };
</script>

<style>
/* Add your custom styles here */
</style>
