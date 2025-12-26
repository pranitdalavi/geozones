<div>
    <div id="map" wire:ignore style="height: 500px; width: 100%;"></div>
    
    <div class="mt-3">
        <label for="name">Geozone Name</label>
        <input type="text" wire:model="name" class="form-control" placeholder="Enter Geozone name">
        @error('name') <div class="error-message text-danger">{{ $message }}</div> @enderror

        <select wire:model="category_id" class="form-control mt-2">
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="error-message text-danger">{{ $message }}</div> @enderror

        <div class="mt-3">
        <div style="margin-top: 15px; display: flex; gap: 10px;">
            <button class="btn btn-primary" id="savePolygonButton" wire:click="save"> 
                Save Geozone
            </button>
            
            <a href="{{ route('geozones.index') }}" class="btn btn-secondary" style="padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                Back
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        //Initialize the map
        var map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: { polygon: true, rectangle: true, circle: false, polyline: false, marker: false },
            edit: { featureGroup: drawnItems }
        });
        map.addControl(drawControl);

        //existing polygons if any
        @if($polygonData && isset($polygonData->coordinates))

            var existingCoords = {!! json_encode($polygonData->coordinates) !!};

            existingCoords.forEach(function(poly) {
                L.polygon(poly).addTo(drawnItems);
            });

            //Auto-zoom to fit existing shapes
            if (drawnItems.getLayers().length > 0) {
                map.fitBounds(drawnItems.getBounds());
            }

        @endif

        map.on('draw:created', function(e) {
            drawnItems.addLayer(e.layer);
        });

        document.querySelector('#savePolygonButton').addEventListener('click', function() {
            var allPolygons = [];
            drawnItems.eachLayer(function(layer) {
                if (layer instanceof L.Polygon) {
                    var latlngs = layer.getLatLngs();
                    var coords = Array.isArray(latlngs[0]) ? latlngs[0] : latlngs;
                    var formatted = coords.map(ll => [ll.lat, ll.lng]);
                    allPolygons.push(formatted);
                }
            });

            if (allPolygons.length === 0) {
                alert("Please draw a polygon first.");
                return;
            }

            Livewire.dispatch('savePolygonData', { coordinates: allPolygons });
        });
    });
</script>