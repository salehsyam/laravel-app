<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tags(){
        return $this->belongsToMany(Tag::class,'post_tag');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function typeClass():string
    {
        return [
            'published' => 'bg-green-200 text-green-800',
            'draft' => 'bg-yellow-200 text-yellow-800',
            'archived' => 'bg-red-200 text-red-800',
        ][$this->status] ?? 'bg-green-200 text-green-800';


//        switch($this->status){
//            case 'published':
//                return 'bg-green-200 text-green-800';
//                break;
//            case 'draft':
//                return 'bg-yellow-200 text-yellow-800';
//                break;
//            case 'archived':
//                return 'bg-red-200 text-red-800';
//                break;
//        }
    }



}
