<template>
    <div class="md:w-full h-auto text-black px-3">
        <Dropdown
            v-for="tagType in tagsByType"
            :key="tagType.tag"
            :tagType="tagType.tag"
            :tags="tagType.tags"
            :images="tagType.images"
            :selectedTags="selectedTags"
            :showDropdown="showDropdown"
            @toggleDropdown="toggleDropdown"
            @selectTag="selectTag"
        />
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import Dropdown from './Dropdown.vue';

    const props = defineProps({
        tagsByType: Array,
        tagsByTypePose: Array,
        selectedTags: Object,
        showDropdown: String,
    });

    const emits = defineEmits(['toggleDropdown', 'selectTag']);

    const toggleDropdown = (tagType) => {
        emits('toggleDropdown', tagType);
    };

    const selectTag = (tagType, tag) => {
        emits('selectTag', tagType, tag);
    };
</script>
