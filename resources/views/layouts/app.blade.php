<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Puskesmas Digital')</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "secondary": "#006c49", "surface-container": "#e5eeff", "surface": "#f8f9ff",
                    "on-surface": "#0b1c30", "on-surface-variant": "#434655",
                    "error-container": "#ffdad6", "error": "#ba1a1a",
                    "secondary-container": "#6cf8bb", "on-secondary-container": "#00714d",
                    "outline-variant": "#c3c6d7", "surface-variant": "#d3e4fe",
                    "on-primary": "#ffffff", "outline": "#737686",
                    "surface-container-highest": "#d3e4fe", "surface-container-lowest": "#ffffff",
                    "inverse-surface": "#213145", "on-secondary": "#ffffff",
                    "background": "#f8f9ff", "on-primary-container": "#eeefff",
                    "tertiary-container": "#996100", "surface-container-lowest": "#ffffff",
                    "tertiary-fixed": "#ffddb8", "on-tertiary-fixed-variant": "#653e00",
                    "on-error-container": "#93000a", "inverse-primary": "#b4c5ff",
                    "tertiary": "#784b00", "surface-container-high": "#dce9ff",
                    "on-background": "#0b1c30", "primary": "#004ac6", "primary-container": "#2563eb",
                    "primary-fixed": "#dbe1ff", "primary-fixed-dim": "#b4c5ff",
                    "surface-dim": "#cbdbf5", "on-secondary-fixed": "#002113",
                    "secondary-fixed": "#6ffbbe", "on-error": "#ffffff",
                },
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                fontSize: {
                    "label-sm":  ["12px", { lineHeight:"16px",  fontWeight:"500" }],
                    "label-lg":  ["14px", { lineHeight:"20px",  fontWeight:"600" }],
                    "headline-md":["24px",{ lineHeight:"32px",  fontWeight:"600" }],
                    "headline-lg":["32px",{ lineHeight:"40px",  fontWeight:"700" }],
                    "display-lg": ["48px",{ lineHeight:"56px",  fontWeight:"700" }],
                    "body-md":    ["16px",{ lineHeight:"24px",  fontWeight:"400" }],
                },
            },
        },
    }
</script>
<style>
    body { font-family: 'Inter', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    @keyframes float { from{transform:translate(0,0)} to{transform:translate(50px,100px)} }
    .floating-blob { animation: float 20s infinite alternate ease-in-out; }
</style>
@stack('styles')
</head>
<body class="bg-background text-on-background min-h-screen @yield('body-class')">
@yield('content')
@stack('scripts')
</body>
</html>
