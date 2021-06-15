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
    data() {
      return {
        apiKey: process.env.MIX_GOOGLE_API,
      }
    },

    computed: {
      mapConfig() {
        return {
          ...mapSettings,
          center: { lat: 47.23, lng: 38.9 }
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