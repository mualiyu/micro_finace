@extends('layouts.index')

@section('content')
<div class="container-fluid gtco-banner-area">
    <div class="container">
	    @include('layouts.flash')
        <div class="row">
           <div class="col-lg-8" id="contact">
		   <form action="{{route('transfer_m')}}" method="POST">
			@csrf
			<input type="hidden" name="source" value="{{Auth::user()->account}}">
			<h4> Transfer Money </h4><span style="float: right; font-weight:bold; color:white;">₦{{Auth::user()->amount ?? '0'}}</span>
			<label for="from">Source Account</label>
			<input id="from" type="text" name="source" class="form-control" placeholder="From Account" value="{{Auth::user()->account}}" disabled required>
			
			<label for="to">Enter Beneficiary Account:</label>
			<input id="to" type="text" name="ben" class="form-control" placeholder="Enter Account number to send money" value="" required>
			<span style="color: green;" id="ben_name"></span>
			<br>
	
			<label for="amount">Amount to send</label>
			<input id="amount" type="number" name="amount" class="form-control" placeholder="Amount" required>
	
			{{-- <input type="email" class="form-control" placeholder="Email Address"> --}}
			<label for="nar">Transaction Narrative</label>
			<textarea class="form-control" name="nar" placeholder="Narration"></textarea>
			<br>
			<button type="submit" style="display:none;" id="aac" class="btn btn-primary submit-button">Submit <i class="fa fa-angle-right" aria-hidden="true"></i></button>
		</form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
	$(document).ready(function () {
             
        $('#to').on('keyup',function() {
            let query = $('#to').val(); 
	    if (query.length == 10) {
            	$.ajax({
		
            	    url:"{{ route('get_ben_data') }}",
		
            	    type:"GET",
		
            	    data:{'ben':query},
		
            	    success:function (data) {
			if (data !== null) {
            	        $('#ben_name').html(data['name']);
			$('#aac').css('display', 'block');
			}
			else{
				$('#ben_name').html("Beneficiary Not Found!");
			}
			    console.log(data['name']);
            	    }
            }).fail(function() {
		    $('#aac').css('display', 'none');
  		  	$('#ben_name').html("Beneficiary Not Found!");
  		})
	    }else{
		     $('#aac').css('display', 'none');
	    }
            // end of ajax call
        });

        // $('#submit').on('click',function() {
        //     console.log(val);
        // });
        
    });
    
//     function generate_t_sumary() {
//         var val = [];
//         $('input[type=checkbox]:checked').each(function(i){
//           val[i] = $(this).val();
//         });

//         $('#card_form').addClass("card-load");
//         $('#card_form').append('<div class="card-loader"><i class="fa fa-spinner rotate-refresh"></div>');

    
// }
    </script>
@endsection
