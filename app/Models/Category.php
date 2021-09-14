<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $connection = 'mysql';

    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'name', 'slug', 'parent_id', 'status', 'description',
    ];

    protected $appends = [
        'original_name'
    ];

    // event when make add or update
    protected static function booted()
    {
        static::creating(function (Category $category)
        {
            $category->slug = Str::slug($category->name);
        });
    }


    // Accessors: get{AttributeName}Attribute
    // Exists Attribute
    // $model->name
    public function getNameAttribute($value)
    {
        if ($this->trashed()) {
            return $value . ' (Deleted)';
        }
        return $value;
    }
    // Non-exists Attribute
    // $model->original_name
    public function getOriginalNameAttribute()
    {
        return $this->attributes['name'];
    }
    //
    public function products(){
        return $this->hasMany(Product::class,'category_id','id');
    }

    public function children()
    {
        return $this->hasMany(Category::class,'parent_id','id');
    }

    public function parent()
    {
            return $this->belongsTo(self::class,'parent_id','id')
            ->withDefault([
                'name' => 'No Parent'
            ]);
    }

    public function toJson($options = 0)
    {
        return json_encode([
            'id' => $this->id,
            'title' => $this->name,
            'sub_category' => $this->children,
        ]);
    }
}
