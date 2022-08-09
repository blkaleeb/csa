<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaran extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = "pengeluaran";
    protected $guarded = [];
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';
    function category()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_pengeluaran_id','id');
    }
    function itemstock()
    {
        return $this->belongsTo(Stok::class, 'itemstock_id','id');
    }
    function inventoris()
    {
        return $this->belongsTo(Inventoris::class, 'inventaris_id','id');
    }
    function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
