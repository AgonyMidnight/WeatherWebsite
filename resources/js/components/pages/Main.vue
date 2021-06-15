<template>
    <div>
        <div>
            <span>Your town: {{city}}</span>
        </div>
        <br>
        <div>
            Map:
            <Map></Map>
            <hr>
        </div>
        <br>
        <div>
            <img v-if="mapImg" :src="mapImg" alt="">
            {{mapImg}}
        </div>
        <br>
        <div>
            <h3> {{weather.main}}</h3>
            <span> Temperature: {{weather.temp}}</span>
            <span> Feels like: {{weather.feels_like}}</span>
            <span> Humidity: {{weather.humidity}}</span>
            <span> Pressure {{weather.pressure}}</span>
            <span v-if="weather.rain" > Precipitation: Rain</span>
            <span v-if="weather.snow">Precipitation: Snow </span>
            <span> {{weather.wind_deg}}</span>
            <span> {{weather.wind_speed}}</span>
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
        mapImg: "",
        layer: ["temp_new", "clouds_new", "precipitation_new", "pressure_new", "wind_new"],
        latitude : 0,
        longitude: 0,
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
      }
    },
    created: function(){
      this.axios.get(this.apiIp).then((response) => {
        this.city = response.data.city
        this.latitude = response.data.latitude
        this.longitude = response.data.longitude
        this.axios.post('api/get/weather',
          {'city':this.city,
                'latitude': this.latitude,
                'longitude': this.longitude})
          .then((response) => {
            this.weather = response.data.data
            this.mapImg = ""
            //this.mapImg = "https://tile.openweathermap.org/map/temp_new/5/47/38.png?appid=c3f93d23f4afc931f07743b1f8a9ffc6"
          console.log(response.data)
          //this.showWeather(response.data.data)

        })
      })

    },
    methods:{

    }
  }
</script>

<style scoped>
span {
    display: block;
}
</style>