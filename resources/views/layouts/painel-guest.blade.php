<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        @include('layouts.partials.head', ['titulo' => $titulo])
    </head>
    <body class="bg-paper font-sans leading-normal text-ink antialiased" x-data>
        {{ $slot }}
    </body>
</html>
