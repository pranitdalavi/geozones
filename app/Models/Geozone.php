<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Geozone extends Model
{
    protected $fillable = ['name', 'category_id', 'geometry'];

    /**
     * Boot method to automatically handle geometry before saving
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($geozone) {
            // Ensure geometry is stored as MultiPolygon
            if ($geozone->geometry) {
                // If the geometry is a single polygon, convert it to multipolygon
                if (!$geozone->isMultiPolygon($geozone->geometry)) {
                    $geozone->geometry = DB::raw('ST_GeomFromText(ST_AsText(?))', [$geozone->geometry->toWkt()]);
                }
            }

            // Validate geometry using PostGIS ST_IsValid
            $isValid = DB::selectOne("SELECT ST_IsValid(?) AS is_valid", [$geozone->geometry]);
            if (!$isValid->is_valid) {
                // Optionally repair invalid geometry using ST_MakeValid
                $geozone->geometry = DB::selectOne("SELECT ST_MakeValid(?) AS repaired_geometry", [$geozone->geometry])->repaired_geometry;
            }
        });
    }

    /**
     * Check if the geometry is a MultiPolygon
     * @param  mixed $geometry
     * @return bool
     */
    private function isMultiPolygon($geometry)
    {
        // Use PostGIS functions to check if geometry is MultiPolygon
        return DB::selectOne("SELECT ST_GeometryType(?) AS type", [$geometry])->type === 'ST_MultiPolygon';
    }

    /**
     * Relationship to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
