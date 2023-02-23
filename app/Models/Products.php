<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;
    use SoftDeletes;
  protected $table = "products";
  protected $primaryKey = 'uuid';

  /**
     * The "booting" function of model
     *
     * @return void
     */
    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if ( ! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

  public static function getProduct()
  {
    $query = 'SELECT * FROM products WHERE deleted_at IS NULL';

    $data = DB::select($query);

    return $data;
  }

  public function user()
  {
    return $this->belongsTo('App\Models\User', 'uuid');
  }

//   public function image()
//   {
//     return $this->hasMany('App\Models\Reference\Initialization\PasImage', 'product_id');
//   }

  public static function detProduct()
  {
    $query = 'SELECT * FROM products';

    $data = DB::select($query);

    return $data;
  }
}
