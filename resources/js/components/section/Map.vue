<template>
    <GoogleMapLoader
            :mapConfig="mapConfig"
            :apiKey="apiKey"
            class="dd"
    >
    </GoogleMapLoader>
</template>

<script>
  import GoogleMapLoader from "./GoogleMapLoader";

  import { mapSettings } from "./constants/mapSettings";

  export default {
    components: {
      GoogleMapLoader,
    },
    props: {
      coords: Object,
    },
    data() {
      return {
        apiKey: process.env.MIX_GOOGLE_API,
      }
    },

    computed: {
      mapConfig() {
        return {
          ...mapSettings,
          center: { lat: this.coords.lat, lng: this.coords.lng }
        };
      },

    }
  };
</script>

<style scoped>
.dd {
    height: 500px;
}
</style>