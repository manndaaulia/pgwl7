@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    #map {
    height: 100vh;
    width: 100%;
    }
</style>
@endsection

@section('content')
    <div id="map"></div>

    {{-- Modal Form Input untuk Point --}}
    <div class="modal" tabindex="-1" id="modalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_point" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name_point" name="name" placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description_point" class="form-label">Description</label>
                            <textarea class="form-control" id="description_point" name="description" rows="3"></textarea>
                        </div>

                        <!-- FIX: hidden geometry -->
                        <input type="hidden" id="geometry_point" name="geometry_point">

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            </div>
                                <div class ="mb-3">
                                    <img src="" alt="" id="preview-image-point" class="img-thumbnail"
                                    width="400">
                                </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input untuk Polylines --}}
    <div class="modal" tabindex="-1" id="modalInputpolylines">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polylines</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polylines.store') }}" method="post" enctype="multipart/form-data">

                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_polylines" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name_polylines" name="name" placeholder="Enter name">
                        </div>

                        <!-- FIX: tambah description -->
                        <div class="mb-3">
                            <label for="description_polylines" class="form-label">Description</label>
                            <textarea class="form-control" id="description_polylines" name="description"></textarea>
                        </div>

                        <!-- FIX: hidden geometry -->
                        <input type="hidden" id="geometry_polylines" name="geometry_polylines">

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image-polylines').src = window.URL.createObjectURL(this.files[0])">
                            </div>
                                <div class ="mb-3">
                                    <img src="" alt="" id="preview-image-polylines" class="img-thumbnail"
                                    width="400">
                                </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input untuk Polygon --}}
<div class="modal" tabindex="-1" id="modalInputPolygon">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Polygon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('polygons.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name_polygon" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name_polygon" name="name" placeholder="Enter name">
                    </div>

                    <div class="mb-3">
                        <label for="description_polygon" class="form-label">Description</label>
                        <textarea class="form-control" id="description_polygon" name="description"></textarea>
                    </div>

                    <!-- hidden geometry -->
                    <input type="hidden" id="geometry_polygon" name="geometry_polygon">

                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input class="form-control" type="file" id="image" name="image"
                            onchange="document.getElementById('preview-image-polygons').src = window.URL.createObjectURL(this.files[0])">
                    </div>

                    <div class="mb-3">
                        <img src="" alt="" id="preview-image-polygons" class="img-thumbnail" width="400">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        var map = L.map('map').setView([-7.7956, 110.3695], 10);

        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            position: 'topleft',
            draw: {
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
                $('#geometry_polylines').val(objectGeometry);
                $('#modalInputpolylines').modal('show');
                $('#modalInputpolylines').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputpolylines').find('form')[0].reset();
                });
            } else if (type === 'polygon' || type === 'rectangle') {
                $('#geometry_polygon').val(objectGeometry);
                $('#modalInputPolygon').modal('show');
                $('#modalInputPolygon').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputPolygon').find('form')[0].reset();
                });
            } else if (type === 'marker') {
                $('#geometry_point').val(objectGeometry);
                $('#modalInputPoint').modal('show');
                $('#modalInputPoint').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputPoint').find('form')[0].reset();
                });
            }

            drawnItems.addLayer(layer);
        });

        var satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
        );

        var baseMaps = {
            "OpenStreetMap": osm,
            "Satellite": satellite
        };

        L.control.layers(baseMaps).addTo(map);

        // GeoJSON Points
var points = L.geoJSON(null, {
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
            "Deskripsi: " + feature.properties.description + "<br>" +
            "Dibuat: " + feature.properties.created_at +
            (feature.properties.image
                ? "<br><img src='{{ asset('storage') }}/" + feature.properties.image + "' alt='Image Point' class='img-thumbnail' width='100'>"
                : "");
        layer.bindPopup(popup_content);
    }
});

$.getJSON("{{ route('geojson.points') }}", function(data) {
    points.addData(data);
    map.addLayer(points);
});

// GeoJSON Polylines
var polylines = L.geoJSON(null, {
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
            "Deskripsi: " + feature.properties.description + "<br>" +
            "Dibuat: " + feature.properties.created_at +
            (feature.properties.image
                ? "<br><img src='{{ asset('storage') }}/" + feature.properties.image + "' alt='Image Polylines' class='img-thumbnail' width='100'>"
                : "");
        layer.bindPopup(popup_content);
    }
});

$.getJSON("{{ route('geojson.polylines') }}", function(data) {
    polylines.addData(data);
    map.addLayer(polylines);
});

// GeoJSON Polygons
var polygons = L.geoJSON(null, {
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
            "Deskripsi: " + feature.properties.description + "<br>" +
            "Dibuat: " + feature.properties.created_at +
            (feature.properties.image
                ? "<br><img src='{{ asset('storage') }}/" + feature.properties.image + "' alt='Image Polygons' class='img-thumbnail' width='100'>"
                : "");
        layer.bindPopup(popup_content);
    }
});

$.getJSON("{{ route('geojson.polygons') }}", function(data) {
    polygons.addData(data);
    map.addLayer(polygons);
});
    </script>
@endsection
