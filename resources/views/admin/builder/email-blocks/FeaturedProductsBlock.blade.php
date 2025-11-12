<tr>
    <td style="padding: 10px 0;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse;">
            <tr>
                @foreach ($block['products'] as $product)

                    {{-- 1. Apply the mobile stacking class and desktop width --}}
                    <td class="product-td" align="center" valign="top"
                        style="padding: 10px; width: 50%; max-width: 50%; min-width: 280px;">

                        @if ($product)
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                   style="border-collapse: collapse; border: 1px solid #ddd; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <tr>
                                    {{-- 2. New fixed-height container TD/DIV --}}
                                    <td class="img-container" style="padding: 0; height: 280px; overflow: hidden; border-radius: 12px 12px 0 0;">

                                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                             width="100%"
                                             height="280" {{-- Explicit height for older clients --}}
                                             style="display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    max-width: 100%;
                                                    object-fit: cover;"> {{-- object-fit still helps modern clients --}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background: #000; color: #fff; padding: 8px 10px; text-align: left; border-radius: 0 0 12px 12px;">
                                        <p style="margin: 0; font-size: 14px; font-weight: bold;">{!! $product['name'] !!}</p>
                                        <p style="margin: 0; font-size: 12px;">
                                            £{{ number_format($product['price'], 2) }}</p>
                                    </td>
                                </tr>
                            </table>
                        @else
                            {{-- Placeholder remains similar --}}
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                   style="border-collapse: collapse; border: 2px dashed #ccc; border-radius: 12px;">
                                <tr>
                                    {{-- Keep consistent height for placeholder as well --}}
                                    <td align="center" valign="middle"
                                        style="height: 280px; padding: 40px 0; color: #999; font-size: 14px;">
                                        Empty Slot
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </td>

                    @if ($loop->even && !$loop->last)
            </tr>
            <tr>
                @endif
                @endforeach

                @if (($loop->count % 2) != 0)
                    <td class="product-td" style="width: 50%; padding: 10px;">&nbsp;</td>
                @endif

            </tr>
        </table>
    </td>
</tr>
