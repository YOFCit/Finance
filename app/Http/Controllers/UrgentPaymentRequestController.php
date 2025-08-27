<?php

namespace App\Http\Controllers;

use App\Models\UrgentPaymentRequest;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\NewRequestMail;
use App\Mail\RequestStatusMail;
use App\Models\ApprovalPerson;
use Illuminate\Support\Facades\Mail;
use Psy\Readline\Hoa\Console;

class UrgentPaymentRequestController extends Controller
{
  /** Variable to receive requests via mail */
  public $mail;
  public $requestModel;
  public $empleado;

  public function __construct(UrgentPaymentRequest $requestModel)
  {
    $this->requestModel = $requestModel;
    $this->empleado = Usuarios::where('requestor', $requestModel->requestor)->first();
    $approval = ApprovalPerson::first();
    $this->mail = $approval->mail ?? null;
  }

  public function build()
  {
    return $this->markdown('emails.request-status')
      ->with([
        'requestModel' => $this->requestModel,
        'empleado' => $this->empleado,
      ]);
  }

  /** List requests with filters and pagination */
  public function index(Request $request)
  {
    $query = UrgentPaymentRequest::query();
    if (auth()->check()) {
    } else {
      if ($request->filled('device_token')) {
        $deviceToken = $request->input('device_token');
        $latestRequest = UrgentPaymentRequest::where('device_token', $deviceToken)->latest()->first();
        if ($latestRequest) {
          $query->where('device_token', $deviceToken);
        } else {
          $query->whereRaw('0 = 1');
        }
      } elseif ($request->filled('access_token')) {
        $token = $request->input('access_token');
        $query->where('access_token', $token);
      } else {
        $query->whereRaw('0 = 1');
      }
    }

    // 🔹 Búsqueda global
    if ($request->filled('search')) {
      $search = $request->input('search');
      $query->where(function ($q) use ($search) {
        $q->where('requestor', 'like', "%{$search}%")
          ->orWhere('expense_no', 'like', "%{$search}%")
          ->orWhere('supplier', 'like', "%{$search}%")
          ->orWhere('department', 'like', "%{$search}%")
          ->orWhere('causing_department', 'like', "%{$search}%");
      });
    }
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }
    if ($request->filled('name')) {
      $query->where('name', 'like', '%' . $request->name . '%');
    }
    if ($request->filled('employee')) {
      $query->where('requestor', 'like', '%' . $request->employee . '%');
    }

