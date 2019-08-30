<html>
<head>





</head>
<body>



<div role="dialog"  class="modal fade" style="display: none;">
   {!! Form::model($attendee, array('url' => route('postCheckInEditAttendee', array('event_id' => $event->id, 'attendee_id' => $attendee->id)), 'class' => 'ajax')) !!}
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

<h2>Check-in</h2>

                              <div id="signature-pad" class="m-signature-pad">
                                <div class="m-signature-pad--body">
                                  <canvas style="border: 2px dashed #ccc"></canvas>
                                </div>

                                <div class="m-signature-pad--footer">
                                  <button type="button" class="btn btn-sm btn-secondary" data-action="clear">Clear</button>
                                  <button type="button" class="btn btn-sm btn-primary" data-action="save">Save</button>
                                </div>
                              </div>

                              {!! HTML::script('vendor/jquery/dist/jquery.min.js') !!}

                              <script>
                              $(function () {
                                var wrapper = document.getElementById("signature-pad"),
                                    clearButton = wrapper.querySelector("[data-action=clear]"),
                                    saveButton = wrapper.querySelector("[data-action=save]"),
                                    canvas = wrapper.querySelector("canvas"),
                                    signaturePad;

                                // Adjust canvas coordinate space taking into account pixel ratio,
                                // to make it look crisp on mobile devices.
                                // This also causes canvas to be cleared.
                                window.resizeCanvas = function () {
                                  var ratio =  window.devicePixelRatio || 1;
                                  canvas.width = canvas.offsetWidth * ratio;
                                  canvas.height = canvas.offsetHeight * ratio;
                                  canvas.getContext("2d").scale(ratio, ratio);
                                }

                                resizeCanvas();

                                signaturePad = new SignaturePad(canvas);

                                clearButton.addEventListener("click", function(event) {
                                  signaturePad.clear();
                                });

                                saveButton.addEventListener("click", function(event) {
                                  event.preventDefault();

                                  if (signaturePad.isEmpty()) {
                                    alert("Please provide a signature first.");
                                  } else {
                                    var dataUrl = signaturePad.toDataURL();
                                    var image_data = dataUrl.replace(/^data:image\/(png|jpg);base64,/, "");

                                    $.ajax({
                                      url: '/save-signature',
                                      type: 'POST',
                                      data: {
                                        image_data: image_data,
                                      },
                                    }).done(function() {
                                      //
                                    });
                                  }
                                });
                              });

                              </script>

                              <h2>Enveloppe</h2>
                              {{$attendee->enveloppe}}


                              <a
                                  href="{{route('postCheckInAttendee', ['attendee_id'=>$attendee->id])}}"
                                  >Check-in</a></li>
                          </ul>

                            </div>


                            </div>

                        </div>

                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::hidden('attendee_id', $attendee->id) !!}
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit(trans("ManageEvent.edit_attendee"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>


@include("Shared.Partials.LangScript")
{!! HTML::script('assets/javascript/backend.js') !!}
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    });

    @if(!Auth::user()->first_name)
      setTimeout(function () {
        $('.editUserModal').click();
    }, 1000);
    @endif

</script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
</body>
</html>