@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative h-40" data-height-xs="360">
        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/skmd.jpeg') }}');">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->
                    <span class="strong">FRA</span>
                    <h1> <b>¿Cual aeropuerto puedo controlar? </b></h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->

    <section>
        <div class="container">
            <div class="row justify-content-around">
                <div class="col-md-12 my-4 wow fadeLeft">
                    <p class="lead">Los siguientes son los aeropuertos que puedes controlar segun tu rango</p>
                    <div class="table-responsive" id="vue">
                        <table class="table  table-bordered rounded w-100">
                            <thead>
                                <tr class="bg-primary text-white text-center" role="row">
                                    <td rowspan="2" class="sorting_disabled" colspan="1" style="width: 115px;">
                                        Position
                                    </td>
                                    <td colspan="2" align="center">Time</td>
                                    <td colspan="8" align="center">Day</td>
                                    <td align="center">Requirements</td>
                                </tr>
                                <tr class="bg-primary text-white text-center" role="row">
                                    <td class="sorting_disabled" style="width: 95px;">Start
                                    </td>
                                    <td class="sorting_disabled" style="width: 95px;">End</td>
                                    <td class="sorting_disabled">Mon</td>
                                    <td class="sorting_disabled">Tue</td>
                                    <td class="sorting_disabled">Wed</td>
                                    <td class="sorting_disabled">Thu</td>
                                    <td class="sorting_disabled">Fri</td>
                                    <td class="sorting_disabled">Sat</td>
                                    <td class="sorting_disabled">Sun</td>
                                    <td class="sorting_disabled" colspan="1">Date</td>
                                    <td width="105" align="center" class="sorting_disabled" style="width: 120px;">ATC
                                        Rating
                                    </td>
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="item in items" class="text-center">
                                    <td>
                                        <div v-if="item.atcPosition">
                                            @{{ item.atcPosition.composePosition }}
                                        </div>
                                        <div v-if="item.subcenter">
                                            @{{ item.subcenter.composePosition }}
                                        </div>
                                    </td>
                                    <td>@{{ item.startTime }}</td>
                                    <td>@{{ item.endTime }}</td>
                                    <td><span :class="{ 'badge-success': item.dayMon, 'badge-danger': !item.dayMon }"
                                            class="badge uppercase"> <i v-if="item.dayMon" class="icon-check"></i> <i
                                                v-if="!item.dayMon" class="icon-x"></i></span>
                                    </td>
                                    <td><span :class="{ 'badge-success': item.dayTue, 'badge-danger': !item.dayTue }"
                                            class="badge uppercase"> <i v-if="item.dayTue" class="icon-check"></i> <i
                                                v-if="!item.dayTue" class="icon-x"></i></span>
                                    </td>
                                    <td><span :class="{ 'badge-success': item.dayWed, 'badge-danger': !item.dayWed }"
                                            class="badge uppercase"> <i v-if="item.dayWed" class="icon-check"></i> <i
                                                v-if="!item.dayWed" class="icon-x"></i></span>
                                    </td>
                                    <td><span :class="{ 'badge-success': item.dayThu, 'badge-danger': !item.dayThu }"
                                            class="badge uppercase"> <i v-if="item.dayThu" class="icon-check"></i> <i
                                                v-if="!item.dayThu" class="icon-x"></i></span>
                                    </td>
                                    <td><span :class="{ 'badge-success': item.dayFri, 'badge-danger': !item.dayFri }"
                                            class="badge uppercase"> <i v-if="item.dayFri" class="icon-check"></i> <i
                                                v-if="!item.dayFri" class="icon-x"></i></span>
                                    </td>
                                    <td><span :class="{ 'badge-success': item.daySat, 'badge-danger': !item.daySat }"
                                            class="badge uppercase"> <i v-if="item.daySat" class="icon-check"></i> <i
                                                v-if="!item.daySat" class="icon-x"></i></span>
                                    </td>
                                    <td><span :class="{ 'badge-success': item.daySun, 'badge-danger': !item.daySun }"
                                            class="badge uppercase"> <i v-if="item.daySun" class="icon-check"></i> <i
                                                v-if="!item.daySun" class="icon-x"></i></span>
                                    </td>
                                    <td>@{{ item.date }}</td>
                                    <td><img :src="`https://www.ivao.aero/data/images/ratings/atc/${item.minAtc}.gif`"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>
@endpush
