<template>
    <div>
        <Head title="Configure" />
        <div class="app">
            <div class="flex flex-col md:flex-row w-full md:h-screen"  :class="{'oneBG': modelBackgroundColor == 1, 'twoBG': modelBackgroundColor == 2, 'threeBG': modelBackgroundColor == 3}">
                <div class="md:w-3/4 pr-4 h-1/2 md:h-screen relative">
                    <div class="model relative" v-if="modelImage">
                        <div v-if="loading" class="absolute inset-0 flex items-center justify-center z-50">
                            <div role="status">
                                <svg aria-hidden="true" class="inline w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-gray-600 dark:fill-gray-300" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                </svg>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <img :src="'storage/' + modelImage" alt="Model Image" class="max-w-full h-screen"
                             :class="{ 'zoom-out': outZoom, 'zoom-head': headZoom, 'zoom-arms': armsZoom }">
                    </div>
                    <div v-else class="text-center">No model found</div>
                </div>
                <!-- TAGS -->
                <div class="md:w-1/3 md:relative">
                    <div class="p-4 h-fit text-white">
                        <!-- PARENT TAG -->
                        <div v-for="(tagData, tagType) in tagsByType" :key="tagData.tag" class="mb-4">
                            <h2 class="text-lg font-semibold">{{ tagData.tag }}</h2>
                            <div class="custom-dropdown relative">
                                <div class="selected-tag flex items-center justify-between rounded-xl" @click="toggleDropdown(tagData.tag)">
                                    <span>{{ selectedTags[tagData.tag] || 'Select Tag' }}</span>
                                    <img v-if="selectedTags[tagData.tag]" :src="'storage/' + tagData.tags.find(tag => tag.tag === selectedTags[tagData.tag]).image"
                                         alt="Selected Tag Image" class="w-12 h-auto ml-2 border">
                                </div>
                                <div v-show="showDropdown === tagData.tag" class="dropdown flex flex-wrap justify-center rounded-md mt-2 text-white">
                                    <div v-for="(tag, index) in tagData.tags" :key="tag.tag" @click="selectTag(tagData.tag, tag.tag)"
                                         :class="{ 'tag-Head': tag.tag === 'Head', 'tag-Arms': tag.tag === 'Arms', 'selected': selectedTags[tagData.tag] === tag.tag }"
                                         class="flex items-center p-2 cursor-pointer rounded-md mr-5">
                                        <span>{{ tag.tag }}</span>
                                        <img :src="'storage/' + tag.image" alt="Tag Image" class="w-12 h-auto ml-2 border">
                                    </div>
                                </div>
                                <button v-show="showDropdown === tagData.tag" @click="toggleDropdown(tagData.tag)" class="absolute top-0 right-0 m-1 px-2 py-1 text-xs bg-gray-300 rounded-md">Close</button>
                            </div>
                        </div>
                        <!-- CHILDREN TAGS -->
                        <div v-for="(tagData, tagType) in tagsByTypePose" :key="tagData.tag" class="mb-4">
                            <h2 class="text-lg font-semibold">{{ tagData.tag }}</h2>
                            <div class="custom-dropdown relative">
                                <div class="selected-tag flex items-center justify-between rounded-xl" @click="toggleDropdown(tagData.tag)">
                                    <span>{{ selectedTags[tagData.tag] || 'Select Tag' }}</span>
                                    <img v-if="selectedTags[tagData.tag]" :src="'storage/' + (tagData.tags.find(tag => tag.tag === selectedTags[tagData.tag])?.image || '')"
                                         alt="Selected Tag Image" class="w-12 h-auto ml-2 border">
                                </div>
                                <div v-show="showDropdown === tagData.tag" class="dropdown flex flex-wrap justify-center rounded-md mt-2 text-white">
                                    <div v-for="(tag, index) in tagData.tags" :key="tag.tag" @click="selectTag(tagData.tag, tag.tag)"
                                         :class="{ 'tag-Head': tag.tag === 'Head', 'tag-Arms': tag.tag === 'Arms', 'selected': selectedTags[tagData.tag] === tag.tag }"
                                         class="flex items-center p-2 cursor-pointer rounded-md mr-5">
                                        {{ tag.tag }}
                                        <img :src="'storage/' + tag.image" alt="Tag Image" class="w-12 h-auto ml-2 border">
                                    </div>
                                </div>
                                <button v-show="showDropdown === tagData.tag" @click="toggleDropdown(tagData.tag)" class="absolute top-0 right-0 m-1 px-2 py-1 text-xs bg-gray-300 rounded-md">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white p-16 rounded-lg relative">
                    <button @click="showModal = false" class="absolute top-0 right-0 m-2 text-gray-600 hover:text-gray-800">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <p>Not Available</p>
                </div>
            </div>


        </div>
    </div>
