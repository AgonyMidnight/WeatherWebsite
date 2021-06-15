import colorPalette from "./colorPalette";

const {
  COLOR_LANDSCAPE,
  COLOR_BORDERS,
  COLOR_WATER,
  COLOR_LINE,
  COLOR_POINT_FILL,
  COLOR_SELECTED_POINT
} = colorPalette;

const COLORS = {
  BORDERS: COLOR_BORDERS,
  LANDSCAPE: COLOR_LANDSCAPE,
  LINE: COLOR_LINE,
  POINT: COLOR_SELECTED_POINT,
  POINT_FILL: COLOR_POINT_FILL,
  WATER: COLOR_WATER
};


const mapSettings = {
  clickableIcons: false,
  streetViewControl: false,
  panControlOptions: false,
  gestureHandling: "cooperative",
  backgroundColor: COLORS.LANDSCAPE,
  mapTypeControl: false,
  zoomControlOptions: {
    style: "SMALL"
  },
  zoom: 10,
  minZoom: 10,
  maxZoom: 15,
  styles: [
    {
      featureType: "landscape",
      stylers: [
        { hue: COLORS.LANDSCAPE },
        { saturation: 50.2 },
        { lightness: -34.8 },
        { gamma: 1 }
      ]
    },
    {
      featureType: "poi",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "road.highway",
      stylers: [
        { hue: COLORS.LANDSCAPE },
        { saturation: -19.8 },
        { lightness: -1.8 },
        { gamma: 1 }
      ]
    },
    {
      featureType: "road.arterial",
      stylers: [
        { hue: COLORS.LANDSCAPE },
        { saturation: 72.4 },
        { lightness: -32.6 },
        { gamma: 1 }
      ]
    },
    {
      featureType: "road.local",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "transit",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "administrative.province",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "administrative.locality",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "administrative.province",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "administrative.land_parcel",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "administrative.neighborhood",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "administrative.country",
      elementType: "geometry.stroke",
      stylers: [{ visibility: "on" }, { color: COLORS.BORDERS }]
    },
    {
      featureType: "administrative",
      elementType: "labels",
      stylers: [{ visibility: "off" }]
    },
    {
      featureType: "water",
      stylers: [
        { hue: COLORS.WATER },
        { saturation: -63.2 },
        { lightness: 38 },
        { gamma: 1 }
      ]
    }
  ]
};

export { mapSettings };
