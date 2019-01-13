<template>
    <div>
        <div id="map"></div>
    </div>
</template>

<script>

    import L from 'leaflet';
    import 'leaflet/dist/leaflet.css';

    export default {

        name: 'project-show',

        props: ['project', 'bbox', 'detections'],
        
        data() {
            return {
                map: null,
            }
        },

        mounted() {
            this.initiateMap()
        },

        methods: {

            initiateMap() {
                var osm = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'});
                var cartodb = L.tileLayer('http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="http://cartodb.com/attributions">CartoDB</a>'});
                var white = L.tileLayer("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQMAAABmvDolAAAAA1BMVEX///+nxBvIAAAAH0lEQVQYGe3BAQ0AAADCIPunfg43YAAAAAAAAAAA5wIhAAAB9aK9BAAAAABJRU5ErkJggg==");
                var lyr = L.tileLayer('./' + this.project.id + '/tiles/{z}/{x}/{y}', {tms: true, maxZoom: 24});

                this.map = L.map('map', {
                    minZoom: 15,
                    maxZoom: 24,
                    layers: [cartodb, lyr]
                });

                var basemaps = {"OpenStreetMap": osm, "CartoDB Positron": cartodb, "Without background": white}
                var overlaymaps = {"Layer": lyr}

                L.control.layers(basemaps, overlaymaps, {collapsed: false}).addTo(this.map);
                
                L.geoJSON(this.bbox);
                this.map.fitBounds(L.geoJSON(this.bbox).getBounds())

                var detectionStyle = {
                    "color": "#ff7800",
                    "weight": 2,
                    "opacity": 0.8,
                    "fillOpacity": 0.0
                };

                let geojson = {
                    type: "FeatureCollection", 
                    features: JSON.parse(this.detections.features)
                }
                
                L.geoJSON(geojson, {
                    style: detectionStyle
                }).addTo(this.map);
            }
        }
        
    }
</script>

<style>
    #map {
        height: 1280px;
        width: 100%;
    }
</style>
