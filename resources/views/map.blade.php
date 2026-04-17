@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Leaflet Draw CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        #map {
            height: 90vh;
            width: 100%;
        }
    </style>
    @endsection


@section('content')
    <!-- Map -->
    <div id="map"></div>

    {{-- Modal Point --}}
    <div class="modal" tabindex="-1" id="modalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('points.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-2">
                        <textarea name="description" class="form-control mb-2"></textarea>
                        <textarea id="geometry_point" name="geometry_point" class="form-control"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Polyline --}}
    <div class="modal" tabindex="-1" id="modalInputPolyline">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('polylines.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-2">
                        <textarea name="description" class="form-control mb-2"></textarea>
                        <textarea id="geometry_polyline" name="geometry_polyline" class="form-control"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Polygon --}}
    <div class="modal" tabindex="-1" id="modalInputPolygon">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('polygons.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-2">
                        <textarea name="description" class="form-control mb-2"></textarea>
                        <textarea id="geometry_polygon" name="geometry_polygon" class="form-control"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- leaflet draw js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

{{-- Terraformer JS --}}
<script src="https://unpkg.com/@terraformer/wkt"></script>

{{-- JQuery Js --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@section('scripts')
<script>
var map = L.map('map').setView([-7.7956, 110.3695], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
}).addTo(map);


/* Digitize Function */
var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

var drawControl = new L.Control.Draw({
    draw: {
        position: 'topleft',
        polyline: true,
        polygon: true,
        rectangle: true,
        circle: false,
        marker: true,
        circlemarker: false
    },
    edit: false
});

map.addControl(drawControl);

map.on('draw:created', function(e) {
    var type = e.layerType,
        layer = e.layer;

    var drawnJSONObject = layer.toGeoJSON();
    var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

    if (type === 'polyline') {
        $('#geometry_polyline').val(objectGeometry);
        $('#modalInputPolyline').modal('show');
        $('#modalInputPolyline').on('hidden.bs.modal', function() {
            location.reload();
        });

    } else if (type === 'polygon' || type === 'rectangle') {

        $('#geometry_polygon').val(objectGeometry);
        $('#modalInputPolygon').modal('show');
        $('#modalInputPolygon').on('hidden.bs.modal', function() {
            location.reload();
        });

    } else if (type === 'marker') {

        $('#geometry_point').val(objectGeometry);
        $('#modalInputPoint').modal('show');
        $('#modalInputPoint').on('hidden.bs.modal', function() {
            location.reload();
        });
    }

    drawnItems.addLayer(layer);
});


// GeoJSON Point
var points = L.geoJSON(null, {
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
            "Deskripsi: " + feature.properties.description + "<br>" +
            "created_at: " + feature.properties.created_at + "<br>" +
            "updated_at: " + feature.properties.updated_at;

        layer.bindPopup(popup_content); // FIX
    },
});
$.getJSON("/api/points", function(data) {
    points.addData(data);
    map.addLayer(points);
});


// GeoJSON Polyline
var polylines = L.geoJSON(null, {
    style: {
        color: "blue",
        weight: 3
    },
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
            "Deskripsi: " + feature.properties.description + "<br>" +
            "created_at: " + feature.properties.created_at + "<br>" +
            "updated_at: " + feature.properties.updated_at;

        layer.bindPopup(popup_content); // FIX
    },
});
$.getJSON("/api/polylines", function(data) {
    polylines.addData(data);
    map.addLayer(polylines);
});


// GeoJSON Polygon
var polygons = L.geoJSON(null, {
    style: {
        color: "green",
        fillColor: "green",
        fillOpacity: 0.5
    },
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
            "Deskripsi: " + feature.properties.description + "<br>" +
            "created_at: " + feature.properties.created_at + "<br>" +
            "updated_at: " + feature.properties.updated_at;

        layer.bindPopup(popup_content); // FIX
    },
});
$.getJSON("/api/polygons", function(data) {
    polygons.addData(data);
    map.addLayer(polygons);
});


// Control Layer
var baseMaps = {};

var overlayMaps = {
    "points": points,
    "polylines": polylines,
    "polygons": polygons,
};

// TAMBAHAN BIAR CONTROL MUNCUL
points.addTo(map);
polylines.addTo(map);
polygons.addTo(map);

var controllayer = L.control.layers(baseMaps, overlayMaps, {
    position: 'topright',
    collapsed: false
});
controllayer.addTo(map);

</script>
@endsection
