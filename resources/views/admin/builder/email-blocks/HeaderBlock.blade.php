<tr>


    <td
        background="{{$hotel->featured_image}}"
        class=""
        style="background:url({{$hotel->featured_image}}) no-repeat center center / cover; height: 250px; width:100%;"
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
                                <img src="{{$hotel->logo}}" alt="hotel"
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