</template>

<script setup>
    import { Head } from '@inertiajs/vue3';
    import { ref, onMounted, onUnmounted } from 'vue';
    import axios from 'axios';
    const tagOrder = ['Gender', 'Pose', 'Head', 'Arms', 'Finish', 'Color']; // Define the order of tags
    const tagsByType = ref([]);
    const tagsByTypePose = ref([]);
    const selectedTags = ref({
        Gender: 'Female',
        Pose: 'Elle 6',
        Head: 'EVE',
        Arms: 'Regular',
        Finish: 'Fiber',
        Color: 'White'
    });
    const modelImage = ref('');
    const loading = ref(false);
    const showDropdown = ref(null);
    const outZoom = ref(false);
    const headZoom = ref(false); // Track the zoom state for the head
    const armsZoom = ref(false); // Track the zoom state for the ARMS

    const showModal = ref(false);

    const fetchTagsByType = async () => {
        try {
            const response = await axios.get('/tags-by-type');
            tagsByType.value = response.data.map(tag => ({
                tag: tag.tag,
                id: tag.ids.split(',').map(Number)[0], // Assuming ids is a comma-separated list of numbers
                tags: tag.names.split(',').map((name, index) => ({ tag: name, id: tag.ids.split(',')[index], image: tag.images.split(',')[index] })),
                images: tag.images.split(',') // Assuming images is a comma-separated list
            }));
            selectedTagId.value = tagsByType.value[0]?.id; // Set the ID of the first tag as the selectedTagId
            searchModel();
            fetchTagsByTypePose();
        } catch (error) {
            console.error(error);
        }
    };

    const selectedTagId = ref(null);

    const fetchTagsByTypePose = async () => {
        try {
            const selectedTagIds = Object.entries(selectedTags.value)
                .map(([tagType, tagName]) => tagsByType.value.find(t => t.tag === tagType)?.tags.find(t => t.tag === tagName)?.id)
                .filter(id => id !== undefined); // Filter out undefined IDs
            if (selectedTagIds.length > 0) {
                const allTagsByTypePose = [];
                for (const tagId of selectedTagIds) {
                    const response = await axios.get(`/tags-by-type-pose/${tagId}`);
                    const tagsByTypePose = response.data.map(tag => ({
                        tag: tag.tag,
                        tags: tag.names.split(',').map((name, index) => ({ tag: name, image: tag.images.split(',')[index] })),
                        images: tag.images.split(',') // Assuming images is a comma-separated list
                    }));

                    // Group tags with the same tag under the same parent tag
                    tagsByTypePose.forEach(newTag => {
                        const existingTag = allTagsByTypePose.find(tag => tag.tag === newTag.tag);
                        if (existingTag) {
                            existingTag.tags.push(...newTag.tags);
                        } else {
                            allTagsByTypePose.push(newTag);
                        }
                    });
                }
                tagsByTypePose.value = allTagsByTypePose;
            }
        } catch (error) {
            console.error(error);
        }
    };

    const modelBackgroundColor = ref(1); // Default background color
    const searchModel = async () => {
        loading.value = true;
        try {
            const tagsArray = Object.entries(selectedTags.value).map(([key, value]) => ({ key, value }));
            console.log('Model tags:', tagsArray.map(tag => ({ [tag.key]: { id: tagsByType.value.find(t => t.tag === tag.key)?.tags.find(t => t.tag === tag.value)?.id, value: tag.value } })));
            const response = await axios.post('/search-model', { tags: tagsArray });
            const models = response.data.models;
            if (models.length > 0) {
                modelImage.value = models[0].images;
                modelBackgroundColor.value = models[0].background_color;
            } else {
                modelImage.value = null;
            }
        } catch (error) {
            console.error(error);
        } finally {
            loading.value = false;
        }
    };

    const toggleDropdown = (tagType) => {
        // Check if the dropdown is being closed
        const closingDropdown = showDropdown.value === tagType;

        // Close the currently open dropdown
        showDropdown.value = closingDropdown ? null : tagType;

        // Zoom out before zooming in on another tag
        outZoom.value = true; // Set outZoom to true when closing the dropdown

        // Delay setting zoom values to allow for the zoom-out effect
        setTimeout(() => {
            headZoom.value = showDropdown.value === 'Head'; // Zoom in on 'Head' when its dropdown is shown
            armsZoom.value = showDropdown.value === 'Arms'; // Zoom in on 'Arms' when its dropdown is shown
            outZoom.value = false; // Reset outZoom after the zoom-in effect
        }, closingDropdown ? 170 : 350); // Delay setting zoom values only when closing the dropdown
    };

    // Update selectedFinishTagId when a parent tag is selected
    const selectTag = async (tagType, tag) => {
        const tagId = tagsByType.value.find(t => t.tag === tagType)?.tags.find(t => t.tag === tag)?.id;
        selectedTags.value[tagType] = tag;
        selectedTagId.value = tagId; // Set the selected tag's ID
        console.log(selectedTagId.value);
        // MODAL
        if (selectedTags.value['Finish'] === 'Fabric') {
            if(selectedTags.value['Head'] === 'EVE'){
                showModal.value = true; // Show the modal
                showDropdown.value = null; // Hide the dropdown
                outZoom.value = true; // Set outZoom to true for zoom-out effect
            }
        }
        // FABRIC FILTERS
        if (selectedTags.value['Finish'] === 'Fabric' && selectedTags.value['Head'] === 'EVE') {
            selectedTags.value['Head'] = 'FXE';
        }
        if (selectedTags.value['Finish'] === 'Fabric' && selectedTags.value['Arms'] === 'Regular'){
            selectedTags.value['Arms'] = 'Wooden';
        }
        if (selectedTags.value['Finish'] === 'Fabric' && selectedTags.value['Color'] === 'Grey') {
            selectedTags.value['Color'] = 'White';
        }
        // ZOOM
        if (tagType === 'Head') {
            headZoom.value = true; // Zoom in on the head when 'Head' tag is selected
        }
        else if (tagType === 'Arms') {
            armsZoom.value = true; // Zoom in on the arms when 'Arms' tag is selected
            if (selectedTags.value['Finish'] === 'Fabric'){
                showModal.value = true;
                showDropdown.value = null;
                outZoom.value = true;
            }
        } else {
            headZoom.value = false; // Zoom out for other tags
        }
        fetchTagsByTypePose(); // Fetch tags by type pose
        searchModel(); // Perform model search
    };

    fetchTagsByType();
    fetchTagsByTypePose();

    const closeDropdownOnOutsideClick = (event) => {
        if (!event.target.closest('.dropdown') && !event.target.closest('.selected-tag')) {
            showDropdown.value = null;
            outZoom.value = true;
        }

    };
    onMounted(() => {
        document.body.addEventListener('click', closeDropdownOnOutsideClick);
    });

    onUnmounted(() => {
        document.body.removeEventListener('click', closeDropdownOnOutsideClick);
    });
