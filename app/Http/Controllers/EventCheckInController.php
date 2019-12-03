<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use JavaScript;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class EventCheckInController extends MyBaseController
{

    /**
     * Show the check-in page
     *
     * @param $event_id
     * @return \Illuminate\View\View
     */
    public function showCheckIn($event_id)
    {
        $event = Event::scope()->findOrFail($event_id);
        $attendee = Attendee::all();

        $data = [
            'event'     => $event,
            'attendees' => $event->attendees
        ];

        JavaScript::put([
            // 'qrcodeCheckInRoute' => route('postQRCodeCheckInAttendee', ['event_id' => $event->id]),
            // 'checkInRoute'       => route('postCheckInAttendee', ['event_id' => $event->id]),
            'checkInSearchRoute' => route('postCheckInSearch', ['event_id' => $event->id]),
        ]);

        return view('ManageEvent.CheckIn', $data);
    }

    public function showQRCodeModal(Request $request, $event_id)
    {
        return view('ManageEvent.Modals.QrcodeCheckIn');
    }

    /**
     * Show the 'Edit Attendee' modal
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return View
     */
    public function showCheckInModal(Request $request, $event_id, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee' => $attendee,
            'event'    => $attendee->event,
            'tickets'  => $attendee->event->tickets->pluck('title', 'id'),
        ];

        return view('ManageEvent.Modals.showCheckInModal', $data);
    }

    /**
     * Attendee Signature
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return mixed
     */
    // public function postSignatureAttendee(Request $request, $event_id, $attendee_id)
    // {
    //     $attendee_id = $request->get('attendee_id');
    //
    //     $data_uri = "data:image/png;base64,signature";
    //     $encoded_image = explode(",", $data_uri)[1];
    //     $decoded_image = base64_decode($encoded_image);
    //     Storage::put('/uploads/signatures/' $event_id . '/' . $attendee_id . '-signature.png', $decoded_image);
    //
    //     $attendee = Attendee::scope()->findOrFail($attendee_id);
    //     $attendee->update($request->all());
    //
    //     session()->flash('message',trans("Controllers.successfully_updated_attendee"));
    //
    //     return response()->json([
    //         'status'      => 'success',
    //         'id'          => $attendee->id,
    //         'redirectUrl' => '',
    //     ]);
    // }


    /* *
   * Save Signature
   *
   * @param Request $request
   * @param $event_id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function saveSignature(Request $request, $event_id)
  {
      //Find Assessment in DB//
      // $signature = DrivingAssessments::find($request->assid);
      $attendee_id = $request->get('attendee_id');

      //Get image from ajax, encode then decode the image to store//
      $data_uri = $request->signature;
      $encoded_image = explode(",", $data_uri)[1];
      $decoded_image = base64_decode($encoded_image);

      //store the decoded image//
      $storagePath = \Storage::put('/uploads/signatures/'. $event_id . '/' . $attendee_id .'_signature.png', $decoded_image);

      //store the file in the db//
      // $signature->signature = 'signatures/'.$request->assid.'_driver_signature.png';
      // $signature->save();


  }

    /**
     * Updates an attendee
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return mixed
     */
    public function postCheckInEditAttendee(Request $request, $event_id, $attendee_id)
    {
        $rules = [
            'last_name' => 'required',
            'ticket_id'  => 'required|exists:tickets,id,account_id,' . Auth::user()->account_id,
            'email'      => 'email',
        ];

        $messages = [
            'ticket_id.exists'   => trans("Controllers.ticket_not_exists_error"),
            'ticket_id.required' => trans("Controllers.ticket_field_required_error"),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $attendee = Attendee::scope()->findOrFail($attendee_id);
        $attendee->update($request->all());

        // dd($attendee);
        //
        // $data_uri = "data:image/png;base64,signature";
        // $encoded_image = explode(",", $data_uri)[1];
        // $decoded_image = base64_decode($encoded_image);
        // Storage::put('/uploads/signatures/' $event_id . '/' . $attendee_id . '-signature.png', $decoded_image);
        //
        // dd($decoded_image);

        session()->flash('message',trans("Controllers.successfully_updated_attendee"));

        return response()->json([
            'status'      => 'success',
            'id'          => $attendee->id,
            'redirectUrl' => '',
        ]);
    }

    /**
     * Search attendees
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckInSearch(Request $request, $event_id)
    {
        $searchQuery = $request->get('q');

        $attendees = Attendee::scope()->withoutCancelled()
            ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
            ->join('orders', 'orders.id', '=', 'attendees.order_id')
            ->where(function ($query) use ($event_id) {
                $query->where('attendees.event_id', '=', $event_id);
            })->where(function ($query) use ($searchQuery) {
                $query->orWhere('attendees.first_name', 'like', $searchQuery . '%')
                    ->orWhere(
                        DB::raw("CONCAT_WS(' ', attendees.first_name, attendees.last_name)"),
                        'like',
                        $searchQuery . '%'
                    )
                    ->orWhere('orders.order_reference', 'like', $searchQuery . '%')
                    ->orWhere('attendees.enveloppe', 'like', $searchQuery . '%')
                    ->orWhere('attendees.company', 'like', $searchQuery . '%')
                    ->orWhere('attendees.sender', 'like', $searchQuery . '%')
                    ->orWhere('attendees.last_name', 'like', $searchQuery . '%');
            })
            ->select([
                'attendees.id',
                'attendees.first_name',
                'attendees.last_name',
                'attendees.email',
                'attendees.arrival_time',
                'attendees.reference_index',
                'attendees.enveloppe',
                'attendees.company',
                'attendees.sender',
                'attendees.has_arrived',
                'tickets.title as ticket',
                'orders.order_reference',
                'orders.is_payment_received'
            ])
            ->orderBy('attendees.enveloppe', 'ASC')
            ->get();

        return response()->json($attendees);
    }

    /**
     * Check in/out an attendee
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckInAttendee(Request $request, $event_id)
    {
        $attendee_id = $request->get('attendee_id');
        $checking = $request->get('checking');

        $attendee = Attendee::scope()->find($attendee_id);

        /*
         * Ugh
         */
        if ((($checking == 'in') || ($attendee->has_arrived == 1))) {

            Attendee::find($attendee->id)->update(['has_arrived' => 0, 'arrival_time' => 0, 'checking' => $checking]);

            return redirect()->back()->with('success', trans("Controllers.attendee_successfully_checked_out"));

            // return response()->json([
            //     'status'  => 'error',
            //     'message' => 'Attendee Already Checked ' . (($checking == 'in') ? 'In (at ' . $attendee->arrival_time->format('H:i A, F j') . ')' : 'Out') . '!',
            //     'checked' => $checking,
            //     'id'      => $attendee->id,
            //     'redirectUrl' => '/event/' . $event_id . '/check_in',
            // ]);
        }

        else {

        Attendee::find($attendee->id)->update(['has_arrived' => true, 'arrival_time' => Carbon::now(), 'checking' => $checking]);

        /* Save signature */
        // $data_uri = "data:image/png;base64,signature";
        // $encoded_image = explode(",", $data_uri)[1];
        // $decoded_image = base64_decode($encoded_image);
        // Storage::put('/uploads/signatures/' $event_id . '/' . $attendee_id . '-signature.png', $decoded_image);

        // return response()->json([
        //     'status'  => 'success',
        //     'checked' => $checking,
        //     'message' =>  (($checking == 'in') ? trans("Controllers.attendee_successfully_checked_in") : trans("Controllers.attendee_successfully_checked_out")),
        //     'id'      => $attendee->id,
        //     'redirectUrl' => '/event/' . $event_id . '/check_in',
        // ]);

        return redirect()->back()->with('success', trans("Controllers.attendee_successfully_checked_in"));

        }
    }


    /**
     * Save signature
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function postSignatureAttendee(Request $request)
    // {
    //     $attendee_id = $request->get('attendee_id');
    //     $event_id = $request->get('event_id');
    //
    //     $attendee = Attendee::scope()->find($attendee_id);
    //     $event = Event::scope()->find($event_id);
    //
    //     $signature = new Signature;
    //     $signature->attendee_id = $attendee_id;
    //     $signature->event_id = $event_id;
    //     // $signature->position = $request->position;
    //
    //     $data_uri = $request->signature;
    //     $encoded_image = explode(",", $data_uri)[1];
    //     //$decoded_image = base64_decode($encoded_image);
    //
    //     $sig = sha1($request->session()->get('attendee.first_name').$request->session()->get('attendee.last_name')) . "_signature.png";
    //     $folder = '/uploads/signatures/';
    //
    //     Storage::put($folder, $sig);
    //
    //     $signature->url = $encoded_image;
    //     $signature->save();
    //
    //
    //     return response()->json([
    //         'status'  => 'success',
    //         'id'      => $attendee->id,
    //     ]);
    // }


    /**
     * Check in an attendee
     *
     * @param $event_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckInAttendeeQr($event_id, Request $request)
    {
        $event = Event::scope()->findOrFail($event_id);

        $qrcodeToken = $request->get('attendee_reference');
        $attendee = Attendee::scope()->withoutCancelled()
            ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
            ->where(function ($query) use ($event, $qrcodeToken) {
                $query->where('attendees.event_id', $event->id)
                    ->where('attendees.private_reference_number', $qrcodeToken);
            })->select([
                'attendees.id',
                'attendees.order_id',
                'attendees.first_name',
                'attendees.last_name',
                'attendees.email',
                'attendees.signature',
                'attendees.reference_index',
                'attendees.enveloppe',
                'attendees.company',
                'attendees.sender',
                'attendees.arrival_time',
                'attendees.has_arrived',
                'tickets.title as ticket',
            ])->first();

        if (is_null($attendee)) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.invalid_ticket_error")
            ]);
        }

        $relatedAttendesCount = Attendee::where('id', '!=', $attendee->id)
            ->where([
                'order_id'    => $attendee->order_id,
                'has_arrived' => false
            ])->count();

        if ($attendee->has_arrived) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.attendee_already_checked_in", ["time"=> $attendee->arrival_time->format(env("DEFAULT_DATETIME_FORMAT"))])
            ]);
        }

        Attendee::find($attendee->id)->update(['has_arrived' => true, 'arrival_time' => Carbon::now()]);

        return response()->json([
            'status'  => 'success',
            'name' => $attendee->first_name." ".$attendee->last_name,
            'reference' => $attendee->reference,
            'enveloppe' => $attendee->enveloppe,
            'signature' => $attendee->signature,
            'company' => $attendee->company,
            'sender' => $attendee->sender,
            'ticket' => $attendee->ticket
        ]);
    }
}
