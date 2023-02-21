

<div role="dialog"  class="modal fade" style="display: none;">
  {!! Form::model($attendee, array('url' => route('postEditAttendee', array('event_id' => $event->id, 'attendee_id' => $attendee->id)), 'class' => 'ajax')) !!}
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title">
          <i class="ico-edit"></i>
          {{ @trans("ManageEvent.edit_attendee_title", ["attendee"=> $attendee->full_name]) }}
        </h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('ticket_id', trans("ManageEvent.ticket"), array('class'=>'control-label required')) !!}
                  {!! Form::select('ticket_id', $tickets, $attendee->ticket_id, ['class' => 'form-control']) !!}
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('first_name', trans("Attendee.first_name"), array('class'=>'control-label')) !!}
                  {!!  Form::text('first_name', Input::old('first_name'),
                  array(
                  'class'=>'form-control'
                  ))  !!}
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('last_name', trans("Attendee.last_name"), array('class'=>'control-label')) !!}
                  {!!  Form::text('last_name', Input::old('last_name'),
                  array(
                  'class'=>'form-control'
                  ))  !!}
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('company', trans("Attendee.company"), array('class'=>'control-label')) !!}
                  {!!  Form::text('company', Input::old('company'),
                  array(
                  'class'=>'form-control'
                  ))  !!}
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('sender', trans("Attendee.sender"), array('class'=>'control-label')) !!}
                  {!!  Form::text('sender', Input::old('sender'),
                  array(
                  'class'=>'form-control'
                  ))  !!}
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('email', trans("Attendee.email"), array('class'=>'control-label')) !!}

                  {!!  Form::text('email', Input::old('email'),
                  array(
                  'class'=>'form-control'
                  ))  !!}
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('enveloppe', trans("Attendee.enveloppe"), array('class'=>'control-label')) !!}

                  {!!  Form::text('enveloppe', Input::old('enveloppe'),
                  array(
                  'class'=>'form-control'
                  ))  !!}
                </div>


                {!! Form::hidden('attendee_id', $attendee->id) !!}
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit(trans("ManageEvent.edit_attendee"), ['class'=>"btn btn-success"]) !!}
              </div>
            </div>
            {!! Form::close() !!}
            <div class="row">
              <div class="col-md-12">

                @if ($attendee->has_arrived == 0)

                <h2>Check-in</h2>

                <form  method="post" enctype="multipart/form-data" class="ansform">
                    {{ csrf_field() }}
                    <div class="wrapper">
                        <canvas id="signature-pad" class="signature-pad" width="100%" height=200></canvas>
                    </div>
                    <div>
                        {!! Form::hidden('attendee_id', $attendee->id) !!}
                        <button type="button" class="btn btn-sm btn-secondary" id="clear">Clear</button>
                        <button type="button" class="btn btn-sm btn-primary" id="save">Save</button>
                    </div>
                </form>


          <script>
          $(function () {

                      $.ajaxSetup({
                          headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          }
                      });

                      var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        penColor: 'rgb(0, 0, 0)'
                      });
                      var saveButton = document.getElementById('save');
                      var cancelButton = document.getElementById('clear');


                      saveButton.addEventListener('click', function (event) {
                          if (signaturePad.isEmpty()) {
                              sweetAlert("Oops...", "Please provide signature first.", "error");
                          } else {

                              // do ajax to post it
                              $.ajax({
                                  url : "{{route('saveSignature')}}",
                                  type: 'POST',
                                  data : {
                                      signature: signaturePad.toDataURL('image/png'),
                                      position: $('#position').val()
                                  },
                                  success: function(response)
                                  {
                                      swal({
                                        title: "Signature Saved",
                                        text: "Your signature has now been stored.",
                                        icon: "success",
                                      });
                                      window.setTimeout(function(){window.location.reload()}, 3000);
                                      //data - response from server
                                      console.log(response);
                                  },
                                  error: function(response)
                                  {

                                      console.log(response);
                                  }
                              });
                          }

                      });

                      cancelButton.addEventListener('click', function (event) {
                          signaturePad.clear();
                      });

                  });

          </script>

          <h2>Enveloppe n°{{$attendee->enveloppe}}</h2>

          @endif

        </div>


      </div>

    </div>

  </div>
</div>
</div> <!-- /end modal body-->
<div class="modal-footer">

  @if ($attendee->has_arrived == 1)
  <form method="post" action="{{route('postCheckInAttendee', ['event_id' => $event->id])}}" id="check-form">
    @csrf
    {!! Form::hidden('attendee_id', $attendee->id) !!}
    {!! Form::hidden('has_arrived', $attendee->has_arrived) !!}
    {!! Form::hidden('checking', $attendee->checking) !!}
    <button type="submit" name="check-in" class="btn btn-danger">Check-out</button>

    @else
    <form method="post" action="{{route('postCheckInAttendee', ['event_id' => $event->id])}}" id="check-form">
      @csrf
      {!! Form::hidden('attendee_id', $attendee->id) !!}
      {!! Form::hidden('has_arrived', $attendee->has_arrived) !!}
      {!! Form::hidden('checking', $attendee->checking) !!}
      <button type="submit" name="check-in" class="btn btn-success">Check-in</button>
    </form>

    @endif


  </div>
</div><!-- /end modal content-->

</div>
</div>
