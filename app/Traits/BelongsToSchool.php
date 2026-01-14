<?php

namespace App\Traits;

use App\Models\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToSchool
{
    protected static function bootBelongsToSchool()
    {
        static::creating(function ($model) {
            if (empty($model->school_id) && Auth::check() && Auth::user()->school_id) {
                $model->school_id = Auth::user()->school_id;
            }
        });

        static::addGlobalScope('school', function (Builder $builder) {
            if (Auth::check() && Auth::user()->school_id) {
                $builder->where($builder->getQuery()->from . '.school_id', Auth::user()->school_id);
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
