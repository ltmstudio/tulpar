<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            *
        @endslot
        @slot('title')
            Главная
        @endslot
    @endcomponent

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="uil uil-exclamation-octagon me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    <style>
        .driver-avatar-sm {
            border-radius: 10px;
            box-shadow: 0 0 5px #dedede;
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-right: 20px;
        }
    </style>

    <ul class="nav nav-pills" role="tablist">
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link {{ $selected_period == 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#navpills-0"
                role="tab">
                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                <span class="d-none d-sm-block">За все время</span>
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link {{ $selected_period == 30 ? 'active' : '' }}" data-bs-toggle="tab" href="#navpills-30"
                role="tab">
                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                <span class="d-none d-sm-block">Месяц</span>
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link {{ $selected_period == 7 ? 'active' : '' }}" data-bs-toggle="tab" href="#navpills-7"
                role="tab">
                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                <span class="d-none d-sm-block">Неделя</span>
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link {{ $selected_period == 1 ? 'active' : '' }} " data-bs-toggle="tab" href="#navpills-1"
                role="tab">
                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                <span class="d-none d-sm-block">День</span>
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="row">
        <div class="col-lg-4">
            <div class="tab-content p-3 text-muted">
                @foreach ($all_periods as $period)
                    <div class="tab-pane {{ $selected_period == $period ? 'active show' : '' }}"
                        id="navpills-{{ $period }}" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h6>Количество поездок по классам</h6>
                                {{-- <p>Выберите модуль и язык, которые будут включены в файл скачивания</p> --}}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if (isset($result[$period]))
                                        @foreach ($result[$period] as $res)
                                            <div class="col-lg-6">
                                                <div class="card card-body">
                                                    @php
                                                        $item = $res['car_class'];
                                                    @endphp
                                                    <div class="d-flex align-items-center">
                                                        <div class="d-flex flex-row">
                                                            @if ($item->image)
                                                                <img src="{{ asset(str_replace('public/', 'storage/', $item->image)) }}"
                                                                    alt="" class="driver-avatar-sm mb-3">
                                                            @endif
                                                            <div class="d-flex flex-column">
                                                                <h5 class="font-size-15">{{ $item->name }}</h5>
                                                                <h4 class="mb-1 mt-1">{{ $res['count'] }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6>Пользователи системы</h6>
                </div>
                <div class="card-body">
                    <p>Число водителей: {{ $drivers_total_count }}</p>
                    <p>Число пользователей: {{ $users_total_count }}</p>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Новые заявки на модерацию</h6>
                    {{-- <p>Выберите модуль и язык, которые будут включены в файл скачивания</p> --}}
                </div>
                <div class="card-body">
                    <div class="row" wire:loading.class="opacity-75">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 35px;">#</th>
                                        <th>ФИО / дата рождения</th>
                                        <th>Авто</th>
                                        <th>VIN авто</th>
                                        <th>Гос номер авто</th>
                                        <th>Фото авто</th>
                                        <th>ВУ</th>
                                        <th>ПТС</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($moderations as $moderation)
                                        <tr>
                                            <th scope="row">{{ $moderation->id }}</th>
                                            <td>{{ $moderation->name }} {{ $moderation->lastname }}
                                                @if ($moderation->birthdate)
                                                    /
                                                    {{ \Carbon\Carbon::parse($moderation->birthdate)->format('d.m.Y') }}
                                                @else
                                                    Дата рождения не указана
                                                @endif
                                            </td>
                                            <td>
                                                @if ($moderation->car_id)
                                                    {{ optional($moderation->car)->name ?? '--' }}
                                                @else
                                                    --
                                                @endif
                                                @if ($moderation->car_model_id)
                                                    {{ $moderation->carModel->name ?? '--' }}
                                                @else
                                                    --
                                                @endif
                                                @if ($moderation->car_year)
                                                    {{ $moderation->car_year }} г
                                                @endif
                                            </td>
                                            <td>
                                                @if ($moderation->car_vin)
                                                    {{ $moderation->car_vin }}
                                                @else
                                                    <span><i class="text-danger uil-times-circle"></i> VIN
                                                        не указан
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($moderation->car_gos_number)
                                                    {{ $moderation->car_gos_number }}
                                                @else
                                                    <span><i class="text-danger uil-times-circle"></i> Гос
                                                        номер не
                                                        указан </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (empty($moderation->car_images))
                                                    <span><i class="text-danger uil-times-circle"></i> Нет
                                                        фото</span>
                                                @else
                                                    <span><i class="text-success uil-check-circle"></i>
                                                        {{ count($moderation->car_images) }} фото</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- №{{ $moderation->driver_license_number ?? '---' }} <br>
                                    @if ($moderation->driver_license_date)
                                        {{ \Carbon\Carbon::parse($moderation->driver_license_date)->format('d M Y') }} <br>
                                    @else
                                        Дата ВУ не указана <br>
                                    @endif --}}
                                                @if (empty($moderation->driver_license_images))
                                                    <span><i class="text-danger uil-times-circle"></i> Нет
                                                        фото</span>
                                                @else
                                                    <span><i class="text-success uil-check-circle"></i>
                                                        {{ count($moderation->driver_license_images) }}
                                                        фото</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if (empty($moderation->ts_passport_images))
                                                    <span><i class="text-danger uil-times-circle"></i> Нет
                                                        фото</span>
                                                @else
                                                    <span><i class="text-success uil-check-circle"></i>
                                                        {{ count($moderation->ts_passport_images) }}
                                                        фото</span>
                                                @endif

                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="21" align="center">Модерации отсутствуют</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
