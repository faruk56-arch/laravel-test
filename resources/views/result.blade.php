@include('header')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 order-md-1">
            <h3 class="mb-3">{{trans('messages.payment_result')}}</h3>
            <hr>

            @if(session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{session('error')}}</div>
            @endif

            @if(count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="p-0 m-0" style="list-style: none;">
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if(session('error'))
                <a href="javascript:history.back()" class="btn btn-primary my-5">{{trans('messages.back_to_payment_page')}}</a>
            @else
                <a href="{{route('home', ['locale' => app()->getLocale()])}}" class="btn btn-primary my-5">{{trans('messages.back_to_homepage')}}</a>
            @endif
        </div>
    </div>

</div>


@include('footer')

