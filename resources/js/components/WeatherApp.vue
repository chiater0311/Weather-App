<template>
    <div class="text-white mb-8 w-full">
        <div class="places-input text-gray-800 w-full flex justify-center">
            <div class="relative">
                <img src="https://ae-nuskin-2022.s3.ap-southeast-1.amazonaws.com/1.%20Registration/input-box.png" class="w-56 h-8">  
                <input placeholder="Enter a city  (press enter)" @keyup.enter="searchCity" type="search" class="absolute text-sm font-medium w-[96%] border-none bg-transparent focus:outline-none focus:ring-0 autofill:!ring-0 autofill:!outline-none top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-[50%] h-[80%] text-center placeholder:text-black">
            </div>
        </div>

        <div v-if="isLoading && searchResult == null && notFoundMsg == null" class="w-full flex justify-center mt-1">
            <Loader />
        </div>
        
        <error-msg v-if="notFoundMsg != null" :errMsg="notFoundMsg" class="w-[10rem] w-full mx-auto mt-3" />

        <div v-if="searchResult != null && notFoundMsg == null" class="search-container flex flex-col mx-auto w-[18rem] min-w-[15rem] lg:w-[28rem] justify-center items-center space-x-3 space-y-2">
            <search-result :result="searchResult" />

            <div class="w-full flex justify-center space-x-4">
                <button @click="addToList" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add this city
                </button>
                <button @click="searchResult = null; errMsg = null; isLoading = false" class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </button>
            </div>
            <error-msg v-if="errMsg != null" :errMsg="errMsg" />
        </div>

        <div class="flex h-content max-w-[634px] xl:max-w-[1280px] 2xl:max-w-[1850px] px-8 mx-auto justify-center flex-wrap gap-4 w-full my-12 cursor-pointer">
            <error-msg v-if="tiles.length < 1" :errMsg="'Search your favourite city and add it in your list!'" class="w-[25rem] w-full mx-auto mt-3 !bg-[#1c212e] rounded" />
            <div v-for="tile in tiles" :key="tile.city" class="weather-container group transform transition duration-300 hover:scale-105 hover:shadow-2xl shadow-gray-900 hover:grayscale-[30%] transition-all duration-300 ease-in-out flex-1 overflow-hidden font-sans min-w-[20rem] lg:min-w-[20rem] 2xl:min-w-[26rem] shadow-lg mt-3 font-sans rounded-xl flex flex-col">
                <button @click="removeTile(tile)" class="absolute right-5 top-3 text-white hidden group-hover:block w-9">
                    <img src="https://ae-demo.s3.ap-southeast-1.amazonaws.com/best-world/icons8-cross-mark-96.png" class="w-full" alt="">
                </button>
                <div class="current-weather flex justify-center items-center px-2 py-4 bg-[#1c212e]">
                    <div class="flex flex-col md:flex-row w-[50%] md:flex-1 justify-center items-center mx-4 space-y-2">
                        <div class="w-full md:w-[45%] flex flex-col space-y-2 ">
                            <div class="text-5xl font-semibold">{{tile.current_temp}}°C</div> 
                            <div class="text-sm md:text-lg">Feels like {{tile.current_feels}}°C</div>
                        </div>

                        <div class="w-full flex flex-col">
                            <div class="text-lg font-semibold">{{tile.current_des.charAt(0).toUpperCase() + tile.current_des.substring(1)}}</div> 
                            <div class="text-xs">{{tile.city}}, {{tile.country}}</div>
                        </div>
                    </div>
                    <div class="md:flex-0 md:w-[30%]">
                        <img :src="`http://openweathermap.org/img/wn/${tile.current_icon}@2x.png`" class="w-24" alt="">
                    </div>
                </div>

                <div class="future-weather text-sm bg-orange-300 px-6 py-8 overflow-hidden">
                    <forecast class="text-[#1c212e] font-semibold" :time="'Morning'" :description="tile.morning_des" :minTemp="tile.morning_min" :maxTemp="tile.morning_max" :icon="tile.morning_icon"/>
                    <forecast class="mt-8 text-[#1c212e] font-semibold" :time="'Afternoon'" :description="tile.afternoon_des" :minTemp="tile.afternoon_min" :maxTemp="tile.afternoon_max" :icon="tile.afternoon_icon"/>
                    <forecast class="mt-8 text-[#1c212e] font-semibold" :time="'Night'" :description="tile.night_des" :minTemp="tile.night_min" :maxTemp="tile.night_max" :icon="tile.night_icon"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Forecast from './Forecast.vue'
import SearchResult from './SearchResult.vue'
import ErrorMsg from './ErrorMsg.vue'
import Loader from './Loader.vue'

const cityName = ref('Johor')
const lon = ref(-79.38323)

let isLoading = ref(false)
let tiles = ref([])

let currentTemp = ref(null)
let currentFeels = ref(null)
let currentIcon = ref(null)
let currentDes = ref(null)
let cod = ref(null)
let searchResult = ref(null)
let notFoundMsg = ref(null)
let errMsg = ref(null)

const fetchData = (cityName) => {
     fetch(`/api/getCurrentWeatherData?cityName=${cityName}`)
    .then(response => response.json())
    .then(async data => {
        cod.value = data.cod;
        if (cod.value == 200) {
            currentTemp.value = Math.round(data.main.temp);
            currentFeels.value = Math.round(data.main.feels_like);
            currentIcon.value = data.weather[0].icon;
            currentDes.value = data.weather[0].description;
            await fetch(`/api/getForecastData?cityName=${cityName}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    let tile = {
                        "city": data.city.name,
                        "country": data.city.country,
                        "current_temp": currentTemp.value,
                        "current_feels": currentFeels.value,
                        "current_des": currentDes.value,
                        "current_icon": currentIcon.value,
                        "morning_des":  data.list[0].weather[0].description,
                        "morning_icon": data.list[0].weather[0].icon,
                        "morning_min":  Math.round(data.list[0].main.temp_min),
                        "morning_max":  Math.round(data.list[0].main.temp_max),
                        "afternoon_des": data.list[2].weather[0].description,
                        "afternoon_icon": data.list[2].weather[0].icon,
                        "afternoon_min": Math.round(data.list[2].main.temp_min),
                        "afternoon_max": Math.round(data.list[2].main.temp_max),
                        "night_des": data.list[4].weather[0].description,
                        "night_icon": data.list[4].weather[0].icon,
                        "night_min": Math.round(data.list[4].main.temp_min),
                        "night_max": Math.round(data.list[4].main.temp_max),
                    }
                    searchResult.value = tile;
                })
        } else {
            notFoundMsg.value = "City not found."
        }
    })
}

const searchCity = (e) => {
    searchResult.value = null;
    fetchData(e.target.value);
    e.target.value = '';
    errMsg.value = null;
    notFoundMsg.value = null;
    isLoading.value = true;
}

const addToList = () => {
    isLoading.value = false;
    let checkIsExist = false;
    tiles.value.filter(data => {
        if(data.city == searchResult.value.city){
            checkIsExist = true;
        }
    });
    if (checkIsExist) {
        errMsg.value = "This city is already in the list."
    }
    else if (tiles.value.length > 2) {
        errMsg.value = "Sorry, you can only have 3 cities in the list."
    }
    else {
        tiles.value.push(searchResult.value);
        searchResult.value = null;
        errMsg.value = null
    }
}

const removeTile = (tile) => {
    console.log(tile.current_temp);
    tiles.value = tiles.value.filter(data => data.city != tile.city);
}

onMounted(() => {
    // fetchData("singapura")
})
</script>
