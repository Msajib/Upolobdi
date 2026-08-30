<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0; url={{ route('home') }}">
    <title>Redirecting...</title>
    <script>
        window.location.href = "{{ route('home') }}";
    </script>
</head>
<body style="background:#020617; color:white; display:flex; align-items:center; justify-content:center; height:100vh; font-family:sans-serif; margin:0;">
    <div style="text-align:center;">
        <p style="font-size:16px;">পুনর্নির্দেশ করা হচ্ছে...</p>
        <a href="{{ route('home') }}" style="color:#d4af37; text-decoration:none; font-weight:bold;">হোম পেজে যেতে এখানে ক্লিক করুন</a>
    </div>
</body>
</html>
