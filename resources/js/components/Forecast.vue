<template>
    <div class="flex items-center">
        <div class="w-1/6 lg:text-lg">{{time}}</div>
        <div class="w-4/6 px-4 flex items-center">
            <img :src="`http://openweathermap.org/img/wn/${icon}@2x.png`" class="w-14" alt="">
            <div class="ml-4">{{description.charAt(0).toUpperCase() + description.substring(1)}}</div>
        </div>
        <div class="w-1/6 text-right">
            <div>{{minTemp}}°C</div>
            <div>{{maxTemp}}°C</div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps(["time", "description", "maxTemp", "minTemp", "icon"])
</script>