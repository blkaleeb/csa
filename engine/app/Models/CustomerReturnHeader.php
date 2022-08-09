<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerReturnHeader extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = "customer_return_header";
    protected $guarded = [];
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';
    public function customer()
    {
        return $this->belongsTo(Konsumen::class, "customer_id");
    }
    public function line()
    {
        return $this->hasMany(CustomerReturnLine::class);
    }

    public function detail()
    {
        return $this->hasMany(CustomerReturnLine::class,'customer_return_header_id', 'id');
    }
}
