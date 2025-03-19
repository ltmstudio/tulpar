<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Система
        @endslot
        @slot('title')
            Данные для оплаты
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">

                    <form wire:submit.prevent="save">
                        <div class="form-group">
                            <label for="pay_link">Payment Link</label>
                            <input type="url" id="pay_link" wire:model="pay_link" class="form-control"
                                placeholder="Enter payment link">
                            @error('pay_link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pay_qr_image">QR Code Image</label>
                            <input type="file" id="pay_qr_image" wire:model="image" class="form-control">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label>Selected Image Preview</label>
                            <div>
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" alt="Selected Image" class="img-fluid rounded mb-3" style="max-width: 200px;">
                                @elseif ($pay_qr_image)
                                    <img src="{{ asset('storage/' . $pay_qr_image) }}" alt="Saved Image" class="img-fluid rounded mb-3" style="max-width: 200px;">
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pay_qr_phone">QR Code Phone</label>
                            <input type="text" id="pay_qr_phone" wire:model="pay_qr_phone" class="form-control"
                                placeholder="Enter phone number">
                            @error('pay_qr_phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @if (session()->has('message'))
        <div class="alert alert-success mt-3">
            {{ session('message') }}
        </div>
    @endif
</div>
