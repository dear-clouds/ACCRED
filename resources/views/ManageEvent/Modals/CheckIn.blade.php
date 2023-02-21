<div role="dialog"  class="modal fade " style="display: none;">
   {!! Form::open(array('url' => route('postImportAttendee', array('event_id' => $event->id)),'files' => true, 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-user-plus"></i>
                    @lang("ManageEvent.invite_attendees")</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                   {!! Form::label('ticket_id', trans("ManageEvent.ticket"), array('class'=>'control-label required')) !!}
                                   {!! Form::select('ticket_id', $tickets, null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
						<!-- Import -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                {!! Form::labelWithHelp('attendees_list', trans("ManageEvent.import_file"), array('class'=>'control-label required'),
                                    trans("ManageEvent.attendees_file_requirements")) !!}
                                {!!  Form::styledFile('attendees_list',1,array('id'=>'input-attendees_list'))  !!}
                                </div>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="checkbox custom-checkbox">
                                        <input type="checkbox" name="email_ticket" id="email_ticket" value="1" />
                                        <label for="email_ticket">&nbsp;&nbsp;@lang("ManageEvent.send_invitation_n_ticket_to_attendees").</label>
                                    </div>
                                </div>
                            </div>
                		</div>
                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit(trans("ManageEvent.create_attendees"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
