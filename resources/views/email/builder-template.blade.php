<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Preview</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    @foreach($blocks as $block)
        @includeIf("admin.builder.blocks.".$block['type'], ['block' => $block])
    @endforeach
</table>
</body>
</html>
