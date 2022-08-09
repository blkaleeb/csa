<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierReturnLine extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = "supplier_return_line";
    protected $guarded = [];
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';

    public function stock(){
        return $this->belongsTo(Stok::class,"item_stock_id")->withTrashed();
    }
    public function header(){
        return $this->hasOne(SupplierReturnHeader::class,"id","supplier_return_header_id");
    }
}