    if ($request->filled('date_from')) {
      $query->whereDate('request_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
      $query->whereDate('request_date', '<=', $request->date_to);
    }

    $requests = $query->latest()
      ->paginate(6)
      ->appends($request->all());

    return view('Components.Tables.table', [
      'requests' => $requests,
      'auth_status' => auth()->check(),
      'auth_user' => auth()->user()
    ]);
  }
  /** Create a new urgent payment request */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'requestor' => 'required|string|min:1',
      'expense_no' => 'nullable|string',
      'department' => 'required|string|min:1',
      'supplier' => 'required|string|min:1',
      'amount' => 'required|string',
      'currency' => 'required|in:MXN,USD,CNY',
      'payment_due_date' => 'required|date',
      'description' => 'nullable|string',
      'justification' => 'nullable|string',
      'cause' => 'nullable|string',
      'risk' => 'nullable|string',
      'causing_department' => 'nullable|string',
      'signature' => 'nullable|string',
      'device_token' => 'required|string',
    ]);

    $validated['request_date'] = now()->format('Y-m-d');
    $validated['amount'] = (float) str_replace(',', '', $validated['amount']);
    $signaturePath = null;

    if (!empty($validated['signature'])) {
      $signaturePath = $this->saveSignature($validated['signature']);
    }

    // Generar token único
    do {
      $accessToken = Str::random(64);
    } while (UrgentPaymentRequest::where('access_token', $accessToken)->exists());

    // Guardar la solicitud
    $urgentRequest = UrgentPaymentRequest::create([
      'device_token' => $request->device_token,
      'request_date' => $validated['request_date'],
      'requestor' => $validated['requestor'],
      'expense_no' => $validated['expense_no'],
      'department' => $validated['department'],
      'supplier' => $validated['supplier'],
      'amount' => $validated['amount'],
      'currency' => $validated['currency'],
      'payment_due_date' => $validated['payment_due_date'],
      'description' => $validated['description'] ?? null,
      'justification' => $validated['justification'] ?? null,
      'cause' => $validated['cause'] ?? null,
      'risk' => $validated['risk'] ?? null,
      'causing_department' => $validated['causing_department'] ?? null,
      'signature_path' => $signaturePath,
      'access_token' => $accessToken,
    ]);

    $link = route('request.approve', $accessToken);

    try {
      Mail::to($this->mail)
        ->send(new NewRequestMail($urgentRequest, $link));
    } catch (\Exception $e) {
      dd($e->getMessage());
    }

    return redirect()->back()->with('success', 'Request created successfully and email sent.');
  }
  /** Update an existing request */
  public function update(Request $request, $id)
  {
    $requestModel = UrgentPaymentRequest::findOrFail($id);

    $validated = $request->validate([
      'requestor' => 'required|string|min:1',
      'department' => 'required|string|min:1',
      'expense_no' => 'nullable|string|unique:urgent_payment_requests,expense_no',
      'supplier' => 'required|string',
      'amount' => 'required|string',
      'currency' => 'required|in:MXN,USD,CNY',
      'payment_due_date' => 'required|date',
      'description' => 'nullable|string',
      'justification' => 'nullable|string',
      'cause' => 'nullable|string',
      'risk' => 'nullable|string',
      'causing_department' => 'nullable|string',
      'signature' => 'nullable|string',
    ]);

    $validated['request_date'] = $requestModel->request_date;
    $validated['amount'] = (float) str_replace(',', '', $validated['amount']);

    $requestModel->request_date = $validated['request_date'];
    $requestModel->requestor = $validated['requestor'];
    $requestModel->department = $validated['department'];
    $requestModel->expense_no = $validated['expense_no'];
    $requestModel->supplier = $validated['supplier'];
    $requestModel->amount = $validated['amount'];
    $requestModel->currency = $validated['currency'];
    $requestModel->payment_due_date = $validated['payment_due_date'];
    $requestModel->description = $validated['description'] ?? null;
    $requestModel->justification = $validated['justification'] ?? null;
    $requestModel->cause = $validated['cause'] ?? null;
    $requestModel->risk = $validated['risk'] ?? null;
    $requestModel->causing_department = $validated['causing_department'] ?? null;

    if (!empty($validated['signature'])) {
      $requestModel->signature_path = $this->saveSignature($validated['signature']);
    }

    $requestModel->save();

    return redirect()->back()->with('success', 'Request updated successfully.');
  }
  /** Delete a request and its signature */
  public function destroy(UrgentPaymentRequest $urgentPaymentRequest)
  {
    try {
      if ($urgentPaymentRequest->signature_path) {
        Storage::disk('public')->delete($urgentPaymentRequest->signature_path);
      }

      $urgentPaymentRequest->delete();

      return redirect()->back()->with('success', 'Request deleted successfully.');
    } catch (\Exception $e) {
      Log::error('Error deleting payment request: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Error deleting request: ' . $e->getMessage());
    }
  }
  /** Export all requests to PDF */
  public function exportAllPDF()
  {
    $requests = UrgentPaymentRequest::latest()->get();

    foreach ($requests as $req) {
      if ($req->signature_path && Storage::disk('public')->exists($req->signature_path)) {
        $req->signature = 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($req->signature_path));
      } else {
        $req->signature = null;
      }
    }

    $pdf = Pdf::loadView('Urgent_requests_exports.export-all-pdf', ['requests' => $requests]);
    return $pdf->download('All_Urgent_Requests.pdf');
  }
  /** Export a single request to PDF */
  public function exportSinglePDF($id)
  {
    $req = UrgentPaymentRequest::findOrFail($id);

    if ($req->signature_path && Storage::disk('public')->exists($req->signature_path)) {
      $req->signature = 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($req->signature_path));
    } else {
      $req->signature = null;
    }

    $pdf = Pdf::loadView('Urgent_requests_exports.export-single-pdf', ['request' => $req]);
    return $pdf->download('Urgent_Request_' . $req->id . '.pdf');
  }
  /** Get request data in JSON */
  public function edit($id)
  {
    $request = UrgentPaymentRequest::findOrFail($id);
    return response()->json($request);
  }
  /** Approve a request */
  public function approve($id)
  {
    $request = UrgentPaymentRequest::findOrFail($id);
    $request->status = 'Approve';
    $request->save();

    return redirect()->back()->with('success', 'Request approved successfully.');
  }

  /** Update status with reason and notify requestor */

  public function updateStatus(Request $request, $id)
  {
    $requestModel = UrgentPaymentRequest::findOrFail($id);
    $validStatuses = ['Approve', 'Reject', 'In Review'];
    $newStatus = $request->input('status');
    if (!in_array($newStatus, $validStatuses)) {
      return redirect()->back()->with('error', 'Status inválido.');
    }
    $requestModel->status = $newStatus;
    $requestModel->reason = $request->input('reason');
    $requestModel->save();
    $empleado = Usuarios::where('requestor', $requestModel->requestor)->first();
    if ($empleado && $empleado->email) {
      Mail::to($empleado->email)
        ->send(new RequestStatusMail($requestModel));
    }
    return redirect()->back()->with('success', "Status updated to {$newStatus} successfully!");
  }

  /** Get user info by requestor ID */
  public function getName(Request $request)
  {
    $id = $request->input('id');

    $empleado = Usuarios::where('requestor', $id)->first();

    return response()->json([
      'nombre' => $empleado->name ?? 'No encontrado',
      'departamento' => $empleado->department ?? 'No encontrado',
      'causing_department' => $empleado->causing_department ?? 'No encontrado'
    ]);
  }
  /** Show approval page via token */
  public function approveurl($token)
  {
    try {
      $request = UrgentPaymentRequest::where('access_token', $token)->firstOrFail();

      return view('Mails.approve', compact('request'));
    } catch (\Exception $e) {
      return redirect()->route('home')->with('error', 'Solicitud no encontrada o token inválido.');
    }
  }
  /** Update approval status to Approve or Rejected */
  public function updateApprovalStatus(Request $request, $id)
  {
    $requestModel = UrgentPaymentRequest::findOrFail($id);
    $status = $request->input('status');

    if (!in_array($status, ['Approve', 'Reject'])) {
      return redirect()->back()->with('error', 'Estado inválido. Solo se permite Approve o Reject.');
    }

    $requestModel->status = $status;
    $requestModel->reason = $request->input('reason');
    $requestModel->save();

    // Aquí puedes mandar correo si quieres
    $empleado = Usuarios::where('requestor', $requestModel->requestor)->first();
    if ($empleado && $empleado->email) {
      Mail::to($empleado->email)->send(new RequestStatusMail($requestModel));
    }

    return redirect()->back()->with('success', "Solicitud actualizada a estado: $status.");
  }

  /** Show request details */
  public function show($id)
  {
    $requestModel = UrgentPaymentRequest::findOrFail($id);
    return view('View_user.show', compact('requestModel'));
  }
}
