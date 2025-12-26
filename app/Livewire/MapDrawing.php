<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Geozone;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class MapDrawing extends Component
{
    public $polygonData = null;
    public $name;
    public $category_id;
    public $geozone;

    public $categories;

    //Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id', // Ensure category_id exists in the categories table
        'polygonData' => 'required|array|min:1', // Basic polygon array validation
    ];

    public function mount(Geozone $geozone = null)
    {
        $this->categories = Category::all();
        $this->geozone = $geozone ?? new Geozone(); // Initialize if null

        if ($this->geozone->exists && $this->geozone->geometry) {
            $geometryJson = DB::select('SELECT ST_AsGeoJSON(geometry) as geojson FROM geozones WHERE id = ?', [$this->geozone->id]);
            
            $this->name = $this->geozone->name;
            $this->category_id = $this->geozone->category_id;
            $this->polygonData = json_decode($geometryJson[0]->geojson);
        }
    }

    #[On('savePolygonData')] 
    public function savePolygonData($coordinates)
    {
        // Ensure the coordinates are an array and there are polygons
        if (is_array($coordinates) && count($coordinates) >= 1) {
            $this->polygonData = $coordinates;
        } else {
            $this->addError('polygonData', 'A valid polygon must have at least 1 polygon drawn.');
        }
    }

    public function save()
    {
        $validatedData = $this->validate();
        
        $categoryId = $this->category_id;
        $name = $this->name;

        //Validate and format the polygon data
        if (is_array($this->polygonData) && count($this->polygonData) > 0) {
            $coordinates = $this->polygonData;

            if (count($coordinates) === 1 && is_array($coordinates[0])) {
                $coordinates = [$coordinates];  // Convert single polygon to MultiPolygon
            }

            //Create the GeoJSON structure
            $geometry = [
                'type' => 'MultiPolygon',
                'coordinates' => $coordinates
            ];

            $geometryJson = json_encode($geometry);  // GeoJSON string

        } else {
            $geometryJson = '{"type":"MultiPolygon","coordinates":[]}';
        }

        //Check if Geozone exists
        if ($this->geozone && $this->geozone->exists) {
            $this->geozone->update([
                'name' => $name,
                'category_id' => $categoryId,
                'geometry' => $geometryJson
            ]);

        } else {
            Geozone::create([
                'name' => $name,
                'category_id' => $categoryId,
                'geometry' => $geometryJson
            ]);

        }

        session()->flash('message', 'Geozone saved successfully.');

        return redirect()->route('geozones.index');
    }

    public function render()
    {   
        if ($this->geozone->id) {
            return view('livewire.map-drawing', [
                'polygonData' => $this->polygonData,
            ]);
        }
        return view('livewire.map-drawing');
    }
}
