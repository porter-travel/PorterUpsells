<tr>
    <td>
        <table>
            <tr>
                @foreach ($block['products'] as $product)
                    <td align="center" valign="top" style="padding: 10px; width: 50%; max-width: 50%;">
                        @if ($product)
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                   style="border-collapse: collapse; border: 1px solid #ddd; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <tr>
                                    <td style="padding: 0;">
                                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" width="100%"
                                             style="display: block; width: 100%; height: auto; border-radius: 12px 12px 0 0;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background: #000; color: #fff; padding: 8px 10px; text-align: left; border-radius: 0 0 12px 12px;">
                                        <p style="margin: 0; font-size: 14px; font-weight: bold;">{{ $product['name'] }}</p>
                                        <p style="margin: 0; font-size: 12px;">
                                            £{{ number_format($product['price'], 2) }}</p>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                   style="border-collapse: collapse; border: 2px dashed #ccc; border-radius: 12px;">
                                <tr>
                                    <td align="center" valign="middle"
                                        style="padding: 40px; color: #999; font-size: 14px;">
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </td>

                    @if (($loop->iteration % 2) == 0)
            </tr>
            <tr>
                @endif
                @endforeach
            </tr>
        </table>
    </td>
</tr>
