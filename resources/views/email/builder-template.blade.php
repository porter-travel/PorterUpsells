<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Preview</title>
    <style>
        /* Desktop layout defaults */
        .product-td {
            width: 50% !important;
            max-width: 50% !important;
            min-width: 280px !important; /* Forces the td to be wide enough to prefer stacking on small screens */
        }

        /* Mobile Stacking Media Query */
        @media only screen and (max-width: 600px) {
            .product-td {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 100% !important; /* Ensures full width on mobile */
            }
            .img-container {
                height: 300px !important; /* Enforce a fixed height on mobile */
                overflow: hidden !important;
            }
            .img-container img {
                max-width: 100% !important;
                height: 100% !important;
            }
        }
    </style>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color: #f6f6f6;" bgcolor="#f6f6f6">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 660px; width:100%; margin:0 auto; background-color: #ffffff; padding:20px; box-sizing: border-box;">

    @foreach($body as $block)
        @includeIf("admin.builder.email-blocks.".$block['type'], ['block' => $block, 'hotel' => $hotel])
    @endforeach
</table>
</body>
</html>
