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
            <div class="row ">
                <div class="col-12 mb-2">
                    <div class="table-responsive" id="vue">


                        <table class="border--round">
                            <thead>
                                <tr class="bg--primary text-white" role="row">
                                    <td rowspan="2" class="sorting_disabled" colspan="1" style="width: 82px;">Position
                                    </td>
                                    <td colspan="2" align="center" rowspan="1">Time</td>
                                    <td colspan="8" align="center" rowspan="1">Day</td>
                                    <td align="center" rowspan="1" colspan="1">Requirements</td>
                                </tr>
                                <tr class="bg--primary text-white" role="row">
                                    <td class="sorting_disabled" rowspan="1" colspan="1" style="width: 32px;">Start
                                    </td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1" style="width: 25px;">End</td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1">Mon</td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1">Tue</td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1">Wed</td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1">Thu</td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1">Fri</td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1">Sat</td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1">Sun</td>
                                    <td class="sorting_disabled" rowspan="1" colspan="1">Date</td>
                                    <td width="105" align="center" class="sorting_disabled" rowspan="1" colspan="1"
                                        style="width: 92px;">ATC Rating
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
                                    <td><span class="badge badge-success uppercase">@{{ item.dayMon }}</span></td>
                                    <td><span class="badge badge-success uppercase">@{{ item.dayTue }}</span></td>
                                    <td><span class="badge badge-success uppercase">@{{ item.dayWed }}</span></td>
                                    <td><span class="badge badge-success uppercase">@{{ item.dayThu }}</span></td>
                                    <td><span class="badge badge-success uppercase">@{{ item.dayFri }}</span></td>
                                    <td><span class="badge badge-success uppercase">@{{ item.daySat }}</span></td>
                                    <td><span class="badge badge-success uppercase">@{{ item.daySun }}</span></td>
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
