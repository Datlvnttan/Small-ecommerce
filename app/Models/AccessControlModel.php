<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccessControlModel extends Model
{
    use HasFactory;
    private $access = null;
    public function getModelNameInLowerCase()
    {
        $modelName = class_basename($this);
        return strtolower($modelName);
    }
    public function checkAccess()
    {
        if(!isset($this->access))
        {
            if(Auth::check())
            {
                $user = Auth::user();
                $fkAuth = isset($this->fkAuth) ? $this->fkAuth : 'user_id'; 
                if($this->getAttribute($fkAuth) == $user->getKey())
                {
                    $this->access = true;
                }
            }
            else{
                $this->access = false;
            } 
        }
        return $this->access;
        
    }
    protected $isMasked = false;
    public function setMasked($isMasked = false)
    {
        $this->isMasked = $isMasked;
    }
}
