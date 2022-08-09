<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoLine extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = "po_line";
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';

    public function header()
    {
        return $this->belongsTo(PoHeader::class,"po_header_id");
    }

    public function purchaseline()
    {
        return $this->hasMany(PurchaseInvoiceLine::class,"po_line_id");
    }

    public function purchaselinev2()
    {
        return $this->hasOne(PurchaseInvoiceLine::class,"po_line_id");
    }
    public function stock()
    {
        return $this->belongsTo(Stok::class,"item_stock_id");
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,"warehouse_id");
    }
}
