<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
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
         // ดึงข้อมูลแผนกทั้งหมดจากฐานข้อมูล โดยเลือกเฉพาะ 'dept_no' และ 'dept_name'
        $departments = DB::table('departments')->select('dept_no', 'dept_name')->get();
        // ส่งข้อมูลแผนกไปยังหน้า 'Employee/Create' ผ่าน Inertia.js
        return inertia('Employee/Create', ['departments' => $departments]);
    }
    public function store(Request $request)
    {
    // ตรวจสอบความถูกต้องของข้อมูลที่ผู้ใช้กรอกในฟอร์ม
    $validated = $request->validate([
        "birth_date" => "required|date",                // ต้องกรอกและเป็นวันที่
        "first_name" => "required|string|max:255",     // ชื่อ ต้องกรอก เป็นข้อความ และยาวไม่เกิน 255 ตัวอักษร
        "last_name"  => "required|string|max:255",     // นามสกุล ต้องกรอก เป็นข้อความ และยาวไม่เกิน 255 ตัวอักษร
        "gender"     => "required|in:M,F",             // เพศ ต้องกรอกและมีค่าเป็น M หรือ F เท่านั้น
        "hire_date"  => "required|date",               // วันที่จ้างงาน ต้องกรอกและเป็นวันที่
        "photo"      => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048" // รูปภาพ ไม่จำเป็นต้องกรอก แต่ต้องเป็นไฟล์รูปภาพ และขนาดไม่เกิน 2MB
    ]);

    try {
        // เริ่มต้น transaction เพื่อให้การทำงานทั้งหมดเสร็จสมบูรณ์ก่อนบันทึก
        DB::transaction(function () use ($validated, $request) {
            // ดึงหมายเลขพนักงาน (emp_no) ล่าสุดจากฐานข้อมูล
            $latestEmpNo = DB::table('employees')->max('emp_no') ?? 0;
            $newEmpNo = $latestEmpNo + 1; // เพิ่ม 1 เพื่อสร้าง emp_no ใหม่

            // ตรวจสอบว่ามีการอัปโหลดไฟล์รูปภาพหรือไม่
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('employees', 'public'); // เก็บรูปภาพใน storage/public/employees
                $validated['photo'] = $photoPath; // บันทึกเส้นทางของรูปภาพลงในตัวแปร
            }

            // เพิ่มข้อมูลพนักงานลงในฐานข้อมูล
            DB::table("employees")->insert([
                "emp_no"     => $newEmpNo,
                "first_name" => $validated['first_name'],
                "last_name"  => $validated['last_name'],
                "gender"     => $validated['gender'],
                "birth_date" => $validated['birth_date'],
                "hire_date"  => $validated['hire_date'],
                "photo"      => $validated['photo'] ?? null // หากไม่มีรูปภาพ ให้บันทึกเป็น null
            ]);
        });

        // ถ้าสำเร็จ ให้ redirect ไปยังหน้ารายชื่อพนักงานพร้อมแสดงข้อความสำเร็จ
        return Redirect::route('employee.index')->with('success', 'Employee created successfully!');

        } catch (\Exception $e) {
        // หากเกิดข้อผิดพลาด ให้บันทึก log และแสดงข้อความผิดพลาดแก่ผู้ใช้
        Log::error('Error creating employee: ' . $e->getMessage());

        // ส่งกลับไปยังหน้าเดิมพร้อมแสดงข้อความผิดพลาด และคืนค่าข้อมูลที่กรอกไว้
        return Redirect::back()->withErrors(['error' => 'An error occurred while creating employee. Please try again.'])
                            ->withInput();
        }
    }
}
