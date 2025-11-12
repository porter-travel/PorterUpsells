<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CustomerEmail;
use App\Models\EmailTemplate;
use App\Models\Hotel;
use App\Models\HotelMeta;
use Illuminate\Http\Request;

class EmailTemplatesController extends Controller
{


    public $labelKeys = [
        'before_arrival' => 'Before Arrival',
        'after_arrival' => 'After Arrival',
        'before_checkout' => 'Before Checkout',
        'after_checkout' => 'After Checkout'
    ];

    public function listTemplates($hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        $sampleTemplates = self::sampleTemplateData();
        if ($user->role === 'superadmin' || $hotel->user_id === $user->id) {
            $email_templates = EmailTemplate::where('hotel_id', $hotel_id)->get();
            return view('admin.emails.templates', [
                'hotel' => $hotel,
                'email_templates' => $email_templates,
                'keys' => $this->labelKeys,
                'sampleTemplates' => $sampleTemplates
            ]);
        } else {
            return redirect()->route('dashboard');
        }
    }


    public function create($hotel_id, $example_key = null)
    {
        $hotel = Hotel::find($hotel_id);
        $data = null;
        if ($example_key) {
            $data = self::getSampleTemplateData($example_key);
        }
        return view('admin.builder.builder', ['hotel_id' => $hotel_id, 'template_id' => null, 'hotel' => $hotel, 'data' => $data]);
    }

