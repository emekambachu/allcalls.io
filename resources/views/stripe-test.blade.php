<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap');

        ::selection {
            background: #87CEEB;
        }

        * {
            font-family: Poppins, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        #container {
            width: 700px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        label {
            color: #333;
            /* A little lighter than black */
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <div id="container">
        <div style="margin-bottom: 20px;">
            <label for="card-holder-name">Your Full Name:</label>
            <input id="card-holder-name" type="text"
                style="padding: 10px; margin-top: 10px; width: 100%; box-sizing: border-box;" placeholder="John Doe">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="amount">Amount:</label>
            <input id="amount" type="text"
                style="padding: 10px; margin: 10px 0; width: 100%; box-sizing: border-box;" placeholder="100.00">
        </div>

        <!-- Stripe Elements Placeholder -->
        <label for="amount">Card Information:</label>
        <div id="card-element"
            style="padding: 10px; margin: 10px 0; width: 100%; box-sizing: border-box; border: 1px solid #ccc;"></div>

        <button id="card-button"
            style="background-color: #007BFF; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 10px 0; display: block; width: 100%; box-sizing: border-box;">
            Process Payment
        </button>
    </div>
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe(
            'pk_test_51JUMhZF43egAbbxbdvc4FIRiALFxHyYECIknypspzMqjYBQ47Kvt8TBY3g44gfhIQHJLPQT4GMwcqlqN1KwKPsbc00UfQoy1mu'
            );

        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');

        cardButton.addEventListener('click', async (e) => {
            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod(
                'card', cardElement, {
                    billing_details: {
                        name: cardHolderName.value
                    }
                }
            );

            if (error) {
                console.log(error);
            } else {
                console.log('card has been verified successfully');
                window.location.href = '/stripe-test-redirect?paymentMethodId=' + paymentMethod.id + '&amount=' + document.getElementById('amount').value;
            }
        });
    </script>
</body>

</html>
