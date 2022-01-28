<template>
    <div class="bg-image"
         v-bind:style="{ 'background-image': 'url(https://i.fbcd.co/products/resized/resized-750-500/dc90c6dd7d80b1d49f9ab284933c931534d4ebe432d077ed4431b8a7eb2e1f89.jpg)' }">
        <div class="select-date-section">
            <p class="choose-date-text">Select a date for know shop is open or not</p>
            <Datepicker class="select-date" :enable-time-picker="false" :min-date="today" v-model="date"
                        @update:modelValue="checkStatus"></Datepicker>
            <small v-if="checkStatusError" class="error">{{ checkStatusError }}</small>
            <template v-if="statusForDate !== null">
                <p v-if="statusForDate.isOpen" class="open-day">Bakery is open</p>
                <p v-else class="close-day">Bakery is closed</p>
            </template>
        </div>
        <div class="now-status" v-if="openInfo !== null">
            <div class="status-board" v-if="openInfo.isOpen">
                <p v-if="openInfo.isLunch">We are lunching now.</p>
                <p v-else>Bakery is open now</p>
            </div>
            <div class="status-board" v-else><p>Bakery is closed now</p></div>
        </div>
        <div>
        </div>
        <p v-if="openInfo?.nextOpenTime">{{ openInfo.nextOpenTime }}</p>
    </div>
</template>
<script setup>
import Datepicker from 'vue3-date-time-picker';
import {ref} from 'vue';

const today = (new Date()).toDateString();
let openInfo = ref(null),
    statusForDate = ref(null),
    date = ref(today),
    checkStatusError = ref('');
const checkStatus = () => {
    statusForDate.value = null;
    checkStatusError.value = '';
    if (!date.value) return;
    const dateObject = (new Date(date.value)),
        onlyDate = dateObject.getFullYear() + '-' + (dateObject.getMonth() + 1) + '-' + dateObject.getDate();
    axios.post('/api/check-status', {date: onlyDate}).then(response => {
        statusForDate.value = response.data.data;
    }).catch(error => {
        checkStatusError.value = error.response.data?.error || 'Unknown error.';
    });
};

axios.get('/api/open-info')
    .then(response => {
        openInfo.value = response.data.data;
    });
</script>
