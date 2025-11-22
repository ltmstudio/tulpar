<!DOCTYPE html>
<html>
<head>
    <title>Авторизация через Google</title>
</head>
<body>
    <script>
        const data = @json([
            'success' => $success,
            'data' => $success ? [
                'token' => $token,
                'profile' => $profile
            ] : null,
            'error' => $error ?? null
        ]);

        // Сохраняем данные в window для извлечения
        window.authData = data;

        // Для webview_flutter используем JavaScript channel
        if (window.GoogleAuthChannel) {
            // Для webview_flutter через JavaScript channel
            window.GoogleAuthChannel.postMessage(JSON.stringify(data));
        } else if (window.flutter_inappwebview) {
            // Для flutter_inappwebview (если используется)
            window.flutter_inappwebview.callHandler('onGoogleAuthCallback', JSON.stringify(data));
        } else {
            // Fallback: используем URL scheme
            window.location.href = 'tulpar://auth/google?data=' + encodeURIComponent(JSON.stringify(data));
        }
    </script>
</body>
</html>
