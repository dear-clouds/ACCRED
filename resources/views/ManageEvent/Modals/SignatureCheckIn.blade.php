<div role="dialog"  class="modal fade" style="display: none;">
  {!! Form::model($attendee, array('url' => route('postSignatureAttendee', array('event_id' => $event->id, 'attendee_id' => $attendee->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-edit"></i>
                    {{ @trans("ManageEvent.signature_attendee_title", ["attendee"=> $attendee->full_name]) }}
                    </h3>
            </div>
            <div class="modal-body">
                <div class="row">


<div id="signature-pad" class="m-signature-pad">
  <div class="m-signature-pad--body">
    <canvas style="border: 2px dashed #ccc"></canvas>
  </div>

  <div class="m-signature-pad--footer">
    <button type="button" class="btn btn-sm btn-secondary" data-action="clear">Clear</button>
    <button type="button" class="btn btn-sm btn-primary" data-action="save">Save</button>
  </div>
</div>

                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::hidden('attendee_id', $attendee->id) !!}
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit(trans("ManageEvent.signature_attendee"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
