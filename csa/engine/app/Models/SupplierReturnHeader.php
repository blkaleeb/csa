<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierReturnHeader extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = "supplier_return_header";
    protected $guarded = [];
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';

    public function supplier(){
        return $this->belongsTo(Supplier::class,"supplier_code");
    }

    public function line(){
        return $this->hasMany(SupplierReturnLine::class);
    }

    public function detail(){
        return $this->hasMany(SupplierReturnLine::class);
    }
}
