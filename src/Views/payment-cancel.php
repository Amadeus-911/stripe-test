<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="max-w-[1200px] mx-auto my-20">
        <div class="bg-white shadow p-6 rounded-xl text-center">
            <div class="mb-4 text-red-500">
                <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold mb-4">Payment Cancelled</h1>
            <p class="text-gray-600 mb-6"><?php echo $message; ?></p>
            <a href="/checkout" class="inline-block bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                Try Again
            </a>
        </div>
    </div>
</body>
</html>