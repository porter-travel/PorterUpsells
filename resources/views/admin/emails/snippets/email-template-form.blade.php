<div class="border rounded-3xl border-darkGrey p-4">

    <table class="body-wrap"
           style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;"
           bgcolor="#f6f6f6">
        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                valign="top"></td>
            <td class="container" width="800"
                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 800px !important; clear: both !important; margin: 0 auto;"
                valign="top">
                <div class="content"
                     style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 800px; display: block; margin: 0 auto; padding: 20px;">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0"
                           style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;"
                           bgcolor="#fff">
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td
                                background="{{$hotel->featured_image}}"
                                class=""
                                style="background:url({{$hotel->featured_image}}) no-repeat center center / cover; height: 250px;"
                                align="center" bgcolor="" valign="top">
                                <table
                                    style="width: 100%; background: linear-gradient(0deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);">
                                    <tr>
                                        <td style="height: 150px">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; padding: 16px">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <img src="{{$hotel->logo}}"
                                                             alt="hotel"
                                                             style="width: 100px; height: auto; border-radius: 4px; object-fit: contain"/>

                                                    </td>
                                                    <td>
                                                        <p
                                                            style="color: white; font-size: 24px; font-weight: bold; margin-left: 16px">{{$hotel->name}}</p>

                                                    </td>
                                                </tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 16px">
                                <p class="text-xl">Key Message</p>
                                <textarea
                                    name="hotel_email[key-message]"
                                    cols="10" rows="10"
                                    class="w-full h-[200px] rounded-2xl">{{$email_content->key_message}}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 16px; text-align: center">
                                                        <span
                                                            style="background-color: {{$hotel->button_color}}; font-weight: bold; padding: 16px; text-decoration: none; border-radius: 4px">
                                                            <x-text-input
                                                                name="hotel_email[button-text]"
                                                                type="text"
                                                                value="{{$email_content->button_text}}"/></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px">
                                <p class="text-xl">Highlight Specific Products</p>
                                <table style="width: 100%; table-layout: fixed">
                                    <tr>

                                        @foreach($email_content->featured_products as $key => $product)
                                            <td style="padding: 8px; vertical-align: top; position: relative">
                                                <a data-slot-id="{{$key}}"
                                                   class=" featuredProductLink" href="#">
                                                    @if((is_numeric($product) && ($product == 0 || $product == '0')) || $product == null)
                                                        <div
                                                            class=" flex pointer-events-none items-center justify-center border-darkGrey border rounded-2xl h-[150px] p-4">
                                                            <img src="/img/icons/plus.svg"
                                                                 alt="plus"
                                                                 class=" w-8 h-8 pointer-events-none"/>


                                                        </div>
                                                    @else

                                                        <div class="">
                                                            <img src="{{$product->image}}"
                                                                 alt="{{$product->name}}"
                                                                 class="w-full h-[150px] object-cover rounded-2xl"/>
                                                            <p class="left">{{$product->name}}</p>
                                                            <strong>{{\App\Helpers\Money::lookupCurrencySymbol($hotel->user->currency)}}{{Money::format($product->price)}}</strong>
                                                        </div>
                                                    @endif


                                                    <input type="hidden"
                                                           name="hotel_email[featured-products][]"
                                                           value="{{$product ? $product->id : null}}">
                                                </a>

                                                <a href="#">
                                                    <img src="/img/icons/remove.svg"
                                                         alt="remove"
                                                         data-remove-slot-id="{{$key}}"
                                                         class="remove-icon absolute top-0 right-0 w-6 h-6"/>
                                                </a>
                                            </td>

                                        @endforeach


                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 16px">
                                <p class="text-xl">Additional Information</p>
                                <textarea name="hotel_email[additional-information]"
                                          cols="10" rows="10"
                                          class="w-full h-[200px] rounded-2xl">{{$email_content['additional_information']}}</textarea>
                            </td>
                        </tr>
                    </table>

                </div>
            </td>
            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                valign="top"></td>
        </tr>
    </table>

</div>
<div class="flex items-end justify-end">
    <x-primary-button dusk="save-pre-arrival-email-setup" class="mt-4">Save
    </x-primary-button>
</div>

