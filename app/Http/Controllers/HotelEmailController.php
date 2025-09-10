<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelEmail;
use App\Models\HotelMeta;
use App\Models\Product;
use Illuminate\Http\Request;

class HotelEmailController extends Controller
{
    public function show($id)
    {
        $hotel = Hotel::find($id);
        $user = auth()->user();

        $email_schedule = HotelMeta::where('hotel_id', $hotel->id)->where('key', 'like', 'email-schedule%')->get();
        $email_content = HotelEmail::where('hotel_id', $hotel->id)->first();
        $email_recipients = HotelMeta::where('hotel_id', $hotel->id)->where('key', 'email-recipients')->first()->value ?? '';


        if (!empty($email_schedule)) {
            $email_schedule = array_combine(
                array_column($email_schedule->toArray(), 'key'),
                array_column($email_schedule->toArray(), 'value')
            );
        }

        if ($email_content) {
            $email_content->featured_products = json_decode($email_content->featured_products);
            $tmp = [];
            foreach ($email_content->featured_products as $key => $product_id) {
                $tmp[] = Product::find($product_id);
            }
            $email_content->featured_products = $tmp;
        } else {
            HotelEmail::createStandardTemplates($hotel->id);
            $email_content = HotelEmail::where('hotel_id', $hotel->id)->first();
            $email_content->featured_products = json_decode($email_content->featured_products);

        }
        if ($user->role === 'superadmin' || $hotel->user_id === $user->id) {
            return view('admin.emails.customise', [
                'hotel' => $hotel,
                'email_schedule' => $email_schedule,
                'email_content' => $email_content,
                'email_recipients' => $email_recipients
            ]);
        } else {
            return redirect()->route('dashboard');
        }

    }

