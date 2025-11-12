<tr>
    <td align="center" style="padding: 0;">
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px;">
            <tr>
                <td style="padding: 20px 0;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="column" width="50%" valign="top" style="padding-right: 10px;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    @foreach($block['blocks']['column1'] as $columnBlock)
                                        @includeIf("admin.builder.email-blocks.".$columnBlock['type'], ['block' => $columnBlock])
                                    @endforeach
                                </table>
                            </td>

                            <td class="column" width="50%" valign="top" style="padding-left: 10px;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    @foreach($block['blocks']['column2'] as $columnBlock)
                                        @includeIf("admin.builder.email-blocks.".$columnBlock['type'], ['block' => $columnBlock])
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
