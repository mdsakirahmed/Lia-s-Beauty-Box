<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::orderBy('id', 'desc')->paginate(20);
        return view('backend.invoice.index',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $appointments = Appointment::where('status', 'Approved')->get();
        $serviceCategories = ServiceCategory::all();
        return view('backend.invoice.create',compact('appointments', 'serviceCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id'    => 'required|exists:appointments,id',
            'service_data_set'  => 'required',
            'vat_percentage'    => 'nullable|numeric|min:0|max:5',
            'note'              => 'nullable|string',
        ]);
        //Change appointment status
        $appointment = Appointment::find($request->appointment_id);
        if($appointment->status != 'Approved'){
            return [
                'type' => 'error',
                'message' => 'This appointment is not approved.',
            ];
        }
        $appointment->status = 'Done';
        $appointment->save();
        //Create invoice
        $invoice = new Invoice();
        $invoice->appointment_id = $appointment->id;
        $invoice->vat_percentage = $request->vat_percentage ?? 0;
        $invoice->note = $request->note;
        // $invoice->due_date;
        // $invoice->custom_counter;
        // $invoice->bar_code;
        $invoice->save();
        //Invoice item save with this invoice ID
        try{
            foreach($request->service_data_set as $service_data){
                $invoiceItem = new InvoiceItem();
                $invoiceItem->invoice_id   = $invoice->id;
                $invoiceItem->service_id   = $service_data['service'];
                $invoiceItem->quantity  = $service_data['quantity'];
                $invoiceItem->price     = $service_data['price'];
                $invoiceItem->save();
            }
        }catch(\Exception $e){
            // Appointment status back and invoice delete
            $invoice->delete();
            $appointment->status = 'Approved';
            $appointment->save();
            return [
                'type' => 'error',
                'message' => $e->getMessage(),
            ];
        }

        return [
            'type' => 'success',
            'message' => 'Successfully Created',
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $data = [
            'foo' => 'bar'
        ];
        $pdf = PDF::loadView('backend.invoice.pdf', $data);
        return $pdf->stream('document.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}