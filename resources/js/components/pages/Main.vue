<template>
    <div class="container mt-3">
        <div id="error" class="alert alert-danger" v-if="error">
            <span id="errorText" class="text-danger" > {{errorMessage}}</span>
        </div>
        <div id="city" @click="chooseCity">
            <span>Your town: <strong>{{city}}</strong></span>
            <div v-if="choose" class="flex align-content-center justify-content-center">
                <input v-model="newCity" placeholder="enter your city" id="inputCity">
                <button @click="getWeather" class="btn btn-danger" id="btnFind">Find</button>
            </div>
        </div>
        <div id="weatherMap">
            <Map :coords="{lat:latitude, lng:longitude}" v-if="showMap"></Map>
        </div>
        <div id="weatherList" v-if="!error">
            <ul class="list-group">
                <li class="list-group-item"><h3>{{weather.main}}</h3></li>
                <li class="list-group-item"> {{weather.description}}</li>
                <li class="list-group-item"> Temperature: {{weather.temp}}</li>
                <li class="list-group-item"> Feels like: {{weather.feels_like}}</li>
                <li class="list-group-item"> Humidity: {{weather.humidity}}</li>
                <li class="list-group-item"> Pressure: {{weather.pressure}}</li>
                <li class="list-group-item" v-if="weather.rain"> Precipitation: Rain</li>
                <li class="list-group-item" v-if="weather.snow"> Precipitation: Snow</li>
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
        showMap: false,
        weather: {
          temp: "",
          feels_like: "",
          humidity: "",
          pressure: "",
          main: "",
          wind_deg: "",
          wind_speed: "",
          description: ""
        },
        choose: false,
        error: false,
        errorMessage: '',
      }
    },
    created: function () {
      this.axios.get(this.apiIp).then((response) => {
        this.city = response.data.city
        this.getWeather()
      })
    },
    methods: {
      chooseCity() {
        this.choose = true
      },
      getWeather() {
        this.error = false;
        if (this.newCity != null) {
          this.city = this.newCity
          this.newCity = null
        }
        this.axios.post('api/get/weather',
          {
            'city': this.city,
          })
          .then((response) => {
            if (response.data.error) {
              console.log(response.data.error)
              this.error = true;
              this.errorMessage = response.data.error;
              console.log(response.data.error)
              this.resetData();
            } else {
              this.weather = response.data.data
              this.latitude = Number(response.data.coords.lat)
              this.longitude = Number(response.data.coords.lon)
              this.showMap = false;
              this.$nextTick(() => {
                this.showMap = true;
              });
            }
          })
      },
      resetData() {
        this.weather = {};
        this.showMap = false;
      }
    }
  }
</script>

<style scoped>
    #inputCity {
        border-radius: 10px;
        font-size: 20px;
    }

    #city {
        font-size: 30px;
        width: auto;
        height: 20%;
    }

    #city strong, h3 {
        color: unset;
        padding-left: 5px;
    }

    #city span:hover {
        color: #b44f1e !important;
        font-size: 2vw;
    }

    h3 {
        color: unset;
        font-weight: bold;

    }

    #btnFind {
        background-color: #b44f1e;
    }

    .list-group-item {
        background-color: #f5e1bf !important;
        color: #b44f1e !important;
        font-weight: bold;
        padding: 10px;
        letter-spacing: 2px;
        border: 2px solid #d4a752;
    }

    #weatherList {
        border: 2px solid #d4a752;
        font-size: 20px;
        width: 50%;
        height: 30%;
        margin: 20px;
    }

    #weatherMap {
        margin: 15px;
        height: 40%;
        box-shadow: 10px 5px 5px #1a1918;
    }

    span {
        display: block;
    }
</style>