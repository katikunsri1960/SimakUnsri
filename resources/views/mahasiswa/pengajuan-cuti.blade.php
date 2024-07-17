@extends('layouts.mahasiswa')
@section('title')
Dashboard
@endsection
@section('content')
<section class="content">
    <div class="row align-items-end">
        <div class="col-xl-12 col-12">
            <div class="box bg-primary-light pull-up">
                <div class="box-body p-xl-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3"><img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" alt="">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h2>Halaman Pengajuan Cuti</h2>
                            <p class="text-dark mb-0 fs-16">
                                Your course Overcoming the fear of public speaking was completed by 11
                                New users this week!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box no-shadow mb-0 bg-transparent">
                <div class="box-header no-border px-0">
                    <h4 class="box-title">Your Courses</h4>
                    <ul class="box-controls pull-right d-md-flex d-none">
                        <li>
                            <button class="btn btn-primary-light px-10">View All</button>
                        </li>
                        <li class="dropdown">
                            <button class="dropdown-toggle btn btn-primary-light px-10" data-bs-toggle="dropdown"
                                href="#" aria-expanded="false">Most
                                Popular</button>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <a class="dropdown-item active" href="#">Today</a>
                                <a class="dropdown-item" href="#">Yesterday</a>
                                <a class="dropdown-item" href="#">Last week</a>
                                <a class="dropdown-item" href="#">Last month</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box bg-secondary-light pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-1.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center pe-2 justify-content-between">
                            <div class="d-flex">
                                <span class="badge badge-primary me-15">Active</span>
                                <span class="badge badge-primary me-5"><i class="fa fa-lock"></i></span>
                                <span class="badge badge-primary"><i class="fa fa-clock-o"></i></span>
                            </div>
                            <div class="dropdown">
                                <a data-bs-toggle="dropdown" href="#" class="px-10 pt-5"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                        Import</a>
                                    <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                        Export</a>
                                    <a class="dropdown-item" href="#"><i class="ti-printer"></i>
                                        Print</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="ti-settings"></i>
                                        Settings</a>
                                </div>
                            </div>
                        </div>
                        <h4 class="mt-25 mb-5">It & software</h4>
                        <p class="text-fade mb-0 fs-12">45 Days Left</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box bg-secondary-light pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-2.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center pe-2 justify-content-between">
                            <div class="d-flex">
                                <span class="badge badge-dark me-15">Finished</span>
                            </div>
                            <div class="dropdown">
                                <a data-bs-toggle="dropdown" href="#" class="px-10 pt-5"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                        Import</a>
                                    <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                        Export</a>
                                    <a class="dropdown-item" href="#"><i class="ti-printer"></i>
                                        Print</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="ti-settings"></i>
                                        Settings</a>
                                </div>
                            </div>
                        </div>
                        <h4 class="mt-25 mb-5">Programming</h4>
                        <p class="text-fade mb-0 fs-12">1 Days Left</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box bg-secondary-light pull-up"
                style="background-image: url({{asset('images/images/svg-icon/color-svg/st-3.svg')}}); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center pe-2 justify-content-between">
                            <div class="d-flex">
                                <span class="badge badge-primary me-15">Active</span>
                                <span class="badge badge-primary me-5"><i class="fa fa-lock"></i></span>
                            </div>
                            <div class="dropdown">
                                <a data-bs-toggle="dropdown" href="#" class="px-10 pt-5"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                        Import</a>
                                    <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                        Export</a>
                                    <a class="dropdown-item" href="#"><i class="ti-printer"></i>
                                        Print</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="ti-settings"></i>
                                        Settings</a>
                                </div>
                            </div>
                        </div>
                        <h4 class="mt-25 mb-5">Networking</h4>
                        <p class="text-fade mb-0 fs-12">15 days Left</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="box bg-secondary-light pull-up"
                style="background-image: url(../images/svg-icon/color-svg/st-4.svg); background-position: right bottom; background-repeat: no-repeat;">
                <div class="box-body">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center pe-2 justify-content-between">
                            <div class="d-flex">
                                <span class="badge badge-warning-light me-15">Paused</span>
                                <span class="badge badge-warning-light me-5"><i class="fa fa-lock"></i></span>
                            </div>
                            <div class="dropdown">
                                <a data-bs-toggle="dropdown" href="#" class="px-10 pt-5"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                        Import</a>
                                    <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                        Export</a>
                                    <a class="dropdown-item" href="#"><i class="ti-printer"></i>
                                        Print</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="ti-settings"></i>
                                        Settings</a>
                                </div>
                            </div>
                        </div>
                        <h4 class="mt-25 mb-5">Network Security</h4>
                        <p class="text-fade mb-0 fs-12">21 Days Left</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-12">
            <div class="box">
                <div class="box-body">
                    <p class="text-fade">Total Courses</p>
                    <h3 class="mt-0 mb-20">19 <small class="text-success"><i class="fa fa-arrow-up ms-15 me-5"></i> 2
                            New</small></h3>
                    <div id="charts_widget_2_chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-12">
            <div class="box">
                <div class="box-body">
                    <p class="text-fade">Hours spent</p>
                    <h3 class="mt-0 mb-20">21 h 30 min <small class="text-danger"><i
                                class="fa fa-arrow-down ms-25 me-5"></i> 15%</small></h3>
                    <div id="charts_widget_1_chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Working Hours</h4>
                    <ul class="box-controls pull-right d-md-flex d-none">
                        <li class="dropdown">
                            <button class="dropdown-toggle btn btn-warning-light px-10" data-bs-toggle="dropdown"
                                href="#">Today</button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item active" href="#">Today</a>
                                <a class="dropdown-item" href="#">Yesterday</a>
                                <a class="dropdown-item" href="#">Last week</a>
                                <a class="dropdown-item" href="#">Last month</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="box-body">
                    <div id="revenue5"></div>
                    <div class="d-flex justify-content-center">
                        <p class="d-flex align-items-center fw-600 mx-20"><span
                                class="badge badge-xl badge-dot badge-warning me-20"></span> Progress
                        </p>
                        <p class="d-flex align-items-center fw-600 mx-20"><span
                                class="badge badge-xl badge-dot badge-primary me-20"></span> Done</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box no-shadow mb-0 bg-transparent">
                <div class="box-header no-border px-0">
                    <h4 class="box-title">Performance & Statistics</h4>
                    <ul class="box-controls pull-right d-md-flex d-none">
                        <li>
                            <button class="btn btn-primary-light px-10">View All</button>
                        </li>
                        <li class="dropdown">
                            <button class="dropdown-toggle btn btn-primary-light px-10" data-bs-toggle="dropdown"
                                href="#" aria-expanded="false">All
                                Type</button>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <a class="dropdown-item active" href="#">Today</a>
                                <a class="dropdown-item" href="#">Yesterday</a>
                                <a class="dropdown-item" href="#">Last week</a>
                                <a class="dropdown-item" href="#">Last month</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-xl-8 col-12">
                    <div class="row">
                        <div class="col-xl-5 col-lg-6 col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h4 class="box-title">Course completion</h4>
                                    <ul class="box-controls pull-right d-md-flex d-none">
                                        <li>
                                            <button class="btn btn-primary-light px-10">View
                                                All</button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="box-body">
                                    <div class="mb-30">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="w-p85">
                                                <div class="progress progress-sm mb-0">
                                                    <div class="progress-bar progress-bar-primary" role="progressbar"
                                                        aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 40%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div>40%</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 text-primary">In Progress</p>
                                            <p class="text-fade mb-0">117 User</p>
                                        </div>
                                    </div>
                                    <div class="mb-30">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="w-p85">
                                                <div class="progress progress-sm mb-0">
                                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 20%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div>20%</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 text-primary">Completed</p>
                                            <p class="text-fade mb-0">74 User</p>
                                        </div>
                                    </div>
                                    <div class="mb-30">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="w-p85">
                                                <div class="progress progress-sm mb-0">
                                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                                        aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 18%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div>18%</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 text-primary">Inactive</p>
                                            <p class="text-fade mb-0">58 User</p>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="w-p85">
                                                <div class="progress progress-sm mb-0">
                                                    <div class="progress-bar progress-bar-danger" role="progressbar"
                                                        aria-valuenow="7" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 7%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div>07%</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 text-primary">Expeired</p>
                                            <p class="text-fade mb-0">11 User</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-6 col-12">
                            <div class="box bg-transparent no-shadow mb-20">
                                <div class="box-header no-border pb-0">
                                    <h4 class="box-title">Lessons</h4>
                                    <ul class="box-controls pull-right d-md-flex d-none">
                                        <li>
                                            <button class="btn btn-primary-light px-10">View
                                                All</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="box mb-15 pull-up">
                                <div class="box-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15 bg-warning h-50 w-50 l-h-60 rounded text-center">
                                                <span class="icon-Book-open fs-24"><span class="path1"></span><span
                                                        class="path2"></span></span>
                                            </div>
                                            <div class="d-flex flex-column fw-500">
                                                <a href="#" class="text-dark hover-primary mb-1 fs-16">Informatic
                                                    Course</a>
                                                <span class="text-fade">Johen Doe, 19 April</span>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <span class="icon-Arrow-right fs-24"><span class="path1"></span><span
                                                    class="path2"></span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-15 pull-up">
                                <div class="box-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15 bg-primary h-50 w-50 l-h-60 rounded text-center">
                                                <span class="icon-Mail fs-24"></span>
                                            </div>
                                            <div class="d-flex flex-column fw-500">
                                                <a href="#" class="text-dark hover-primary mb-1 fs-16">Live
                                                    Drawing</a>
                                                <span class="text-fade">Micak Doe, 12 June</span>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <span class="icon-Arrow-right fs-24"><span class="path1"></span><span
                                                    class="path2"></span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0 pull-up">
                                <div class="box-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15 bg-danger h-50 w-50 l-h-60 rounded text-center">
                                                <span class="icon-Book-open fs-24"><span class="path1"></span><span
                                                        class="path2"></span></span>
                                            </div>
                                            <div class="d-flex flex-column fw-500">
                                                <a href="#" class="text-dark hover-primary mb-1 fs-16">Contemporary
                                                    Art</a>
                                                <span class="text-fade">Potar doe, 27 July</span>
                                            </div>
                                        </div>
                                        <a href="#">
                                            <span class="icon-Arrow-right fs-24"><span class="path1"></span><span
                                                    class="path2"></span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="box bg-transparent no-shadow mb-0">
                                <div class="box-header no-border px-0">
                                    <h4 class="box-title">Media for lessons</h4>
                                    <div class="box-controls pull-right d-md-flex d-none">
                                        <a href="#">View All</a>
                                    </div>
                                </div>
                            </div>
                            <div class="box">
                                <div class="box-body py-10">
                                    <div class="table-responsive">
                                        <table class="table no-border mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="bg-danger h-50 w-50 l-h-50 rounded text-center">
                                                            <p class="mb-0 fs-20 fw-600">A1</p>
                                                        </div>
                                                    </td>
                                                    <td class="fw-600">Biology Course</td>
                                                    <td class="text-fade">StarterReplacement.pdf</td>
                                                    <td class="fw-500"><span
                                                            class="badge badge-sm badge-dot badge-warning me-10"></span>Only
                                                        view</td>
                                                    <td class="text-fade">78 members</td>
                                                    <td class="fw-600">47 MB</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="bg-info h-50 w-50 l-h-50 rounded text-center">
                                                            <p class="mb-0 fs-20 fw-600">B1</p>
                                                        </div>
                                                    </td>
                                                    <td class="fw-600">Contemporary Art</td>
                                                    <td class="text-fade">Loremipsum.doc</td>
                                                    <td class="fw-500 text-warning"><span
                                                            class="badge badge-sm badge-dot badge-warning me-10"></span>Edit
                                                        available</td>
                                                    <td class="text-fade">78 members</td>
                                                    <td class="fw-600">78 MB</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="bg-primary h-50 w-50 l-h-50 rounded text-center">
                                                            <p class="mb-0 fs-20 fw-600">C1</p>
                                                        </div>
                                                    </td>
                                                    <td class="fw-600">Programming Language</td>
                                                    <td class="text-fade">phpcore.mp3</td>
                                                    <td class="fw-500"><span
                                                            class="badge badge-sm badge-dot badge-primary me-10"></span>Only
                                                        view</td>
                                                    <td class="text-fade">48 members</td>
                                                    <td class="fw-600">12 MB</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="bg-success h-50 w-50 l-h-50 rounded text-center">
                                                            <p class="mb-0 fs-20 fw-600">A2</p>
                                                        </div>
                                                    </td>
                                                    <td class="fw-600">Geometry Course</td>
                                                    <td class="text-fade">dummyabz.pdf</td>
                                                    <td class="fw-500"><span
                                                            class="badge badge-sm badge-dot badge-primary me-10"></span>Only
                                                        view</td>
                                                    <td class="text-fade">24 members</td>
                                                    <td class="fw-600">18 MB</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div id="calendar" class="dask evt-cal min-h-400"></div>
                        </div>
                    </div>
                    <a href="#" class="box bg-danger bg-hover-danger pull-up">
                        <div class="box-body">
                            <div class="d-flex align-items-center">
                                <div class="w-80 h-80 l-h-100 rounded-circle b-1 border-white text-center">
                                    <span class="text-white icon-Cart2 fs-40"><span class="path1"></span><span
                                            class="path2"></span></span>
                                </div>
                                <div class="ms-10">
                                    <h4 class="text-white mb-0">+1 1234 456 789</h4>
                                    <h5 class="text-white-50 mb-0">Toll Free</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="box bg-primary bg-hover-primary pull-up">
                        <div class="box-body">
                            <div class="d-flex align-items-center">
                                <div class="w-80 h-80 l-h-100 rounded-circle b-1 border-white text-center">
                                    <span class="text-white icon-Mail fs-40"></span>
                                </div>
                                <div class="ms-10">
                                    <h4 class="text-white mb-0">info@EduAdmin.com</h4>
                                    <h5 class="text-white-50 mb-0">Free to Fill Us</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
