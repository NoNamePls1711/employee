<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {   //รับค่าจากผู้ใช้(search query)
        $query = $request->input('search');

        //รับค่าคอลัมน์ที่ใช้เรียงลำดับ(ค่าเริ่มต้น emp no)
        $sortColumn = $request->input('sortColumn', 'emp_no'); // Default sort column

        //รับค่าลำดับการเรียง (acs หรือ desc , ค่าเริ่มต้น:asc)
        $sortOrder = $request->input('sortOrder', 'acs'); // Default sort order is 'desc'

        // ตรวจสอบว่าที่เรียงคือ 'emp_no' หรือไม่
        if ($sortColumn == 'emp_no') 
        //สลับระหว่าง 'asc' และ 'desc'(toggle order)
        {
            $sortOrder = $sortOrder === 'asc' ? 'desc' : 'asc'; // Toggle the order between 'asc' and 'desc'
        }

        // ดึงข้อมูลพนักงานจากฐานข้อมูล
        $employees = Employee::when($query, function ($queryBuilder, $query) {
            //กรองข้อมูลพนักงานตามชื่อหรือสกุลที่ตรงกับคำค้นหา
            $queryBuilder->where('first_name', 'like', '%' . $query . '%')
                        ->orWhere('emp_no', 'like', '%' . $query . '%')
                        ->orWhere('last_name', 'like', '%' . $query . '%');
        })
                //เรียงลำดับข้อมูลตามคอลัมน์ที่เลือก
            ->orderBy($sortColumn, $sortOrder) // Apply sorting
            ->paginate(10);

        return Inertia::render('Employee/Index', [
            'employees' => $employees,
            'query' => $query,
            'sortColumn' => $sortColumn,
            'sortOrder' => $sortOrder,
        ]);
    }



    public function create()
    {
        // ดึงข้อมูลแผนกจากฐานข้อมูล
        $departments = DB::table('departments')->select('dept_no', 'dept_name')->get();

        return inertia('Employee/Create', ['departments' => $departments]);
    }
    public function store(Request $request)
    {
        // รับข้อมูลจากฟอร์ม พร้อมตรวจสอบความถูกต้อง
        $validated = $request->validate([
            "birth_date" => "required|date",
            "first_name" => "required|string|max:255",
            "last_name"  => "required|string|max:255",
            'gender' => 'required|in:M,F,', 
        ]);

        // ใช้ Database Transaction เพื่อความปลอดภัย
        DB::transaction(function () use ($validated) { 
            // 1. หาค่า emp_no ล่าสุด 
            $latestEmpNo = DB::table('employees')->max('emp_no') ?? 0; 
            $newEmpNo = $latestEmpNo + 1; // เพิ่มค่า emp_no ทีละ 1

            Log::info("New Employee Number: " . $newEmpNo);

            // 2. เพิ่มข้อมูลลงในฐานข้อมูลอย่างถูกต้อง
            DB::table("employees")->insert([
                "emp_no"     => $newEmpNo, 
                "first_name" => $validated['first_name'],
                "last_name"  => $validated['last_name'],
                "gender"     => $validated['gender'],
                "birth_date" => $validated['birth_date'],
            ]);
        });

        // ส่งข้อความตอบกลับเมื่อสำเร็จ
        return response()->json(['message' => 'Employee created successfully']);
    }
}