</script>

<style>
    .app {
        display: flex;
        overflow: hidden; /* Prevent scrolling */
    }
    /* You can define different background colors for different integers */
    .oneBG {
        background-image: linear-gradient(135deg, #718096 0%, #353333 100%);
    }
    .twoBG {
        background-image: linear-gradient(135deg, #8CAEC5 0%, #4A5963 100%);
    }
    .threeBG {
        background-image: linear-gradient(135deg, #deaf84 0%, #000000 100%);
    }

    .model {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        height: 100vh; /* Set height to 100% of the viewport height */
        overflow: hidden; /* Hide overflow to prevent image from stretching */
    }
    .model img {
        width: auto;
        height: 100%; /* Cover the entire container */
    }
    .custom-dropdown {
        position: relative;
    }
    .selected-tag {
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 0.5rem;
        border: 1px solid #ccc;
    }
    .dropdown {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        width: max-content; /* Set the width based on content */
        z-index: 1000;
        border: 1px solid #ccc;
        transform: translateX(-105%); /* Move the dropdown to the left outside */
    }
    .dropdown div {
        padding: 0.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    .dropdown div:hover {
        background-color: #9b9b9b;
        color: white;
    }
    .dropdown img {
        width: auto;
        height: 5rem;
        margin-left: 0.5rem;
        border: #000000 1px solid;
    }
    .selected {
        background-color: #494949; /* Replace #f00 with your desired color */
        color: #fff; /* Text color for selected tag */
    }
    .zoom-head {
        transform: scale(5); /* Zoom factor */
        transform-origin: top; /* Zoom origin from the top */
        transition: transform 0.5s; /* Transition for smooth zoom effect */
        z-index: 10; /* Ensure zoomed head is on top */
    }
    .zoom-arms {
        transform: scale(2.5); /* Zoom factor for arms */
        transform-origin: 85% 30%; /* Zoom origin at the middle right */
        transition: transform 0.5s; /* Transition for smooth zoom effect */
        z-index: 5; /* Ensure zoomed arms are on top */
    }
    .zoom-out {
        transform: scale(1); /* Zoom factor for zoom-out effect */
    }
</style>
