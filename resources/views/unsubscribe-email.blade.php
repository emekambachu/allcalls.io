<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe From AllCalls.io</title>
    <link href="//cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen items-center justify-center">
        <div class="mt-10 rounded-md bg-white px-8 py-6 text-left shadow-lg">
            <h3 class="text-center text-2xl font-bold">Unsubscribe AllCalls.io Emails</h3>

            <!-- Flash Messages -->
            @if (session('message'))
                <div class="p-4 my-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 my-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('unsubscribe-to-emails.store', $token) }}" method="POST">
                @csrf
                <div class="mt-4">
                    <div>
                        <label class="block" for="email">Email Address</label>
                        <input type="email" placeholder="Enter your email" name="email" class="mt-2 w-full rounded-md border px-4 py-2 focus:outline-none focus:ring-1 focus:ring-blue-600" />
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <button class="rounded-lg bg-blue-600 px-6 py-2 text-white hover:bg-blue-900">Unsubscribe</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>

</html>