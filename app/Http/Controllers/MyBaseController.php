<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organiser;
use Auth;
use JavaScript;
use View;


class MyBaseController extends Controller
{
    public function __construct()
    {

        if (empty(Auth::user())) {
            return redirect()->to('/login');
        }

        /*
         * Set up JS across all views
         */
        JavaScript::put([
            'User'                => [
                'full_name'    => Auth::user()->full_name,
                'email'        => Auth::user()->email,
                'is_confirmed' => Auth::user()->is_confirmed,
            ],
            'DateTimeFormat'      => config('attendize.default_date_picker_format'),
            'DateSeparator'       => config('attendize.default_date_picker_seperator'),
            'GenericErrorMessage' => trans("Controllers.whoops"),
        ]);
        /*
         * Share the organizers across all views
         */
        View::share('organisers', Organiser::scope()->get());
    }

    /**
     * Returns data which is required in each view, optionally combined with additional data.
     *
     * @param int $event_id
     * @param array $additional_data
     *
     * @return arrau
     */
    public function getEventViewData($event_id, $additional_data = [])
    {
        $event = Event::scope()->findOrFail($event_id);

        $image_path = $event->organiser->full_logo_path;
        if ($event->images->first() != null) {
            $image_path = $event->images()->first()->image_path;
        }

        return array_merge([
            'event'      => $event,
            'questions'  => $event->questions()->get(),
            'image_path' => $image_path,
        ], $additional_data);
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }
}
