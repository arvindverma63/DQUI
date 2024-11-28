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
                <center>
                    <h3>Generate Qr For Table <i class="fa-solid fa-qrcode"></i></h3>
                </center>
                <div class="row g-3 mt-3">
                    <!-- Loop to dynamically generate cards -->
                    @foreach ($data as $qr)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                            <div class="dropdown">
                                <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <div class="card text-center" style="width: 100px; height: 100px; margin: auto;">
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <h5 class="card-title">{{ $qr['tableNumber'] }}</h5>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="/deleteQr/{{ $qr['id'] }}">Delete</a></li>
                                    <li data-bs-toggle="modal" data-bs-target="#exampleModal{{ $qr['id'] }}">
                                        <a class="dropdown-item">Download</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{ $qr['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Download QR</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{ $qr['qrCodeUrl'] }}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="downloadQRCode('{{ $qr['qrCodeUrl'] }}')">Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Add New QR Card -->
                    <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                        <div class="card text-center" style="width: 100px; height: 100px; margin: auto;cursor: pointer;"
                            data-bs-toggle="modal" data-bs-target="#qrModal">
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

    <script>
        function downloadQRCode(url) {
            const link = document.createElement('a');
            link.href = url;
            link.download = 'qr-code.png';
            link.click();
        }
    </script>
</body>

</html>
