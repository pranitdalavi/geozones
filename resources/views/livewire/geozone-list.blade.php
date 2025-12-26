<div class="container" style="margin-top: 20px; max-width: 1000px; margin-left: auto; margin-right: auto;">
  
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Geozones List</h1>
        <a href="{{ route('geozones.create') }}" class="btn btn-success" style="padding: 8px 16px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px;">
            Create New Geozone
        </a>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        
        <!-- Search Bar -->
        <div class="form-group" style="flex: 1;">
            <label for="search">Search by Name:</label>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="nameSearch" 
                class="form-control" 
                placeholder="Start typing to search..."
                style="width: 100%; padding: 8px;"
            >
        </div>

        <!-- Category Filter -->
        <div class="form-group" style="flex: 1;">
            <label for="category">Filter by Category:</label>
            <select wire:model.live="categoryFilter" class="form-control" style="width: 100%; padding: 8px;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Display Geozones -->
    <div class="geozone-list">
        @if($geozones->isEmpty())
            <p>No geozones found matching your criteria.</p>
        @else
            <ul style="list-style: none; padding: 0;">
                @foreach($geozones as $geozone)
                    <li class="geozone-item" style="padding: 15px; border: 1px solid #ddd; margin-bottom: 10px; border-radius: 4px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="font-size: 1.1em;">{{ $geozone->name }}</strong><br>
                                <span style="color: #666;">Category: {{ $geozone->category->name ?? 'N/A' }}</span>
                            </div>
                            <a href="{{ route('geozones.show', $geozone->id) }}" style="text-decoration: none; color: #007bff;">View Details</a>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div style="margin-top: 20px;">
                {{ $geozones->links() }}
            </div>
        @endif
    </div>
</div>
