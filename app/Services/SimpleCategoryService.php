<?php

namespace App\Services;

use App\Http\Controllers\Web\CategoryController;
use Illuminate\Support\Facades\Session;

class SimpleCategoryService
{
    private static $categories = [];
    private static $nextId = 1;
    
    public static function getAll()
    {
        return collect(static::$categories)->map(function($category) {
            return (object)$category;
        });
    }
    
    public static function findById($id)
    {
        $category = collect(static::$categories)->firstWhere('id', $id);
        return $category ? (object)$category : null;
    }
    
    public static function create($data)
    {
        $category = [
            'id' => static::$nextId++,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
            'products_count' => 0
        ];
        
        static::$categories[] = $category;
        return (object)$category;
    }
    
    public static function update($id, $data)
    {
        $index = collect(static::$categories)->search(function($category) use ($id) {
            return $category['id'] == $id;
        });
        
        if ($index !== false) {
            static::$categories[$index] = array_merge(static::$categories[$index], $data, [
                'updated_at' => now()
            ]);
            return (object)static::$categories[$index];
        }
        
        return null;
    }
    
    public static function delete($id)
    {
        $index = collect(static::$categories)->search(function($category) use ($id) {
            return $category['id'] == $id;
        });
        
        if ($index !== false) {
            unset(static::$categories[$index]);
            static::$categories = array_values(static::$categories);
            return true;
        }
        
        return false;
    }
    
    public static function getParents()
    {
        return collect(static::$categories)->where('parent_id', null)->map(function($category) {
            return (object)$category;
        })->pluck('name', 'id');
    }
    
    public static function getChildren($parentId)
    {
        return collect(static::$categories)->where('parent_id', $parentId)->map(function($category) {
            return (object)$category;
        });
    }
    
    public static function hasProducts($id)
    {
        // Simuler - dans une vraie application, vérifierait la base de données
        return false;
    }
    
    public static function hasChildren($id)
    {
        return collect(static::$categories)->where('parent_id', $id)->count() > 0;
    }
    
    public static function initializeWithDefaults()
    {
        if (empty(static::$categories)) {
            static::$categories = [
                [
                    'id' => 1,
                    'name' => 'Informatique',
                    'description' => 'Ordinateurs, écrans, périphériques',
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'products_count' => 0
                ],
                [
                    'id' => 2,
                    'name' => 'Bureau',
                    'description' => 'Fournitures de bureau',
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'products_count' => 0
                ],
                [
                    'id' => 3,
                    'name' => 'Logiciel',
                    'description' => 'Licences et logiciels',
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'products_count' => 0
                ]
            ];
            static::$nextId = 4;
        }
    }
}
