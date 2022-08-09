<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoHeader extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = "po_header";
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,"supplier_id");
    }

    public function line()
    {
        return $this->hasMany(PoLine::class);
    }

    public function penerimaan()
    {
        return $this->hasMany(PurchaseInvoiceHeader::class,"poheader_id");
    }
}