    public function storeCustomisations($hotel_id, Request $request)
    {
        $user = auth()->user();
        $hotel = Hotel::find($hotel_id);
        if ($user->role === 'superadmin' || $hotel->user_id === $user->id) {


            if ($request->has('hotel_meta')) {
                foreach ($request->hotel_meta as $key => $value) {
                    $hotel_meta = HotelMeta::where('hotel_id', $hotel_id)->where('key', $key)->first();
                    if (!$hotel_meta) {
                        $hotel_meta = new HotelMeta();
                        $hotel_meta->hotel_id = $hotel_id;
                        $hotel_meta->key = $key;
                    }
                    if ($value || $value === '0') {
                        $hotel_meta->value = $value;
                        $hotel_meta->save();
                    }
                }
            }

            if ($request->has('hotel_email')) {
                $hotel_email = HotelEmail::where('hotel_id', $hotel_id)->where('email_type', $request->hotel_email['email_type'])->first();
                if (!$hotel_email) {
                    $hotel_email = new HotelEmail();
                    $hotel_email->hotel_id = $hotel_id;
                    $hotel_email->email_type = $request->hotel_email['email_type'];
                }

                $hotel_email->key_message = $request->hotel_email['key-message'];
                $hotel_email->button_text = $request->hotel_email['button-text'];
                $hotel_email->featured_products = json_encode($request->hotel_email['featured-products']);
                $hotel_email->additional_information = $request->hotel_email['additional-information'];
                $hotel_email->save();

            }

            return redirect()->route('email.customise', ['id' => $hotel_id]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function listTemplates($hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        if ($user->role === 'superadmin' || $hotel->user_id === $user->id) {
            $email_templates = HotelMeta::where('hotel_id', $hotel_id)->where('key', 'custom-email-schedule')->first();
            return view('admin.emails.templates', [
                'hotel' => $hotel,
                'email_templates' => isset($email_templates->value) ? json_decode($email_templates->value) : []
            ]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function addTemplate($hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        $email_content = HotelEmail::createv2StandardTemplates();
        $meta = [
            'email_name' => '',
            'email_subject' => '',
            'when_to_send' => '',
            'days' => '',
            'time' => '15:00'
        ];

        if ($user->role === 'superadmin' || $hotel->user_id === $user->id) {
            return view('admin.emails.email-automation-form', [
                'hotel' => $hotel,
                'email_content' => $email_content,
                'meta' => $meta
            ]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function editTemplate($hotel_id, $template_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        $email_content = HotelEmail::find($template_id);
        $email_content->featured_products = json_decode($email_content->featured_products);
        $email_schedule = HotelMeta::where('hotel_id', $hotel_id)->where('key', 'custom-email-schedule')->first();
        $email_schedule = json_decode($email_schedule->value, true);
        foreach ($email_schedule as $schedule) {
            if ($schedule['email_id'] == $template_id) {
                $meta = $schedule;
                break;
            }
        }

        if ($user->role === 'superadmin' || $hotel->user_id === $user->id) {
            return view('admin.emails.email-automation-form', [
                'hotel' => $hotel,
                'email_content' => $email_content,
                'meta' => $meta,
                'template_id' => $template_id
            ]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function deleteTemplate($template_id)
    {
        $email_template = HotelEmail::find($template_id);
        if ($email_template) {
            $hotel_id = $email_template->hotel_id;
            $email_template->delete();

            $email_schedule = HotelMeta::where('hotel_id', $hotel_id)->where('key', 'custom-email-schedule')->first();
            if ($email_schedule) {
                $schedules = json_decode($email_schedule->value, true);
                $schedules = array_filter($schedules, function ($schedule) use ($template_id) {
                    return $schedule['email_id'] != $template_id;
                });
                $email_schedule->value = json_encode(array_values($schedules));
                $email_schedule->save();
            }
        }
        return redirect()->route('email-v2.list-templates', ['hotel_id' => $hotel_id]);
    }

    public function storeTemplate($hotel_id, Request $request)
    {
        $request->validate([
            'email_name' => 'required|string|max:255',
            'email_subject' => 'required|string|max:255',
            'when_to_send' => 'required|string|max:255',
            'days' => 'nullable|integer',
            'time' => 'required|date_format:H:i',
            'template_id' => 'nullable|integer|exists:hotel_emails,id',
            'hotel_email.key-message' => 'required|string',
            'hotel_email.button-text' => 'required|string|max:255',
            'hotel_email.featured-products' => 'nullable|array',
            'hotel_email.additional-information' => 'required|string',
        ]);

        if ($request->template_id) {
            $hotel_email = HotelEmail::find($request->template_id);
        } else {
            $hotel_email = new HotelEmail();
        }


        $hotel_email->hotel_id = $hotel_id;
        $hotel_email->email_type = 'customised-email';
        $hotel_email->email_subject = $request->email_subject;

        $hotel_email->key_message = $request->hotel_email['key-message'];
        $hotel_email->button_text = $request->hotel_email['button-text'];
        $hotel_email->featured_products = json_encode($request->hotel_email['featured-products']);
        $hotel_email->additional_information = $request->hotel_email['additional-information'];
        $hotel_email->save();

        $meta_data = [
            'email_name' => $request->email_name,
            'email_subject' => $request->email_subject,
            'when_to_send' => $request->when_to_send,
            'days' => $request->days,
            'time' => $request->time,
            'email_id' => $hotel_email->id
        ];

        $email_schedule = HotelMeta::where('hotel_id', $hotel_id)->where('key', 'custom-email-schedule')->first();
        if (!$email_schedule) {
            $email_schedule = new HotelMeta();
            $email_schedule->hotel_id = $hotel_id;
            $email_schedule->key = 'custom-email-schedule';
            $email_schedule->value = json_encode([$meta_data]);
        } else {
            $existing_value = $email_schedule->value ? json_decode($email_schedule->value, true) : [];
            $existing_value[] = $meta_data;
            $email_schedule->value = json_encode($existing_value);
        }

        $email_schedule->save();
        return redirect()->route('email-v2.list-templates', ['hotel_id' => $hotel_id]);
    }
}
