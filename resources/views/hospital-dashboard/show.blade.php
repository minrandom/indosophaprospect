@extends('layout.backend.app', [
    'title' => 'Hospital Dashboard',
    'pageTitle' => 'Hospital Dashboard',
])

@push('css')
<link rel="stylesheet"
      href="{{ asset('css/hospital-dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid hospital-dashboard">

    {{-- Hospital Header --}}
    <div class="card border-0 shadow-sm hospital-header mb-4">
        <div class="card-body">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start">

                <div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hospital text-primary mr-2"></i>

                        <h4 class="font-weight-bold text-gray-800 mb-0"
                            id="hospitalName">
                            Loading hospital...
                        </h4>
                    </div>

                    <div class="d-flex flex-wrap mt-3 hospital-meta">

                        <span class="badge badge-light mr-2 mb-2 p-2">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            <span id="hospitalLocation">-</span>
                        </span>

                        <span class="badge badge-light mr-2 mb-2 p-2">
                            <i class="fas fa-building mr-1"></i>
                            <span id="hospitalType">-</span>
                        </span>

                        <span class="badge badge-light mr-2 mb-2 p-2">
                            <i class="fas fa-star mr-1"></i>
                            <span id="hospitalTarget">-</span>
                        </span>

                    </div>
                </div>

                {{-- <div class="small text-muted mt-3 mt-lg-0 text-lg-right">
                    <div>Last dashboard update</div>

                    <strong class="text-gray-800"
                            id="hospitalLastUpdate">
                        -
                    </strong>
                </div> --}}
                  <button type="button"
                class="btn btn-outline-primary btn-sm"
                data-toggle="modal"
                data-target="#changeHospitalModal">
            <i class="fas fa-exchange-alt mr-1"></i>
            Change Hospital
        </button>

            </div>

        </div>
    </div>

    {{-- Department Filter --}}
    <div class="card border-0 shadow-sm dashboard-filter-card mb-4">
        <div class="card-body">


            <form id="departmentFilterForm">
                <div class="row align-items-end">

                    <div class="col-md-7 col-lg-5 mb-3 mb-md-0">
                        <label for="departmentFilter"
                               class="small font-weight-bold text-gray-700">
                            Department
                        </label>

                        <select id="departmentFilter"
                                class="form-control">
                            <option value="">
                                Loading departments...
                            </option>
                        </select>
                    </div>

                    <div class="col-md-5 col-lg-3">
                        <button type="submit"
                                class="btn btn-primary btn-block">
                            <i class="fas fa-filter mr-1"></i>
                            Change Department
                        </button>
                    </div>

                    <div class="col-lg-4 d-none d-lg-block text-right">
                        <span class="small text-muted">
                            All sections follow the selected department.
                        </span>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <div id="dashboardContent">

        {{-- KPI Cards --}}
        <div class="row">

            <div class="col-6 col-xl-3 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-kpi-card kpi-prospect"
                     data-module="prospect">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="small font-weight-bold text-uppercase text-muted mb-1">
                                    Prospects
                                </div>

                                <div class="h3 font-weight-bold text-gray-800 mb-1"
                                     id="prospectCount">
                                    0
                                </div>

                                <div class="small text-muted"
                                     id="prospectSubtitle">
                                    No active prospect
                                </div>
                            </div>

                            <div class="dashboard-kpi-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>

                        </div>

                        <div class="small mt-3 dashboard-kpi-link">
                            View prospect pipeline
                            <i class="fas fa-arrow-right ml-1"></i>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-kpi-card kpi-installbase"
                     data-module="installbase">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="small font-weight-bold text-uppercase text-muted mb-1">
                                    Installbase
                                </div>

                                <div class="h3 font-weight-bold text-gray-800 mb-1"
                                     id="installbaseCount">
                                    0
                                </div>

                                <div class="small text-muted"
                                     id="installbaseSubtitle">
                                    0 business units
                                </div>
                            </div>

                            <div class="dashboard-kpi-icon">
                                <i class="fas fa-microscope"></i>
                            </div>

                        </div>

                        <div class="small mt-3 dashboard-kpi-link">
                            View installed units
                            <i class="fas fa-arrow-right ml-1"></i>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-kpi-card kpi-sdm"
                     data-module="sdm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="small font-weight-bold text-uppercase text-muted mb-1">
                                    SDM
                                </div>

                                <div class="h3 font-weight-bold text-gray-800 mb-1"
                                     id="sdmCount">
                                    0
                                </div>

                                <div class="small text-muted"
                                     id="sdmSubtitle">
                                    0 decision makers
                                </div>
                            </div>

                            <div class="dashboard-kpi-icon">
                                <i class="fas fa-user-md"></i>
                            </div>

                        </div>

                        <div class="small mt-3 dashboard-kpi-link">
                            View people profile
                            <i class="fas fa-arrow-right ml-1"></i>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-kpi-card kpi-validation"
                     data-module="validation">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="small font-weight-bold text-uppercase text-muted mb-1">
                                    Dept Validation
                                </div>

                                <div class="h3 font-weight-bold text-gray-800 mb-1"
                                     id="validationCount">
                                    0 / 0
                                </div>

                                <div class="small text-muted"
                                     id="validationSubtitle">
                                    0% completed
                                </div>
                            </div>

                            <div class="dashboard-kpi-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>

                        </div>

                        <div class="small mt-3 dashboard-kpi-link">
                            View validation status
                            <i class="fas fa-arrow-right ml-1"></i>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- Prospect and Installbase --}}
        <div class="row">

            <div class="col-xl-7 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-section-card">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="font-weight-bold text-gray-800 mb-1">
                                Prospect Pipeline
                            </h6>

                            <div class="small text-muted">
                                Current opportunity stages
                            </div>
                        </div>

                        <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-module="prospect">
                            View All
                        </button>

                    </div>

                    <div class="card-body">

                        <ul class="list-unstyled mb-0 summary-list"
                            id="prospectPipelineList">
                        </ul>

                        <hr>

                        <div class="row">

                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <div class="small text-muted">
                                    Estimated Prospect Value
                                </div>

                                <div class="h5 font-weight-bold text-gray-800 mb-0"
                                     id="prospectValue">
                                    Rp0
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="small text-muted">
                                    ETA PO Terdekat
                                </div>

                                <div class="h5 font-weight-bold text-gray-800 mb-0"
                                     id="nearestEta">
                                    -
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-5 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-section-card">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="font-weight-bold text-gray-800 mb-1">
                                Installbase by Business Unit
                            </h6>

                            <div class="small text-muted">
                                Installed equipment distribution
                            </div>
                        </div>

                        <button type="button"
                                class="btn btn-sm btn-outline-success"
                                data-module="installbase">
                            View All
                        </button>

                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled mb-0 summary-list"
                            id="installbaseList">
                        </ul>
                    </div>

                </div>
            </div>

        </div>

        {{-- Validation, Mapping, Survey --}}
        <div class="row">

            <div class="col-xl-4 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-section-card">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="font-weight-bold text-gray-800 mb-1">
                                Department Validation
                            </h6>

                            <div class="small text-muted">
                                Department data completeness
                            </div>
                        </div>

                        <button type="button"
                                class="btn btn-sm btn-outline-warning"
                                data-module="validation">
                            Details
                        </button>

                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled mb-0 summary-list"
                            id="validationList">
                        </ul>
                    </div>

                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-section-card">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="font-weight-bold text-gray-800 mb-1">
                                Latest Department Mapping
                            </h6>

                            <div class="small text-muted">
                                Latest two mapping activities
                            </div>
                        </div>

                        <button type="button"
                                class="btn btn-sm btn-outline-info"
                                data-module="mapping">
                            View More
                        </button>

                    </div>

                    <div class="card-body"
                         id="mappingList">
                    </div>

                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="card border-0 shadow-sm h-100 dashboard-section-card">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="font-weight-bold text-gray-800 mb-1">
                                Market Survey
                            </h6>

                            <div class="small text-muted">
                                Latest market information
                            </div>
                        </div>

                        <button type="button"
                                class="btn btn-sm btn-outline-secondary"
                                data-module="survey">
                            View Survey
                        </button>

                    </div>

                    <div class="card-body"
                         id="marketSurveyContent">
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@include('hospital-dashboard.changehospital-modal')

@endsection

@push('js')
<script src="{{ asset('js/hospital-dash-dummy.js') }}"></script>
@endpush
