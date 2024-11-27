<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body class="app">
    @include('partials.header')
    <div class="app-wrapper">

	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    <div class="row g-3 mb-4 align-items-center justify-content-between">
				    <div class="col-auto">
			            <h1 class="app-page-title mb-0">My Category</h1>
				    </div>
				    <div class="col-auto">
					     <div class="page-utilities">
						    @include('components.upload-category')
					    </div><!--//table-utilities-->
				    </div><!--//col-auto-->
			    </div><!--//row-->

			    <div class="row g-4 mt-4">



				    @include('components.getCategory')

		    </div><!--//container-fluid-->
	    </div><!--//app-content-->



    </div><!--//app-wrapper-->
    @include('partials.footer')

</body>
</html>
