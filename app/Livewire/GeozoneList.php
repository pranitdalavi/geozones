<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Geozone;
use App\Models\Category;
use Livewire\WithPagination;

class GeozoneList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; 

    public $nameSearch = '';
    public $categoryFilter = '';


    public function render()
    {
        $geozones = Geozone::query()
            ->when($this->nameSearch, function($query) {
                $query->where('name', 'like', '%' . $this->nameSearch . '%');
            })
            ->when($this->categoryFilter, function($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->with('category')
            ->paginate(10);

        return view('livewire.geozone-list', [
            'geozones' => $geozones,
            'categories' => Category::all(),
        ]);
    }
}