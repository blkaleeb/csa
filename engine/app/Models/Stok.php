<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stok extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = "item_stock";
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
    public function gudang()
    {
        return $this->belongsTo(Warehouse::class, "warehouse_id");
    }
    public function category()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(Merk::class, 'brand_id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
