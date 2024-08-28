<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class ModelCompoundPrimaryKey extends Model
{
    use HasFactory;

    // Tắt auto-incrementing cho khóa chính phức hợp
    public $incrementing = false;
    // protected $primaryKey = ['product_id', 'image_name'];
    /**
     * Ghi đè phương thức để đặt khóa chính phức hợp
     */
    public function setKeysForSaveQuery($query)
    {
        $primaryKeys = $this->primaryKey;
        foreach ($primaryKeys as $primaryKey) {
            $query = $query->where($primaryKey, '=', $this->getAttribute($primaryKey));
        }
        return $query;
    }
    

}
