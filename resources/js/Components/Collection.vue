<!-- Collection in collection page -->
<script setup>
import Table from '@/Components/Table.vue';

defineProps({
    canLogout: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <div class="container mx-auto">
        <div class="overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
            <div class="flex items-center mb-2">
                <h1 class="text-2xl font-bold text-black">Collections</h1>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:grid-cols-1">
                <!-- Display the latest collection taking the whole row -->
                <a v-if="tableData_Collection.length > 0" :key="tableData_Collection[0].id" class="lg:col-span-3 bg-gray-100 rounded-md overflow-hidden sm:row-span-2 col-span-2" :style="{ backgroundImage: `url('${getImageUrl(tableData_Collection[0].image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }" :href="`/collection_view/${encodeURIComponent(tableData_Collection[0].id)}`">
                   <h1 class="py-40 lg:text-5xl sm:text-2xl font-semibold bg-opacity-50 text-white lg:py-80 text-center" style="text-shadow: 4px 4px 8px rgb(0, 0, 0);">{{ tableData_Collection[0].name }}</h1>
                   <!-- <h1 class="text-lg font-semibold bg-opacity-50 text-white p-80 sm:p-2 text-center" style="text-shadow: 4px 4px 8px rgb(0, 0, 0);">{{ tableData_Collection[0].name }}</h1> -->
                </a>
                <!-- Display the second latest collection taking two columns -->
                <a v-if="tableData_Collection.length > 1" :key="tableData_Collection[1].id" class="lg:row-span-2 col-span-2 bg-gray-100 rounded-md overflow-hidden" :style="{ backgroundImage: `url('${getImageUrl(tableData_Collection[1].image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }" :href="`/collection_view/${encodeURIComponent(tableData_Collection[1].id)}`">
                    <h1 class="py-40 lg:text-5xl sm:text-2xl font-semibold bg-opacity-50 text-white lg:py-80 text-center" style="text-shadow: 4px 4px 8px rgb(0, 0, 0);">{{ tableData_Collection[1].name }}</h1>
                </a>
                <!-- Display the rest of the collections in the grid -->
                <a v-for="(item, index) in tableData_Collection.slice(2)" :key="item.id" class="lg:sm:col-span-1  bg-gray-100 rounded-md overflow-hidden sm:row-span-2 col-span-2" :style="{ backgroundImage: `url('${getImageUrl(item.image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }" :href="`/collection_view/${encodeURIComponent(item.id)}`">
                    <h1 class="py-40 lg:text-2xl sm:text-2xl font-semibold bg-opacity-50 text-white lg:py-80 text-center" style="text-shadow: 4px 4px 8px rgb(0, 0, 0);">{{ item.name }}</h1>
                </a>
            </div>
        </div>
    </div>
</template>



<script>
import axios from 'axios';

export default {
    components: {
        Table,
    },
    data() {
        return {
            tableHeaders_Collection: [], // Corrected variable name
            tableData_Collection: [],    // Corrected variable name
        };
    },
    mounted() {
        this.fetchData(); // Fetch your data from the server
    },
    methods: {
        fetchData() {
            // Fetch data for collection
            axios.get('/collection')
            .then(response => {
                this.tableHeaders_Collection = Object.keys(response.data[0]);
                this.tableData_Collection = response.data;
            })
            .catch(error => {
                console.error('Error fetching collection data:', error);
            });
        },
        getImageUrl(imagePath) {
            return `/storage/${encodeURIComponent(imagePath).replace(/%2F/g, '/')}`;
        },
    },
};
</script>
