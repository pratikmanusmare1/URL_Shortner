<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invitation</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .btn { background:#1d68a7;color:#fff;padding:10px 14px;text-decoration:none;border-radius:4px }
    </style>
</head>
<body>
    <h2>You are invited to join {{ $invitation->company->name }}</h2>
    <p>Role: <strong>{{ ucfirst($invitation->role) }}</strong></p>
    <p>Click the button below to accept the invitation:</p>
    <p><a class="btn" href="{{ $acceptUrl }}">Accept Invitation</a></p>
    <p>If the button doesn't work, open this link:</p>
    <p>{{ $acceptUrl }}</p>
</body>
</html>
