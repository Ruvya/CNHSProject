<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting...</title>
    <meta http-equiv="refresh" content="0;url={{ route('principal.index') }}">
</head>
<body>
    <script>
        window.location.href = "{{ route('principal.index') }}";
    </script>
    <p>If you are not redirected automatically, <a href="{{ route('principal.index') }}">click here</a>.</p>
</body>
</html>