    public function store(Request $request, $hotel_id)
    {

        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        if (!($user->role === 'superadmin' || $hotel->user_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        if ($request->template_id) {
            $template = EmailTemplate::find($request->template_id);
            if (!$template) {
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
            if (!$template) {
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
        if (!$template) {
            return response()->json(['error' => 'Template not found.'], 404);
        }

        return response()->json(['template' => $template]);
    }

    public function destroy($hotel_id, $template_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        if (!($user->role === 'superadmin' || $hotel->user_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $template = EmailTemplate::find($template_id);
        if (!$template) {
            return response()->json(['error' => 'Template not found.'], 404);
        }

        $customerEmails = CustomerEmail::where('email_template_id', $template_id);
        $customerEmails->delete();

        $template->delete();

        return redirect()->route('email-v2.list-templates', ['hotel_id' => $hotel_id]);
    }

    public function sendTestEmail(Request $request, $hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        $user = auth()->user();
        if (!($user->role === 'superadmin' || $hotel->user_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }


        $test_email_address = $request->input('email');
        if (!$test_email_address) {
            return response()->json(['error' => 'Test email address is required.'], 400);
        }

        $content = $request->input('content');
        $meta = $request->input('meta');
        $subject = $meta['email_subject'];
        $booking = null;
        if ($request->use_test_booking) {
            $booking = new Booking();
            $booking->hotel_id = $hotel->id;
            $booking->name = 'Test User';
            $booking->email_address = $test_email_address;
            $booking->arrival_date = now()->addDays(7)->toDateString();
            $booking->departure_date = now()->addDays(10)->toDateString();
        }

        // Send test email
        \Mail::to($test_email_address)->send(new \App\Mail\CustomerTemplateEmail($subject, $content, $hotel, $booking));

        return response()->json(['success' => 'Test email sent successfully to ' . $test_email_address]);
    }

    private function storeNewTemplate(Request $request, $hotel_id)
    {
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

    private static function sampleTemplateData()
    {
        $emailTemplates = [
            'preArrivalUpselEmail' => [
                'emailName' => 'Pre-Arrival Upsell Email',
                'emailSubject' => 'Enhance your upcoming experience',
                'emailBody' => '[{"id": 1761293680674, "url": null, "type": "HeaderBlock", "level": null, "content": null, "alignment": null}, {"id": 1761293684352, "url": null, "type": "ContentBlock", "level": null, "content": "<div>Hi [[first_name]],</div><div><br></div><div>We\u2019re looking forward to welcoming you soon! Before you arrive, why not explore a few ways to personalise your experience.</div>", "alignment": "left"}, {"id": 1761293698239, "url": null, "type": "FeaturedProductsBlock", "level": null, "content": null, "products": [null, null, null, null], "alignment": null}, {"id": 1761293700987, "url": "https://www.example.com", "type": "ButtonBlock", "level": null, "content": "Personalise Your Visit", "alignment": null}, {"id": 1761293709883, "url": null, "type": "ContentBlock", "level": null, "content": "<div>See you soon,</div><div>The [[business_name]] Team</div>", "alignment": "left"}]',
                'whenToSend' => 'before_arrival',
                'days' => 10,
                'time' => '12:00',
            ],
            'preArrivalInformationEmail' => [
                'emailName' => 'Pre-Arrival Information Email',
                'emailSubject' => "Your visit is coming up — here's what to know",
                'emailBody' => '[{"id": 1761293793153, "url": null, "type": "HeaderBlock", "level": null, "content": null, "alignment": null}, {"id": 1761293796128, "url": null, "type": "ContentBlock", "level": null, "content": "<p data-start=\\"1246\\" data-end=\\"1278\\">Hi [[first_name]],<br><br></p>\\n<p data-start=\\"1280\\" data-end=\\"1401\\">Your upcoming visit to <strong data-start=\\"1303\\" data-end=\\"1324\\">[[business_name]]</strong> is almost here!<br><br data-start=\\"1340\\" data-end=\\"1343\\">\\nTo make everything smooth, here\u2019s what you need to know:<br><br></p>\\n<p data-start=\\"1403\\" data-end=\\"1465\\">📍 <strong data-start=\\"1406\\" data-end=\\"1436\\">Location &amp; Arrival Details</strong><br data-start=\\"1436\\" data-end=\\"1439\\">\\n[[arrival_instructions]]<br><br></p>\\n<p data-start=\\"1467\\" data-end=\\"1519\\">🕒 <strong data-start=\\"1470\\" data-end=\\"1499\\">Opening or Check-In Times</strong><br data-start=\\"1499\\" data-end=\\"1502\\">\\n[[timing_info]]<br><br></p>\\n<p data-start=\\"1521\\" data-end=\\"1542\\">📱 <strong data-start=\\"1524\\" data-end=\\"1540\\">Useful Links</strong></p>\\n<ul data-start=\\"1543\\" data-end=\\"1618\\">\\n<li data-start=\\"1543\\" data-end=\\"1580\\">\\n<p data-start=\\"1545\\" data-end=\\"1580\\">[[link_1_label]] → [[link_1_url]]</p>\\n</li>\\n<li data-start=\\"1581\\" data-end=\\"1618\\">\\n<p data-start=\\"1583\\" data-end=\\"1618\\">[[link_2_label]] → [[link_2_url]]<br><br></p>\\n</li>\\n</ul>\\n<p data-start=\\"1620\\" data-end=\\"1716\\">If you\u2019d like to add extras or special touches before you arrive, you can still do that below.</p>", "alignment": "left"}, {"id": 1761293829625, "url": "https://www.example.com", "type": "ButtonBlock", "level": null, "content": "View available add-ons", "alignment": null}, {"id": 1761293842021, "url": null, "type": "ContentBlock", "level": null, "content": "See you soon,<br data-start=\\"1762\\" data-end=\\"1765\\">\\n<strong data-start=\\"1765\\" data-end=\\"1795\\">The [[business_name]] Team</strong>", "alignment": "left"}]',
                'whenToSend' => 'before_arrival',
                'days' => 5,
                'time' => '12:00',
            ],
            'inStayUpsellEmail' => [
                'emailName' => 'In-Stay Upsell Email',
                'emailSubject' => 'Make the most of your time with us',
                'emailBody' => '[{"id": 1761293907774, "url": null, "type": "HeaderBlock", "level": null, "content": null, "alignment": null}, {"id": 1761293909880, "url": null, "type": "ContentBlock", "level": null, "content": "<p data-start=\\"1997\\" data-end=\\"2029\\">Hi [[first_name]],<br><br></p>\\n<p data-start=\\"2031\\" data-end=\\"2188\\">We hope you\u2019re enjoying your time with <strong data-start=\\"2070\\" data-end=\\"2091\\">[[business_name]]</strong>!<br><br data-start=\\"2092\\" data-end=\\"2095\\">\\nIf you\u2019d like to enhance your experience, we\u2019ve got some great options available right now:<br><br></p>\\n<p data-start=\\"2190\\" data-end=\\"2281\\">✨ Add-ons, experiences, and limited-time offers — all available directly from your phone.<br><br></p>\\n<p data-start=\\"2283\\" data-end=\\"2350\\">Tap below to see what\u2019s on offer and we\u2019ll take care of the rest.</p>", "alignment": "left"}, {"id": 1761293923997, "url": "https://www.example.com", "type": "ButtonBlock", "level": null, "content": "Explore extras", "alignment": null}, {"id": 1761293936230, "url": null, "type": "ContentBlock", "level": null, "content": "Thanks for being with us,<br data-start=\\"2400\\" data-end=\\"2403\\">\\n<strong data-start=\\"2403\\" data-end=\\"2433\\">The [[business_name]] Team</strong>", "alignment": "left"}]',
                'whenToSend' => 'after_arrival',
                'days' => 1,
                'time' => '12:00',
            ],
            'postStayEmail' => [
                'emailName' => 'Post-Stay Review + Discount Email',
                'emailSubject' => 'Thanks for visiting — here\u2019s a little thank-you',
                'emailBody' => '[{"id": 1761293981745, "url": null, "type": "HeaderBlock", "level": null, "content": null, "alignment": null}, {"id": 1761293984299, "url": null, "type": "ContentBlock", "level": null, "content": "<p data-start=\\"2651\\" data-end=\\"2683\\">Hi [[first_name]],<br><br></p>\\n<p data-start=\\"2685\\" data-end=\\"2871\\">Thanks for spending time with <strong data-start=\\"2715\\" data-end=\\"2736\\">[[business_name]]</strong> — we hope you had a great experience!<br><br data-start=\\"2774\\" data-end=\\"2777\\">\\nWe\u2019d love to hear your thoughts. Your feedback helps us improve and means a lot to our team.<br><br></p>\\n<p data-start=\\"2873\\" data-end=\\"2922\\">💬 Leave a quick review → [Leave a review link]<br><br></p>\\n<p data-start=\\"2924\\" data-end=\\"3040\\">As a small thank-you, here\u2019s a <strong data-start=\\"2955\\" data-end=\\"2979\\">[[discount_amount]]%</strong> discount code for your next visit:&nbsp;<strong data-start=\\"3017\\" data-end=\\"3038\\">[[discount_code]]<br><br></strong></p>\\n<p data-start=\\"3042\\" data-end=\\"3105\\">We hope to see you again soon,<br data-start=\\"3072\\" data-end=\\"3075\\">\\n<strong data-start=\\"3075\\" data-end=\\"3105\\">The [[business_name]] Team</strong></p>", "alignment": "left"}]',
                'whenToSend' => 'after_checkout',
                'days' => 2,
                'time' => '12:00',
            ],
        ];

        return $emailTemplates;
    }

    private function getSampleTemplateData($key)
    {
        $templates = self::sampleTemplateData();
        return $templates[$key] ?? null;
    }

}
