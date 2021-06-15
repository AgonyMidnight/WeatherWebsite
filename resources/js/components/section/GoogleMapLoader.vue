<template>
    <div>
        <div
                class="google-map"
                ref="googleMap"
        ></div>
        <template v-if="Boolean(this.google) && Boolean(this.map)">
            <slot
                    :google="google"
                    :map="map"
            />
        </template>
    </div>
</template>

<script>
  import GoogleMapsApiLoader from "google-maps-api-loader";

  export default {
    props: {
      mapConfig: Object,
      apiKey: String
    },

    data() {
      return {
        google: null,
        map: null,
        geoJSON: "",
        request: "",
        apiOpenWeather: process.env.MIX_OPENWEATHER_KEY,
        mapOptions: "",
        infoWindow: "",
        gettingData: false,
      };
    },

    async mounted() {
      const googleMapApi = await GoogleMapsApiLoader({
        apiKey: this.apiKey
      });
      this.google = googleMapApi;
      this.initializeMap();
      //this.map = new  this.google.maps.Map(document.querySelector('#map-canvas'), this.mapOptions);
      this.google.maps.event.addListener(this.map, 'idle', this.checkIfDataRequested);
      //this.infoWindow = new  this.google.maps.InfoWindow();
      //this.google.maps.event.addDomListener(window, 'load',  this.initializeMap());
    },

    methods: {
      initializeMap() {
        const mapContainer = this.$refs.googleMap;
        this.map = new this.google.maps.Map(mapContainer, this.mapConfig);
      },
      initialize() {
        this.mapOptions = {
          zoom: 4,
          center: new this.google.maps.LatLng(50, -50)
        }
      },
      moveMaps(event) {
        this.infoWindow.setContent(
          "<img src=" + event.feature.getProperty("icon") + ">"
          + "<br /><strong>" + event.feature.getProperty("city") + "</strong>"
          + "<br />" + event.feature.getProperty("temperature") + "&deg;C"
          + "<br />" + event.feature.getProperty("weather")
        );
        this.infoWindow.setOptions({
          position: {
            lat: event.latLng.lat(),
            lng: event.latLng.lng()
          },
          pixelOffset: {
            width: 0,
            height: -15
          }
        });
        this.infoWindow.open(map);
      },
      checkIfDataRequested() {
        while (this.gettingData === true) {
          this.request.abort();
          this.gettingData = false;
        }
        this.getCoords();
      },
      getCoords() {

        let bounds = this.map.getBounds();

        let NE = bounds.getNorthEast();
        let SW = bounds.getSouthWest();
        this.getWeather(NE.lat(), NE.lng(), SW.lat(), SW.lng());
      },
      getWeather(northLat, eastLng, southLat, westLng) {
        console.log('getWeather');
        this.gettingData = true;
        let requestString = "http://api.openweathermap.org/data/2.5/box/city?bbox="
          + westLng + "," + northLat + "," //left top
          + eastLng + "," + southLat + "," //right bottom
          + this.map.getZoom()
          + "&cluster=yes&format=json"
          + "&APPID=" + this.apiOpenWeather;
        console.log(1)
        this.request = new XMLHttpRequest();
        console.log(this.request)
        console.log(2)
        this.request.onload = this.proccessResults;
        console.log(3)
        this.request.open("get", requestString, true);
        console.log(4)
        this.request.send();
        console.log(5)
      },
      proccessResults() {
        console.log(this)
        let results = JSON.parse(this.request.responseText);
        if (results.list.length > 0) {
          this.resetData();
          for (let i = 0; i < results.list.length; i++) {
            this.geoJSON.features.push(this.jsonToGeoJson(results.list[i]));
          }
          this.drawIcons(this.geoJSON);
        }
      },
      jsonToGeoJson(weatherItem) {
        let feature = {
          type: "Feature",
          properties: {
            city: weatherItem.name,
            weather: weatherItem.weather[0].main,
            temperature: weatherItem.main.temp,
            min: weatherItem.main.temp_min,
            max: weatherItem.main.temp_max,
            humidity: weatherItem.main.humidity,
            pressure: weatherItem.main.pressure,
            windSpeed: weatherItem.wind.speed,
            windDegrees: weatherItem.wind.deg,
            windGust: weatherItem.wind.gust,
            icon: "http://openweathermap.org/img/w/"
              + weatherItem.weather[0].icon + ".png",
            coordinates: [weatherItem.coord.Lon, weatherItem.coord.Lat]
          },
          geometry: {
            type: "Point",
            coordinates: [weatherItem.coord.Lon, weatherItem.coord.Lat]
          }
        }
        this.map.data.setStyle(function(feature) {
          return {
            icon: {
              url: feature.getProperty('icon'),
              anchor: new google.maps.Point(25, 25)
            }
          };
        });

        // returns object
        return feature;
      },
      drawIcons(){
        this.map.data.addGeoJson(this.geoJSON);

        this.gettingData = false;
      },
      resetData(){
        this.geoJSON = {
          type: "FeatureCollection",
          features: []
        };
        console.log(1, this.map.data)
        this.map.data.forEach((feature) => {
          this.map.data.remove(feature);
        });
      }
    }
  };
</script>

<style scoped>
    .google-map {
        width: 100%;
        min-height: 100%;
    }
</style>
