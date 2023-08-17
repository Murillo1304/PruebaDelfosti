<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Bienvenido a Laravel - Ejercicio Integración</h1>
    <a href="{{ url('pruebaToken') }}?token=123456" class="btn btn-primary">Ir a prueba token</a>
    <h1></h1>
    @php

        // dd(base_path('vendor/autoload.php'));
        // require_once __DIR__ . '/vendor/autoload.php';
        require base_path('/vendor/autoload.php');
        /**
         * Define configuration
         */

        /* Username, password and endpoint used for server to server web-service calls */
        Lyra\Client::setDefaultUsername(config('services.izipay.code_user'));
        Lyra\Client::setDefaultPassword(config('services.izipay.password'));
        // prod prodpassword_U3AmZdtfezRmRhLEdUqxKnW4TKfsYHetDDanD5RW37Yh2
        Lyra\Client::setDefaultEndpoint('https://api.micuentaweb.pe');

        Lyra\Client::setDefaultPublicKey(config('services.izipay.key'));
        Lyra\Client::setDefaultSHA256Key(config('services.izipay.hash'));

        // NDg3MTY5NDg6dGVzdHBhc3N3b3JkX3pEUnlLMnpYTTlERkVGTkdVUFAwUDRvVXdVVEJKS21OdWM0ajlSYnc4SURmZg==

        $client = new Lyra\Client();

        /**
         * I create a formToken
         */
        $store = [
            'amount' => 200,
            'currency' => 'PEN',
            'orderId' => uniqid(101),
            'customer' => [
                'email' => 'josecarlos130498@gmail.com',
            ],
        ];
        header('Authorization', 'NDg3MTY5NDg6dGVzdHBhc3N3b3JkX3pEUnlLMnpYTTlERkVGTkdVUFAwUDRvVXdVVEJKS21OdWM0ajlSYnc4SURmZg==');
        header('Content-Type', 'application/json');
        $response = $client->post('V4/Charge/CreatePayment', $store);

        /* I check if there are some errors */
        if ($response['status'] != 'SUCCESS') {
            /* an error occurs, I throw an exception */
            display_error($response);
            $error = $response['answer'];
            throw new Exception('error ' . $error['errorCode'] . ': ' . $error['errorMessage']);
        }

        /* everything is fine, I extract the formToken */
        $formToken = $response['answer']['formToken'];
    @endphp

    <div id="paymentForm" class="kr-embedded" kr-form-token="{{ $formToken }}">

        <div class="kr-pan"></div>
        <div class="kr-expiry"></div>
        <div class="kr-security-code"></div>

        <button class="kr-payment-button"></button>

        <div class="kr-form-error"></div>
    </div>

    <script src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
            kr-public-key="{{ config('services.izipay.key') }}"
            kr-post-url-success="{{ route('payments.try') }}" kr-language="es-ES"></script>

    <link rel="stylesheet" href="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/classic-reset.css">
    <script src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/classic.js"></script>
</body>
</html>