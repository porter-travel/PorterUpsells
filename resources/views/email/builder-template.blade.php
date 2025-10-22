<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Preview</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color: #f6f6f6;" bgcolor="#f6f6f6">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 660px; width:100%; margin:0 auto; background-color: #ffffff; padding:20px; box-sizing: border-box;">

    @foreach($body as $block)
        @includeIf("admin.builder.email-blocks.".$block['type'], ['block' => $block, 'hotel' => $hotel])
    @endforeach
</table>
</body>
</html>
