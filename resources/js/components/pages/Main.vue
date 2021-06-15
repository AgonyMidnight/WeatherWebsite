<template>
    <div class="container mt-3">
        <div id="city" @click="chooseCity">
            <span>Your town: <strong>{{city}}</strong></span>
            <div v-if="choose" class="flex align-content-center justify-content-center">
                <input v-model="newCity" placeholder="enter your city" id="inputCity">
                <button @click="getWeather" class="btn btn-primary" id="btnFind">Find</button>
            </div>
        </div>
        <br>
        <div id="weatherMap">
            <Map :coords="{lat:latitude, lng:longitude}" v-if="!update"></Map>
        </div>
        <br>
        <br>
        <div id="weatherList">
            <ul class="list-group">
                <li class="list-group-item"> <h3>{{weather.main}}</h3></li>
                <li class="list-group-item"> Temperature: {{weather.temp}}</li>
                <li class="list-group-item"> Feels like: {{weather.feels_like}}</li>
                <li class="list-group-item"> Humidity: {{weather.humidity}}</li>
                <li class="list-group-item"> Pressure: {{weather.pressure}}</li>
                <li class="list-group-item" v-if="weather.rain"> Precipitation: Rain</li>
                <li class="list-group-item" v-if="weather.snow"> Precipitation: Snow </li>
                <li class="list-group-item"> Wind: {{weather.wind_deg}}</li>
                <li class="list-group-item"> Speed: {{weather.wind_speed}}</li>
            </ul>
        </div>
    </div>
</template>
<script>
  import Map from "../section/Map";

  export default {
    name: "Main",
    components: {'Map': Map},
    data: function () {
      return {
        apiIp: process.env.MIX_API_IP,
        apiGoogle: process.env.MIX_GOOGLE_API,
        ip: "",
        city: "finding",
        newCity: null,
        latitude: 0,
        longitude: 0,
        update: false,
        weatherValue: ['Temperature', 'Feels like', 'Humidity', 'Pressure', 'Precipitation', 'Precipitation', 'Wind', 'Speed'],
        weather: {
          temp: "",
          feels_like: "",
          humidity: "",
          pressure: "",
          rain: "",
          snow: "",
          main: "",
          wind_deg: "",
          wind_speed: "",
        },
        choose: false
        ,
      }
    },
    created: function () {
      this.axios.get(this.apiIp).then((response) => {
        this.city = response.data.city
        this.getWeather()
      })
    },
    methods: {
      chooseCity(){
        this.choose = true
      },
      getWeather(){
        if(this.newCity != null){
          this.city = this.newCity
          this.newCity = null
        }
          this.axios.post('api/get/weather',
            {
              'city': this.city,
            })
            .then((response) => {
              this.weather = response.data.data
              this.latitude = Number(response.data.coords.lat)
              this.longitude = Number(response.data.coords.lon)
              this.update = true;
              this.$nextTick(() => {
                this.update= false;
              });
            })

        }
      }
    }
</script>

<style scoped>
    #inputCity{
        border-radius: 10px;
    }
    #btnFind {


    }
    #weatherList {
        width: 50%;

        min-height: 30%;
    }
    #weatherMap{
        width: 70%;
        min-height: 50%;
    }
    span {
        display: block;
    }
</style>