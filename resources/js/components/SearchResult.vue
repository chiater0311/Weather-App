<template>
    <div class="flex justify-center items-center px-2 py-4 bg-[#1c212e] w-full rounded-xl mt-5">
        <div class="flex flex-col md:flex-row w-[50%] md:flex-1 justify-center items-center mx-4 space-y-2">
            <div class="w-full md:w-[45%] flex flex-col space-y-2 ">
                <div class="text-5xl font-semibold">{{result.current_temp}}°C</div> 
                <div class="text-sm md:text-lg">Feels like {{result.current_feels}}°C</div>
            </div>

            <div class="w-full flex flex-col md:ml-12">
                <div class="xl:text-lg font-semibold">{{result.current_des.charAt(0).toUpperCase() + result.current_des.substring(1)}}</div> 
                <div class="text-xs">{{result.city}}, {{result.country}}</div>
            </div>
        </div>
        <div class="md:flex-0 md:w-[30%]">
            <img :src="`http://openweathermap.org/img/wn/${result.current_icon}@2x.png`" class="w-24" alt="">
        </div>
    </div>
</template>

<script setup>
const props = defineProps(["result"])
</script>