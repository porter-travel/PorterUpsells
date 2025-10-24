<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Hotel;
use App\Models\HotelMeta;
use Illuminate\Http\Request;

class EmailTemplatesController extends Controller
{


    public $labelKeys =  [
            'before_arrival' => 'Before Arrival',
            'after_arrival' => 'After Arrival',
            'before_checkout' => 'Before Checkout',
            'after_checkout' => 'After Checkout'
        ];

    public function listTemplates($hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        if ($user->role === 'superadmin' || $hotel->user_id === $user->id) {
            $email_templates = EmailTemplate::where('hotel_id', $hotel_id)->get();
            return view('admin.emails.templates', [
                'hotel' => $hotel,
                'email_templates' => $email_templates,
                'keys' => $this->labelKeys
            ]);
        } else {
            return redirect()->route('dashboard');
        }
    }


    public function create($hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        return view('admin.builder.builder', ['hotel_id' => $hotel_id, 'template_id' => null, 'hotel' => $hotel]);
    }

    public function store(Request $request, $hotel_id){

        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        if (!($user->role === 'superadmin' || $hotel->user_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        if($request->template_id){
            $template = EmailTemplate::find($request->template_id);
            if(!$template){
                return response()->json(['error' => 'Template not found.'], 404);
            }

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'body' => 'required|json',
                'type' => 'required|string|max:255',
                'is_active' => 'nullable|boolean',
                'when_to_send' => 'required|string|max:255',
                'days' => 'required|integer',
                'time' => 'required',
            ]);

            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            $template->update($data);

            return response()->json(['success' => 'Email template updated successfully.', 'template_id' => $template->id]);

        } else {
            return $this->storeNewTemplate($request, $hotel_id);
        }


    }

    public function edit($hotel_id, $template_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        $template = EmailTemplate::find($template_id);
        if ($user->role === 'superadmin' || $hotel->user_id === $user->id) {
            if(!$template){
                return redirect()->route('email-templates.list', ['hotel_id' => $hotel_id])->withErrors(['Template not found.']);
            }

            return view('admin.builder.builder', [
                'hotel' => $hotel,
                'template' => json_encode($template),
                'hotel_id' => $hotel_id,
                'template_id' => $template_id
            ]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function getTemplateData($hotel_id, $template_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        if (!($user->role === 'superadmin' || $hotel->user_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $template = EmailTemplate::find($template_id);
        if(!$template){
            return response()->json(['error' => 'Template not found.'], 404);
        }

        return response()->json(['template' => $template]);
    }


    private function storeNewTemplate(Request $request, $hotel_id){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|json',
            'type' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'when_to_send' => 'required|string|max:255',
            'days' => 'required|integer',
            'time' => 'required',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['hotel_id'] = $hotel_id;

        $template = EmailTemplate::create($data);

        return response()->json(['success' => 'Email template created successfully.', 'template_id' => $template->id]);

    }
}
