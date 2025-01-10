<?php

namespace App\Http\Controllers;
use Inertia\Inertia; //ใส้Inertia
use Illuminate\Http\Request;
//สร้าง php artisan make:controller ProductController -rm
class ProductController extends Controller
{
    private $products = [
        ['id' => 1, 'name' => 'Mofucat', 'description' => 'ตุ๊กตาน้อง Mofusand ในชุดน้องฉลาม ขนาด 24 cm ตุ๊กตาแมวส้ม ตัวนุ่ม น่ารัก น่ากอด ของแท้ นำเข้าจากญี่ปุ่น', 'price' => 400, 'image' => '/images/iy.png'],
        ['id' => 2, 'name' => 'Floppa', 'description' => 'ราคาถูกและส่วนลด น่ารัก Meme 19ซม.3D ขนาดใหญ่สแควร์แมว Plush ยก Floppa เกมตุ๊กตาแมวตุ๊กตาสัตว์ Kawaii Plushie', 'price' => 300, 'image' => '/images/floppa.jpg'],
        ['id' => 3, 'name' => 'Dark Dargon', 'description' => 'ไม่มีฟันสำหรับเต้นรำมีมส์ตุ๊กตาหนานุ่มเต้นรูปมังกรยัดนุ่นรูปสัตว์นุ่มนิ่มขนาด25ซม. หมอนสำหรับตกแต่งห้องเล่นเกมของขวัญสำหรับเด็ก', 'price' => 150, 'image' => '/images/Dragon.jpg'],
        ['id' => 4, 'name' => 'Shiba', 'description' => 'ตุ๊กตาไข่หน้าชิบะขนาด 12 cm ', 'price' => 90, 'image' => '/images/shiba.jpg'],
        ['id' => 5, 'name' => 'Uganda Knuckles', 'description' => 'ตุ๊กตาสุดมีมมของuganda ที่เท่ที่สุด ขนาด 24 cm ', 'price' => 500, 'image' => '/images/Uganda.jpg'],
        ['id' => 6, 'name' => 'Padoru', 'description' => 'ฟิกเกอร์ตัวแทนจากfate ที่เป็นสัญลักของวันchristmas ', 'price' => 700, 'image' => '/images/padoru.jpg'],
        ['id' => 7, 'name' => 'Cry Cat', 'description' => 'ร้องไห้แมว Meme ปลอกหมอนพิมพ์โพลีเอสเตอร์ปลอกหมอนของขวัญหมอน Living Room ซิป45*45ซม.', 'price' => 450, 'image' => '/images/cry cat.jpg'],
        ['id' => 8, 'name' => 'Puppy', 'description' => 'Every thing is fine', 'price' => 2230, 'image' => '/images/Puppy.jpg'],
        ['id' => 9, 'name' => 'Banana Cat', 'description' => 'Meme Cat ตุ๊กตาแมวน่ารัก ขนปุย 11.8 นิ้ว เสียงน่าสนใจ', 'price' => 600, 'image' => '/images/banana.jpg'],
        ['id' => 10, 'name' => 'Mouse Meme', 'description' => 'หนูแฮมสเตอร์ของเล่นตุ๊กตาสบายหนูแฮมสเตอร์ออกแบบนุ่ม น่ากอด ของเล่น น่ารัก สัตว์ ตุ๊กตา เศร้าหนูแฮมสเตอร์สําหรับเด็กเด็กผู้หญิงเด็ก', 'price' => 120, 'image' => '/images/mouse.jpg'],
    ];
    //
    public function index()
    {
        return Inertia::render('Products/Index', ['products' => $this->products]);  //use Inertia\Inertia; จำเป็น
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = collect($this->products)->firstWhere('id', $id);//จะเป็นการโชว์productของไอดีถ้าไม่มีจะขึ้น404แต่ถ้ามีจะrenderแล้วเข้าไปที่หน้าshowได้

        if (!$product) {
            abort(404, 'Product not found');
        }

        return Inertia::render('Products/Show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
