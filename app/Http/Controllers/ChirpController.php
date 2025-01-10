<?php

namespace App\Http\Controllers;
                                            //models=แทนโครสร้างทุกอย่างของtable 
                                            //migrations = การสร้างตารางขึ้น ข้อดีคือเราก็อปปี้ไปดันเครื่องอื่่นได้ถ้าอยากได้databases ให้ลงmagratใหม่จะได้เป็นข้อมูลเดียวกัน
use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */                // โดยทำการรีเทิร์นข้อความที่เราพิมพ์หรือนำมาแสดงที่หน้าchirpsพร้อมuserที่กำลังล็อกอินโดยแสดงเป็นข้อมูลล่าสุด
    public function index(): Response { //index คือหน้าเว็บ
        return Inertia::render('Chirps/Index', [  //มาreturnของหน้าที่เป็นfront-end 
            'chirps' => Chirp::with('user:id,name')->latest()->get(), //เอาข้อมูลที่ผู้ใช้แต่ละคนโพสต์ขึ้นไป renderหน้าจอไปด้วยแต่ขอให้ข้อมูลจากฐานข้อมูลขึ้นมาด้วย   (with('user:id,name') ประมาณ where userid)  {Chirp::with('user:id,name')chirpมาจากmodul ให้ไปlinkกับuserมาด้วยแต่มันยังไม่ได้ผูกกับใครมันก็เลยเห็นเป็นชื่อของเรา }
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()    //ควบคุมcreate
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse //ควบคุมstore
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);     //ตรวจสอบฝั่งback-end fornt-endก็ตรวจว่ามีอะไรเข้ามา
 
        $request->user()->chirps()->create($validated); //สั่งให้create ผ่านvalidatedเรียบร้อย insert into table
 
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)      //show
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)      //แก้ไข
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse    
    {
        Gate::authorize('update', $chirp); //Gateทำงานร่วมกับauthorize
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $chirp->update($validated); //การสั่งทำการแก้ไขหรือupdate   (เอาข้อมูลที่ผ่านvalidatedเอาไปแก้ไข)
 
        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);
 
        $chirp->delete();  //ลบข้อความ
 
        return redirect(route('chirps.index'));
    }
}
//สแตนดาดที่ให้มามันจะเปิดใช้งานเฉพาะตัวที่ใช้
//