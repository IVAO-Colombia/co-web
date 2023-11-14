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
                <div class="atc_container col-md-6">
                    <h2>{{__('ATC')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "ATC")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pilot_container col-md-6">
                    <h2>{{__('Pilot')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "PILOT")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="traningAtc_container col-md-6">
                    <h2>{{__('Traning ATC')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "TRAINING ATC")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="traningPilot_container col-md-6">
                    <h2>{{__('Traning Pilot')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "TRAINING PILOT")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mtp_container col-md-6">
                    <h2>{{__('Master The Topic')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "MASTER THE TOPIC")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="circular_container col-md-6">
                    <h2>{{__('Circular')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "CIRCULAR")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="circular_container col-md-6">
                    <h2>{{__('Local Regulations')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "LOCAL REGULATIONS")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="circular_container col-md-6">
                    <h2>{{__('OACI')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "OACI")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="circular_container col-md-6">
                    <h2>{{__('For all')}}</h2>
                    <div>
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
                                @foreach ($documents as $item)
                                @if($item->type == "ALL")
                                    <tr>
                                        <td>
                                            <p title="{{$item->description}}">{{ $item->name}}</p>
                                        </td>
                                        @if ($item->file != NULL)
                                        <td>
                                            <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                        @if ($item->url != NULL)
                                        <td>
                                            <a href="{{ $item->url }}" target="_blank" >PDF</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection
