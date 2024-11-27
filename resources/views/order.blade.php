<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body class="app">
    @include('partials.header')

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            @include('components.order-table');
        </div>


    </div>

    @include('partials.footer')

</body>
</html>
