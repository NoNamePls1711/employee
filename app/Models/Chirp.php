<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
                                            //editว่าให้ใส่ข้อมูลอะไรได้บ้างบางตัวต้องกรอกเข้ามา
class Chirp extends Model
{
    use HasFactory;
 
    protected $fillable = [ //ถ้าไม่ใส่ตัวนี้มันจะinsertเข้ามาไม่ได้ 
        'message',  //ใส่เข้ามาเฉพาะmessage
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); //ขึ้นอยู่กับuser ข้อความใครข้อความมัน
    }
}