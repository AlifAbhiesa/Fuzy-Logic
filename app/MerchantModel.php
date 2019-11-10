<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantModel extends Model
{
    protected $table = "merchant";

    protected $fillable = ['idUsers','merchantName','longitude','latitude','address','idCategory'];
}
