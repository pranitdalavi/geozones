<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Geozone</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

    @livewireStyles
</head>
<body>
    <nav style="margin-bottom: 20px;">
        <a href="{{ route('geozones.index') }}" style="color: #007bff; text-decoration: none;">Back to Geozones List</a>
    </nav>

    <h1>{{ isset($geozone) ? 'Edit Geozone' : 'Create New Geozone' }}</h1>
    <hr>

    @livewire('map-drawing', ['geozone' => $geozone])

    @livewireScripts

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
</body>
</html>
