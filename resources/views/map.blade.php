@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
    <div id="map"></div>

    {{-- Modal Form Input Untuk Point --}}
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
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_point" name="geometry_point" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="mb-3">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail" width="400">
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

    {{-- Modal Form Input Untuk Polyline --}}
    <div class="modal" tabindex="-1" id="modalInputPolyline">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polyline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polylines.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polyline" name="geometry_polyline" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="mb-3">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail" width="400">
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

    {{-- Modal Form Input Untuk Polygon --}}
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
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polygon" name="geometry_polygon" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="mb-3">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail" width="400">
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
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Leaflet Draw JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    {{-- Terraformer JS --}}
    <script src="https://unpkg.com/@terraformer/wkt"></script>

    {{-- JQuery JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            console.log(drawnJSONObject);
            console.log(objectGeometry);

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
            } else {
                console.log('__undefined__');
            }

            drawnItems.addLayer(layer);
        });

        // GeoJSON Points
        var points = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                // Route untuk delete point, gunakan route dengan parameter id
                var routedelete = "{{ route('points.delete', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);
                // Popup content dengan tambahan tombol delete, gunakan form dengan method POST dan tambahkan @method('delete') untuk mengirim request delete
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                "Deskripsi: " + feature.properties.description + "<br>" +
                "Dibuat: " + feature.properties.created_at + "<br>" +
                "Diperbarui: " + feature.properties.updated_at + "<br>" +
                "<img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                "' alt='' class='img-thumbnail' width='300'>" +
                "<br><br>" +
                "<form action='" + routedelete + "' method='post'>" +
                '@csrf' + '@method("delete")' +
                "<button class='btn btn-sm btn-danger' title='Delete Point' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>" +
                "<i class='fa-solid fa-trash' style='color: rgb(255, 255, 255);'></i> Delete" +
                "</button></form>";

layer.bindPopup(popup_content);
            },
        });

        $.getJSON("{{ route('geojson_points') }}", function(data) {
            points.addData(data);
            map.addLayer(points);
        });

        // GeoJSON Polylines
        var polylines = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('polylines.delete', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Dibuat: " + feature.properties.created_at + "<br>" +
                    "Diperbarui: " + feature.properties.updated_at + "<br>" +
                    "<img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                    "' alt='' class='img-thumbnail' width='300'>" +
                    "<br><br>" +
                    "<form action='" + routedelete + "' method='post'>" +
                    '@csrf' + '@method("delete")' +
                    "<button class='btn btn-sm btn-danger' title='Delete Polyline' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>" +
                    "<i class='fa-solid fa-trash' style='color: rgb(255, 255, 255);'></i> Delete" +
                    "</button></form>";

                layer.bindPopup(popup_content);
            },
        });

        $.getJSON("{{ route('geojson_polylines') }}", function(data) {
            polylines.addData(data);
            map.addLayer(polylines);
        });

        // GeoJSON Polygons
        var polygons = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('polygons.delete', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Dibuat: " + feature.properties.created_at + "<br>" +
                    "Diperbarui: " + feature.properties.updated_at + "<br>" +
                    "<img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                    "' alt='' class='img-thumbnail' width='300'>" +
                    "<br><br>" +
                    "<form action='" + routedelete + "' method='post'>" +
                    '@csrf' + '@method("delete")' +
                    "<button class='btn btn-sm btn-danger' title='Delete Polygon' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>" +
                    "<i class='fa-solid fa-trash' style='color: rgb(255, 255, 255);'></i> Delete" +
                    "</button></form>";

                layer.bindPopup(popup_content);
            },
        });

        $.getJSON("{{ route('geojson_polygons') }}", function(data) {
            polygons.addData(data);
            map.addLayer(polygons);
        });

        // Control Layer — taruh paling bawah
        var baseMaps = {};

        var overlayMaps = {
            "Point": points,
            "Polyline": polylines,
            "Polygon": polygons,
        };

        var controllayer = L.control.layers(baseMaps, overlayMaps);
        controllayer.addTo(map);
    </script>
@endsection
