@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/0091.jpg') }}');">
            <div class="bg-overlay" data-style="10"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->

                    <h1>{{ __('Documentation') }}</h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->


    <section>
        <div class="container">


            <div class="row justify-center flex">
                @if (count($documents) > 0)
                    <h2 class="col-md-8">{{__('ATC')}}</h2>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>
                                                <strong>{{__('Document')}}</strong>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <strong>{{__('Download')}}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                @foreach ($documents as $document)
                                


                                    
                                
                                    @if($document->type == "controller")
                                    <tr>
                                        <td>
                                            <p>{{ $document->name}}</p>
                                        </td>
                                        <td>
                                            <a href="{{ asset($document->route ) }}" target="_blank">PDF</a>
                                        </td>
                                    </tr>                  
                                    @endif
                                @endforeach      
                                </tbody>
                            </table>
                        </div>
                        <h2 class="col-md-8">{{__('Pilot')}}</h2>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>
                                                <strong>{{__('Document')}}</strong>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <strong>{{__('Download')}}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                @foreach ($documents as $document)
                                    @if($document->type == "pilot")
                                    <tr>
                                        <td>
                                            <p>{{ $document->name}}</p>
                                        </td>
                                        <td>
                                            <a href="{{ asset($document->route ) }}" target="_blank">PDF</a>
                                        </td>
                                    </tr>                  
                                    @endif
                                @endforeach           
                                </tbody>
                            </table>
                            @else
                            <h3>No hay ningun documento disponible ahora mismo</h3>
                            @endif
                        </div>              
                </div>
            </div>
        </div>
    </section>


@endsection
