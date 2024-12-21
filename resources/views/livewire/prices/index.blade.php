<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Система
        @endslot
        @slot('title')
            Ценообразование смен
        @endslot
    @endcomponent
    <style>
        .driver-avatar-sm {
            border-radius: 10px;
            box-shadow: 0 0 5px #dedede;
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
    </style>

    <div class="d-flex flex-row-reverse">
        <div>

            <button type="button" class="btn btn-success waves-effect waves-light mb-4" wire:click="savePrices()"><i
                    class="bx bx-save me-2"></i> Сохранить</button>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show me-3" role="alert">
                <i class="uil uil-check me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif

    </div>

    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Класс автомобиля / Смена</th>
                    @foreach ($shifts as $shift)
                        <th>{{ $shift->hours }} {{ $shift->state }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($car_classes as $car_class)
                    <tr>
                        <td>

                            @if ($car_class->image)
                                <img src="{{ asset(str_replace('public/', 'storage/', $car_class->image)) }}"
                                    alt="" class="driver-avatar-sm mb-3">
                            @endif
                            <p>
                                {{ $car_class->name }}

                            </p>
                        </td>
                        @foreach ($shifts as $shift)
                            <td>
                                @foreach ($levels as $level)
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="bx bxs-star"
                                                style="color: #{{ $level->color }}"></i></div>
                                        <input type="number"
                                            class="form-control"wire:model="prices.{{ $car_class->id }}.{{ $shift->id }}.{{ $level->id }}"
                                            placeholder="{{ $car_class->name }}/{{ $shift->hours }} {{ $shift->state }}/{{ $level->name }}"
                                            value="{{ $prices[$car_class->id][$shift->id][$level->id] ?? '' }}">
                                        <div class="input-group-text">₸</div>
                                    </div>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
