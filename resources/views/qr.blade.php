<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body class="app">
    @include('partials.header')
    @include('components.upload-menu')
    @include('components.tables.generateQr')
    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container">
                <center><h3>Generate Qr For Table <i class="fa-solid fa-qrcode"></i></h3></center>
                <div class="row g-3 mt-3">
                    <!-- Loop to dynamically generate cards -->
                    @foreach ($data as $qr)
                    <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                        <div class="dropdown">
                            <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="card text-center" style="width: 100px; height: 100px; margin: auto;">
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <h5 class="card-title">{{ $qr['tableNumber'] }}</h5>
                                    </div>
                                </div>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="/deleteQr/{{$qr['id']}}">Delete</a></li>
                                <li><a class="dropdown-item" href="{{$qr['qrCodeUrl']}}">Download</a></li>
                            </ul>
                        </div>
                    </div>
                    @endforeach

                    <!-- Add New QR Card -->
                    <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                        <div class="card text-center" style="width: 100px; height: 100px; margin: auto;cursor: pointer;" data-bs-toggle="modal" data-bs-target="#qrModal">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h5 class="card-title">+</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--//container-->
        </div><!--//app-content-->
    </div><!--//app-wrapper-->
    @include('partials.footer')
</body>
</html>
