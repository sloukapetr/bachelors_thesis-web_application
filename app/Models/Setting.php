<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*public function getSetting($name)
    {
        if (!empty($this->where('name', $name)->first()->value)) {
            return $this->where('name', $name)->first()->value;
        } else {
            return __('Chyba!');
        }
    }*/

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getValue()
    {
        return $this->value;
    }
}
