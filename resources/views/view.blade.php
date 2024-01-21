<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Form</title>
</head>
<body>
    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="/submit-form">
        @csrf
        <!-- Your form fields go here -->

        <button type="submit">Submit</button>
    </form>
</body>
</html>