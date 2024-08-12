<template>
    <div>
        <Head title="Configure" />
        <div class="app">
            <div class="flex flex-col md:flex-row w-full md:h-screen" :class="{'oneBG': modelBackgroundColor == 1, 'twoBG': modelBackgroundColor == 2, 'threeBG': modelBackgroundColor == 3}">
                <ModelViewer :modelImage="modelImage" :loading="loading" :outZoom="outZoom"
                :class="{ 'zoom-out': outZoom }"/>
            </div>
            <div class="md:w-1/3 md:relative pt-16">
                <div class="w-full">
                    <TagSelector :tagsByType="tagsByType" :selectedTags="selectedTags" :showDropdown="showDropdown" @toggleDropdown="toggleDropdown" @selectTag="selectTag" />
                </div>
                <div>
                    <TagSelector :tagsByType="tagsByTypePose" :selectedTags="selectedTags" :showDropdown="showDropdown" @toggleDropdown="toggleDropdown" @selectTag="selectTag" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Head } from '@inertiajs/vue3';
    import { ref, onMounted, onUnmounted } from 'vue';
    import axios from 'axios';
    import ModelViewer from './ModelViewer.vue';
    import TagSelector from './TagSelector.vue';

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
    const headZoom = ref(false);
    const armsZoom = ref(false);
    const modelBackgroundColor = ref(1);
    const selectedTagId = ref(null);

    const fetchTagsByType = async () => {
        try {
            const response = await axios.get('/tags-by-type');
            tagsByType.value = response.data.map(tag => ({
                tag: tag.tag,
                id: tag.ids.split(',').map(Number)[0],
                tags: tag.names.split(',').map((name, index) => ({ tag: name, id: tag.ids.split(',')[index], image: tag.images.split(',')[index] })),
                images: tag.images.split(',')
            }));
            selectedTagId.value = tagsByType.value[0]?.id;
            searchModel();
            fetchTagsByTypePose();
        } catch (error) {
            console.error(error);
        }
    };

    const fetchTagsByTypePose = async () => {
        try {
            const selectedTagIds = Object.entries(selectedTags.value)
                .map(([tagType, tagName]) => tagsByType.value.find(t => t.tag === tagType)?.tags.find(t => t.tag === tagName)?.id)
                .filter(id => id !== undefined);
            if (selectedTagIds.length > 0) {
                const allTagsByTypePose = [];
                for (const tagId of selectedTagIds) {
                    const response = await axios.get(`/tags-by-type-pose/${tagId}`);
                    const tagsByTypePose = response.data.map(tag => ({
                        tag: tag.tag,
                        tags: tag.names.split(',').map((name, index) => ({ tag: name, image: tag.images.split(',')[index] })),
                        images: tag.images.split(',')
                    }));

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
        const closingDropdown = showDropdown.value === tagType;
        showDropdown.value = closingDropdown ? null : tagType;
        outZoom.value = true;

        setTimeout(() => {
            headZoom.value = showDropdown.value === 'Head';
            armsZoom.value = showDropdown.value === 'Arms';
            outZoom.value = false;
        }, closingDropdown ? 170 : 350);
    };

    const selectTag = async (tagType, tag) => {
        // elle pose to drv
        const femalePosesToChange = ['Elle 1', 'Elle 2', 'Elle 3', 'Elle 4', 'Elle 5', 'Elle 6', 'Elle 7',
        'Elle 8', 'Elle 9', 'Elle 10', 'Elle 11', 'Elle 12', 'Elle 13', 'Elle 14', 'Elle 15', 'Elle 20',
        'Elle 21', 'Elle 22', 'Elle 23', 'Elle 24', 'Elle 25', 'Elle 26'];

        const malePosesToChange = ['DRV 1', 'DRV 2', 'DRV 3', 'DRV 4', 'DRV 5', 'DRV 6', 'DRV 7',
        'DRV 8', 'DRV 9', 'DRV 10', 'DRV 11', 'DRV 12', 'DRV 13', 'DRV 16', 'DRV 17'];

        const tagId = tagsByType.value.find(t => t.tag === tagType)?.tags.find(t => t.tag === tag)?.id;
        selectedTags.value[tagType] = tag;
        selectedTagId.value = tagId;
        if (selectedTags.value['Finish'] === 'Fabric') {
            if (selectedTags.value['Head'] === 'EVE') {
                showDropdown.value = null;
                outZoom.value = true;
            }
        }
        if (selectedTags.value['Gender'] === 'Male' && selectedTags.value['Head'] === 'EVE') {
            selectedTags.value['Head'] = 'ZAC';
        }
        if (selectedTags.value['Gender'] === 'Female' && selectedTags.value['Head'] === 'ZAC') {
            selectedTags.value['Head'] = 'EVE';
        }
        if (selectedTags.value['Gender'] === 'Male' && femalePosesToChange.includes(selectedTags.value['Pose'])) {
            console.log('Pose changed to DRV 17');
            selectedTags.value['Pose'] = 'DRV 17';
        }
        if (selectedTags.value['Gender'] === 'Female' && malePosesToChange.includes(selectedTags.value['Pose'])) {
            console.log('Pose changed to Elle 6');
            selectedTags.value['Pose'] = 'Elle 6';
        }
        if (selectedTags.value['Finish'] === 'Fabric' && selectedTags.value['Head'] === 'EVE') {
            selectedTags.value['Head'] = 'FXE';
        }
        if (selectedTags.value['Finish'] === 'Fabric' && selectedTags.value['Arms'] === 'Regular') {
            selectedTags.value['Arms'] = 'Wooden';
        }
        if (selectedTags.value['Finish'] === 'Fabric' && selectedTags.value['Color'] === 'Grey') {
            selectedTags.value['Color'] = 'White';
        }
        if (tagType === 'Head') {
            headZoom.value = true;
        } else if (tagType === 'Arms') {
            armsZoom.value = true;
            if (selectedTags.value['Finish'] === 'Fabric') {
                showDropdown.value = null;
                outZoom.value = true;
            }
        } else {
            headZoom.value = false;
        }
        fetchTagsByTypePose();
        searchModel();
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

<style scoped>
    .app {
        display: flex;
        overflow: hidden;
    }
    .oneBG {
        background-image: linear-gradient(135deg, #718096 0%, #353333 100%);
    }
    .twoBG {
        background-image: linear-gradient(135deg, #8CAEC5 0%, #4A5963 100%);
    }
    .threeBG {
        background-image: linear-gradient(135deg, #deaf84 0%, #000000 100%);
    }
    .bgtag{
        background-color: #ff383800;
    }
    .zoom-out {
        transform: scale(1); /* Zoom factor for zoom-out effect */
    }

</style>
