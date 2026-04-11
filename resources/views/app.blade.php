<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,900;1,900&display=swap" rel="stylesheet">
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        <style>
            .font-archivo { font-family: 'Archivo', sans-serif; }
        </style>
    </head>
    <body class="antialiased font-archivo">
        @inertia
    </body>
</html>