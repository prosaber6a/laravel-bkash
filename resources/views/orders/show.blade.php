<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Laravel-Bkash Payment Integration</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="card mt-5">
                    <div class="card-header">
                        {{ $order->product_name }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $order->product_name }}</h5>
                        <p class="card-text amount">{{ $order->amount }}</p>
                        <p class="card-text invoice">{{ $order->invoice }}</p>
                        @if ($order->status == 'Pending')
                            <button type="button" class="btn btn-primary" id="bKash_button">Pay with bKash</button>
                        @else
                            <h4><span class="badge badge-success">Paid</span></h4>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"
        integrity="sha512-J9QfbPuFlqGD2CYVCa6zn8/7PEgZnGpM5qtFOBZgwujjDnG5w5Fjx46YzqvIh/ORstcj7luStvvIHkisQi5SKw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script id="myScript" src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js">
    </script>

    <script>
        var accessToken = '';

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                url: "{!! route('bkash.token') !!}",
                type: 'POST',
                contentType: 'application/json',
                success: function(data) {
                    console.log('got data from token  ..');
                    console.log(data);
                    accessToken = JSON.stringify(data);
                },
                error: function(res) {
                    console.log(res, 'error');

                }
            });

            var paymentConfig = {
                createCheckoutURL: "{!! route('bkash.createpayment') !!}",
                executeCheckoutURL: "{!! route('bkash.executepayment') !!}",
            };


            var paymentRequest;
            paymentRequest = {
                amount: $('.amount').text(),
                intent: 'sale',
                invoice: $('.invoice').text(),
            };
            console.log(JSON.stringify(paymentRequest));



            bKash.init({
                paymentMode: 'checkout',
                paymentRequest: paymentRequest,
                createRequest: function(request) {
                    console.log('=> createRequest (request) :: ');
                    console.log(request);

                    $.ajax({
                         url: paymentConfig.createCheckoutURL + "?amount=" + paymentRequest.amount + "&invoice=" + paymentRequest.invoice,

                        type: 'GET',
                        contentType: 'application/json',
                        success: function(data) {
                            console.log('got data from create  ..');
                            console.log('data ::=>');
                            console.log(data);
                            console.log(JSON.stringify(data));

                            var obj = JSON.parse(data);

                            if (data && obj.paymentID != null) {
                                paymentID = obj.paymentID;
                                bKash.create().onSuccess(obj);
                            } else {
                                console.log('error');
                                bKash.create().onError();
                            }
                        },
                        error: function(res) {
                            console.log(res);
                            console.log('error');
                            bKash.create().onError();
                        }
                    });
                },

                executeRequestOnAuthorization: function() {
                    console.log('=> executeRequestOnAuthorization');
                    $.ajax({
                        url: paymentConfig.executeCheckoutURL + "?paymentID=" + paymentID,
                        type: 'GET',
                        contentType: 'application/json',
                        success: function(data) {
                            console.log('got data from execute  ..');
                            console.log('data ::=>');
                            console.log(JSON.stringify(data));

                            data = JSON.parse(data);
                            if (data && data.paymentID != null) {
                                alert('[SUCCESS] data : ' + JSON.stringify(data));
                                window.location.href = "success.html";
                            } else {
                                bKash.execute().onError();
                            }
                        },
                        error: function() {
                            bKash.execute().onError();
                        }
                    });
                }
            });

            console.log("Right after init ");


        });

        function callReconfigure(val) {
            bKash.reconfigure(val);
        }

        function clickPayButton() {
            $("#bKash_button").trigger('click');
        }
    </script>
</body>

</html>
