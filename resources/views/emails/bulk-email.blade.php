<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .content {
            padding: 20px 0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
    </div>

    <div class="content">
        @if ($recipientName)
            <p>Dear {{ $recipientName }},</p>
        @else
            <p>Hello,</p>
        @endif

        {!! nl2br(e($campaignBody)) !!}
    </div>

    <div class="footer">
        <p>You received this email because you subscribed to our mailing list.</p>
        <p>If you no longer wish to receive these emails, you can unsubscribe.</p>
    </div>
</body>

</html>
