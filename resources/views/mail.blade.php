<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap');

        body {
            font-family: 'Libre Baskerville', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        table {
            border-spacing: 0;
            margin: auto;
            /* Center the table */
        }

        td {
            padding: 0;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
        }

        .email-header {
            background-color: #000000;
            color: #ffffff;
            padding: 10px;
            text-align: center;
        }

        .email-body {
            padding: 20px;
            background-color: #ffffff;
        }

        .email-footer {
            padding: 10px;
            text-align: center;
            background-color: #dddddd;
        }
    </style>
</head>

<body>
    <table class="email-container" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="email-header">
                <h1>{{ env('APP_NAME', 'Revista Phantasma') }}</h1>
            </td>
        </tr>
        <tr>
            <td class="email-body">
                <p><strong>Nombre:</strong> {{ $name }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                {{-- <p><strong>Mensaje:</strong><br>{{ $userMessage }}</p> --}}
            </td>
        </tr>
        <tr>
            <td class="email-footer">
                <p>Thank you for using our application!</p>
            </td>
        </tr>
    </table>
</body>

</html>