<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection ('title')
            @yield('title')
        @else
           {{config('app.name', 'Laravel')}}
        @endif
    </title>
    <script>
        // TODO: remove
        console.log('you are tracking style nodes creation');

        (() => {
            const mask = /^\ssvg/;
            const handleMatch = (el) => {
                throw new Error('USED TO SHOW STACK TRACE');
            };

            const originalCreateElement = document.createElement;

            function customCreateElement(...args) {
                if (args[0] !== 'style') {
                    return originalCreateElement.call(document, ...args);
                }

                const element = originalCreateElement.call(document, ...args);
                const originalAppendChild = element.appendChild;

                function customAppendChild(...args) {
                    const appendedText = args[0].data;

                    if (appendedText.match(mask)) {
                        handleMatch(element);
                    };
                    originalAppendChild.call(element, ...args);
                }

                element.appendChild = customAppendChild;

                return element;
            }

            document.createElement = customCreateElement;
        })();
    </script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="body">
@yield('content')


<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>

</body>
</html>
