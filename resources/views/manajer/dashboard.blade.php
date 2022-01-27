@extends('layout/manajer')

@section('title', 'Dashboard Manajer')
@section('content')

    <div class="card text-center mb-10">
        <div class="card-header">
            <h1>Selamat Datang di Halaman Manajer</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <!--begin::Tiles Widget 4-->
            <div class="card card-custom gutter-b" style="height: 130px">
                <a href="/manajer/sales/table">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column">
                        <!--begin::Stats-->
                        <div class="flex-grow-1">
                            <div class="text-dark-50 font-weight-bold">Total Sales</div>
                            <div class="text-dark font-weight-bolder font-size-h3">{{ $sales }} Sales</div>
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Body-->
                </a>
            </div>
            <!--end::Tiles Widget 4-->
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <!--begin::Tiles Widget 4-->
            <div class="card card-custom gutter-b" style="height: 130px">
                <a href="/manajer/merchant/table">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column">
                        <!--begin::Stats-->
                        <div class="flex-grow-1">
                            <div class="text-dark-50 font-weight-bold">Total Merchant/Partner</div>
                            <div class="text-dark font-weight-bolder font-size-h3">{{ $merchant }} Merchant/Partner
                            </div>
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Body-->
                </a>
            </div>
            <!--end::Tiles Widget 4-->
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <!--begin::Tiles Widget 2-->
            <div class="card card-custom bg-danger gutter-b" style="height: 130px">
                <!--begin::Body-->
                <a href="/manajer/status/1">
                    <div class="card-body d-flex flex-column p-0">
                        <!--begin::Stats-->
                        <div class="flex-grow-1 card-spacer-x pt-6">
                            <div class="text-inverse-danger font-weight-bold">Status Kunjungan</div>
                            <div class="text-inverse-danger font-weight-bolder font-size-h3">
                                {{ \App\Utilities\Helpers::statusCountDash($aktivitas, 1) ?? '' }} Prospek</div>
                        </div>
                        <!--end::Stats-->
                        <!--begin::Chart-->
                        <div id="kt_tiles_widget_2_chart" class="card-rounded-bottom" style="height: 50px"></div>
                        <!--end::Chart-->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Tiles Widget 2-->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <!--begin::Tiles Widget 2-->
            <div class="card card-custom bg-warning gutter-b" style="height: 130px">
                <!--begin::Body-->
                <a href="/manajer/status/2">
                    <div class="card-body d-flex flex-column p-0">
                        <!--begin::Stats-->
                        <div class="flex-grow-1 card-spacer-x pt-6">
                            <div class="text-inverse-danger font-weight-bold">Status Kunjungan</div>
                            <div class="text-inverse-danger font-weight-bolder font-size-h3">
                                {{ \App\Utilities\Helpers::statusCountDash($aktivitas, 2) ?? '' }} Presentasi/Demo</div>
                        </div>
                        <!--end::Stats-->
                        <!--begin::Chart-->
                        <div id="kt_tiles_widget_2_chart" class="card-rounded-bottom" style="height: 50px"></div>
                        <!--end::Chart-->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Tiles Widget 2-->
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <!--begin::Tiles Widget 2-->
            <div class="card card-custom bg-success gutter-b" style="height: 130px">
                <!--begin::Body-->
                <a href="/manajer/status/3">
                    <div class="card-body d-flex flex-column p-0">
                        <!--begin::Stats-->
                        <div class="flex-grow-1 card-spacer-x pt-6">
                            <div class="text-inverse-danger font-weight-bold">Status Kunjungan</div>
                            <div class="text-inverse-danger font-weight-bolder font-size-h3">
                                {{ \App\Utilities\Helpers::statusCountDash($aktivitas, 3) ?? '' }} Closing Paid</div>
                        </div>
                        <!--end::Stats-->
                        <!--begin::Chart-->
                        <div id="kt_tiles_widget_2_chart" class="card-rounded-bottom" style="height: 50px"></div>
                        <!--end::Chart-->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Tiles Widget 2-->
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <!--begin::Tiles Widget 2-->
            <div class="card card-custom bg-primary gutter-b" style="height: 130px">
                <!--begin::Body-->
                <a href="/manajer/status/4">
                    <div class="card-body d-flex flex-column p-0">
                        <!--begin::Stats-->
                        <div class="flex-grow-1 card-spacer-x pt-6">
                            <div class="text-inverse-danger font-weight-bold">Status Kunjungan</div>
                            <div class="text-inverse-danger font-weight-bolder font-size-h3">
                                {{ \App\Utilities\Helpers::statusCountDash($aktivitas, 4) ?? '' }} Pending</div>
                        </div>
                        <!--end::Stats-->
                        <!--begin::Chart-->
                        <div id="kt_tiles_widget_2_chart" class="card-rounded-bottom" style="height: 50px"></div>
                        <!--end::Chart-->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Tiles Widget 2-->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <!--begin::Tiles Widget 2-->
            <div class="card card-custom bg-info gutter-b" style="height: 130px">
                <!--begin::Body-->
                <a href="/manajer/status/5">
                    <div class="card-body d-flex flex-column p-0">
                        <!--begin::Stats-->
                        <div class="flex-grow-1 card-spacer-x pt-6">
                            <div class="text-inverse-danger font-weight-bold">Status Kunjungan</div>
                            <div class="text-inverse-danger font-weight-bolder font-size-h3">
                                {{ \App\Utilities\Helpers::statusCountDash($aktivitas, 5) ?? '' }} Maintenance</div>
                        </div>
                        <!--end::Stats-->
                        <!--begin::Chart-->
                        <div id="kt_tiles_widget_2_chart" class="card-rounded-bottom" style="height: 50px"></div>
                        <!--end::Chart-->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Tiles Widget 2-->
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
        </div>
    </div>

    {{-- Peta Merchant --}}
    <!--begin::Row-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col sm-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Rincian Peta Persebaran Merchant/Partner</h3>
                    </div>
                </div>
                <div class="card-body">
                    <!--The div element for the map -->
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    <!--End::Row-->

    <script>
        // Initialize and add the map
        function initMap() {
            // The location of maps
            var locations = @json($merchant_location);

            //make new map
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 5,
                center: new google.maps.LatLng(-0.2984209, 110.1858078),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var infowindow = new google.maps.InfoWindow();

            var marker, i, custom_marker;

            for (i = 0; i < locations.length; i++) {

                //untuk custom warna marker berdasarkan status
                if (locations[i][3] == 'Prospek') {
                    custom_marker = '{{ url('/assets/image_maps/nmarker_red.png') }}'
                } else if (locations[i][3] == 'Demo/Presentasi') {
                    custom_marker = '{{ url('/assets/image_maps/nmarker_yellow.png') }}'
                } else if (locations[i][3] == 'Closing Paid') {
                    custom_marker = '{{ url('/assets/image_maps/nmarker_green.png') }}'
                } else if (locations[i][3] == 'Pending') {
                    custom_marker = '{{ url('/assets/image_maps/nmarker_blue.png') }}'
                } else if (locations[i][3] == 'Maintenance') {
                    custom_marker = '{{ url('/assets/image_maps/nmarker_purple.png') }}'
                } else {
                    custom_marker = '{{ url('/assets/image_maps/nmarker_black.png') }}'
                }

                //make marker (latitude dan longitude)
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    icon: custom_marker
                });

                //add pop up/info window dr marker
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infowindow.setContent(locations[i][0]); //option 1 nampilin nama merchant
                        infowindow.open(map, marker); //option 2 nampilin lat long ? 
                    }
                })(marker, i));
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC55h3VepLXMTDMz8a-BE9zTCNViINxwxw&callback=initMap">
        //Key anggun : AIzaSyD4bH81ZC2JQTFLORBU71U56ipKjS2B7HM
        //Key project : AIzaSyC55h3VepLXMTDMz8a-BE9zTCNViINxwxw
    </script>
@endsection
