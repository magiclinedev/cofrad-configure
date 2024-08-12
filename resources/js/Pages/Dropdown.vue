<template>
    <div class="relative mb-4 h-100">
        <div class="selected-tag py-4 px-4 bg-gray-200 text-black rounded cursor-pointer mt-10"
             @click="toggleDropdown(tagType)">
            <div class="flex justify-between items-center">
                <span>{{ selectedTags[tagType] || tagType }}</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                     :class="{'rotate-180': showDropdown === tagType}">
                    <path fill-rule="evenodd"
                          d="M10 14.5l-5-5h10l-5 5z"
                          clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div v-if="showDropdown === tagType" class="dropdown mt-2 bg-white border border-gray-300 rounded shadow-lg z-10 overflow-x-auto">
            <div class="flex gap-2 p-2">
                <div v-for="tag in tags" :key="tag.tag" class="cursor-pointer" @click="selectTag(tagType, tag.tag)">
                    <div class="w-32 h-92 overflow-hidden">
                        <img :src="'storage/' + tag.image" alt="" class="w-fit h-44 object-cover rounded" />
                    </div>
                    <div class="text-center mt-1">{{ tag.tag }}</div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        tagType: String,
        tags: Array,
        images: Array,
        selectedTags: Object,
        showDropdown: String,
    });

    const emits = defineEmits(['toggleDropdown', 'selectTag']);

    const toggleDropdown = () => {
        emits('toggleDropdown', props.tagType);
    };

    const selectTag = (tagType, tag) => {
        emits('selectTag', tagType, tag);
    };
</script>

<style scoped>
    .selected-tag {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .dropdown {
        max-height: 600px;
        max-width: 450px;
        overflow-y: auto;
    }
</style>
