<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;

class BlackListApplicant extends Model
{

    protected $table = 'lista_negra';
    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $dateFormat = 'U';
    protected $dates = ['id_empresa', 'id_candidato'];

}
