<?php

namespace App\Models;
use App\Http\Controllers\ListingController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;
    //protected $fillable=['title','company','location','website','email','description','tags'];


    public function scopeFilter($qurey, array $filters){

       if($filters['tag'] ?? false){
        $qurey->where('tags','like','%'.request('tag').'%');
       }
       if($filters['search'] ?? false){
        $qurey->where('title','like','%'.request('search').'%')
        ->orwhere('description','like','%'.request('search').'%')
        ->orwhere('tags','like','%'.request('search').'%')
        ;
       }
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');

    }



}
