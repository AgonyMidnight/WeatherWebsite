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
      this.google.maps.event.addListener(this.map, 'idle', this.checkIfDataRequested);
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
        this.gettingData = true;
        let requestString = "http://api.openweathermap.org/data/2.5/box/city?bbox="
          + westLng + "," + northLat + "," //left top
          + eastLng + "," + southLat + "," //right bottom
          + this.map.getZoom()
          + "&cluster=yes&format=json"
          + "&APPID=" + this.apiOpenWeather;
        this.request = new XMLHttpRequest();
        this.request.onload = this.proccessResults;
        this.request.open("get", requestString, true);
        this.request.send();
      },
      proccessResults() {
        let results = JSON.parse(this.request.responseText);
        if (results.list && results.list.length > 0) {
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
