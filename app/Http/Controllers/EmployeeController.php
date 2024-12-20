<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\EmployeesExport;
use App\Jobs\EmployeesExportJob;
use PDF;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pageTitle = 'Employee List';
        confirmDelete();
        $positions = Position::all();
        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'positions' => $positions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Employee';
        $positions = Position::all();
        return view('employee.create', compact('pageTitle', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'date' => 'Isi :attribute dengan format tanggal yang benar'
        ];

        // Validasi input data
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'birth_date' => 'required|date',  // Ganti 'age' menjadi 'birth_date'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle File Upload
        $file = $request->file('cv');
        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();
            $file->store('public/files');
        }

        // ELOQUENT
        $employee = new Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');  // Format birth_date
        $employee->position_id = $request->position;

        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        Alert::success('Added Successfully', 'Employee Data Added Successfully.');

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Employee Detail';

        // ELOQUENT
        $employee = Employee::find($id);

        // Format birth_date menggunakan Carbon
        $employee->birth_date = Carbon::parse($employee->birth_date)->format('d M Y');  // Format tampilan tanggal

        return view('employee.show', compact('pageTitle', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Employee';
        $positions = Position::all();
        $employee = Employee::find($id);
        return view('employee.edit', compact('pageTitle', 'positions', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'date' => 'Isi :attribute dengan format tanggal yang benar'
        ];

        // Validasi input data
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'birth_date' => 'required|date',  // Ganti 'age' menjadi 'birth_date'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle file upload
        $file = $request->file('cv');
        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();
        }

        // ELOQUENT
        $employee = Employee::find($id);
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');  // Format birth_date
        $employee->position_id = $request->position;

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $file->store('public/files');
            Storage::delete('public/files/'.$employee->encrypted_filename);

            if ($file != null) {
                $employee->original_filename = $originalFilename;
                $employee->encrypted_filename = $encryptedFilename;
            }
        }

        $employee->save();

        Alert::success('Changed Successfully', 'Employee Data Changed Successfully.');

        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        $file = 'public/files/'.$employee->encrypted_filename;

        if (!empty($file)) {
            Storage::delete('/' . $file);
        }

        $employee->delete();

        Alert::success('Deleted Successfully', 'Employee Data Deleted Successfully.');

        return redirect()->route('employees.index');
    }

    /**
     * Download CV file.
     */
    public function downloadFile($employeeId)
    {
        $employee = Employee::find($employeeId);
        $encryptedFilename = 'public/files/'.$employee->encrypted_filename;
        $downloadFilename = Str::lower($employee->firstname.'_'.$employee->lastname.'_cv.pdf');

        if (Storage::exists($encryptedFilename)) {
            return Storage::download($encryptedFilename, $downloadFilename);
        }
    }

    /**
     * Get employee data for DataTables.
     */
    public function getData(Request $request)
    {
        $employees = Employee::with('position')
            ->select('employees.*')
            ->get(); // Ambil data karyawan

        return datatables()->of($employees)
            ->addIndexColumn()  // Menambahkan kolom index
            ->editColumn('birth_date', function($employee) {
                return \Carbon\Carbon::parse($employee->birth_date)->format('Y-m-d'); // Format birth_date yang ingin ditampilkan
            })
            ->addColumn('actions', function($employee) {
                return view('employee.actions', compact('employee')); // Tombol aksi
            })
            ->toJson(); // Kembalikan data dalam format JSON untuk DataTables
    }

    /**
     * Export employees to Excel.
     */
    public function exportExcel()
    {
        EmployeesExport::dispatch();
        return back()->with('success', 'Export to Excel job has been dispatched!');
    }

    /**
     * Export employees to PDF.
     */
    public function exportPdf()
    {
        $employees = Employee::all();
        $pdf = PDF::loadView('employee.export_pdf', compact('employees'));
        return $pdf->download('employees.pdf');
    }

}
