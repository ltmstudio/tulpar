<form wire:submit.prevent="chargeBalance" class="custom-validation">
    <div class="modal fade" wire:ignore.self id="balanceModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-labelledby="balanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="balanceModalLabel">
                        @if ($item_edit_id != '')
                            Баланс водителя
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeBalanceModal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="card-title mb-3">Общая информация о водителе</h4>
                            <div class="d-flex flex-row">
                                @if ($avatar)
                                    <div class="p-2">
                                        <img src="{{ asset(str_replace('public/', 'storage/', $avatar)) }}"
                                            alt="" class="driver-avatar mb-3">
                                    </div>
                                @endif
                                <div class="p-2">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-truncate font-size-14 mb-1">{{ $name }}
                                            {{ $lastname }}</h5>
                                        <p class="text-truncate mb-0">+7 {{ $phone }}</p>
                                        <p class="text-truncate mb-0">{{ $car_number }} | {{ $car_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="card-title mb-3">Текущий баланс</h4>
                            <p class="mt-3 font-size-20" style="font-weight: 800">{{ $balance }} ₸</p>
                        </div>
                    </div>
                    <h4 class="card-title mt-3 mb-3">Новая операция</h4>
                    <div class="row row-cols-lg-auto gx-3 gy-2 align-items-center">

                        <div class="col-12">
                            <label class="visually-hidden" for="balance_input">Сумма</label>
                            @error('balance_input')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="number" class="form-control" id="balance_input" wire:model="balance_input"
                                placeholder="Введите сумму пополнения">
                        </div>

                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-1">
                                <button type="submit" class="btn btn-primary waves-effect waves-light w-md">Выполнить
                                    операцию</button>
                            </div>
                        </div>
                        <div class="col-12">
                            @if (session()->has('message-modal'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="uil uil-check me-2"></i>
                                    {{ session('message-modal') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <h4 class="card-title mt-5 mb-3">История начислений и списаний</h4>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">

                            <thead>
                                <tr>
                                    <th style="width: 35px;">#</th>
                                    <th style="text-align: end">Сумма</th>
                                    <th>Тип</th>
                                    <th style="text-align: end">Результат</th>
                                    <th>Заказ-смена</th>
                                    <th>Дата операции</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($balance_operations as $oper)
                                    <tr>
                                        <th scope="row">{{ $oper->id }}</th>
                                        <td align="end">{{ $oper->operation_value }} ₸</td>
                                        <td>
                                            @if ($oper->operation_value >= 0)
                                                <span class="badge bg-success-subtle text-success">Пополнение</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Списание</span>
                                            @endif
                                        </td>
                                        <td align="end">{{ $oper->result_balance }} ₸</td>
                                        <td>
                                            @if ($oper->shift_order_id)
                                                {{ $oper->shift_order->class->name . '/' . $oper->shift_order->hours . ' ' . $oper->shift_order->hours_state . '/' . $oper->shift_order->level_name  }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $oper->created_at->translatedFormat('H:i d M. Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" align="center">Операции отсутствуют</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="closeBalanceModal()">Закрыть</button>
                    {{-- <button type="submit" class="btn btn-primary">Сохранить</button> --}}
                </div>
            </div>
        </div>
    </div>
</form>
