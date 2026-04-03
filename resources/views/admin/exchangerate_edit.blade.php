@include('admin.head')

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        @include('admin.header')
        @include('admin.ui-setting')

        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')

            <div class="app-main__outer">
                <div class="app-main__inner">

                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            <div class="card-header-title font-size-lg text-capitalize fw-normal">
                                Edit Exchange Rate
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="toast-container"></div>

                            <form action="{{ route('admin.exchangerate.update', $rate->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                <label for="from_currency_id" class="form-label">From Currency</label>
                                <select id="from_currency_id" class="form-control" disabled>
                                    @foreach($currencies as $c)
                                    <option value="{{ $c->id }}" {{ $rate->from_currency_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->code }})
                                    </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="from_currency_id" value="{{ $rate->from_currency_id }}">
                                </div>

                                <div class="mb-3">
                                <label for="to_currency_id" class="form-label">To Currency</label>
                                <select id="to_currency_id" class="form-control" disabled>
                                    @foreach($currencies as $c)
                                    <option value="{{ $c->id }}" {{ $rate->to_currency_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->code }})
                                    </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="to_currency_id" value="{{ $rate->to_currency_id }}">
                                </div>


                                <div class="mb-3">
                                    <label for="rate" class="form-label">Exchange Rate</label>
                                    <input type="number" step="0.000001" name="rate" id="rate" class="form-control"
                                           value="{{ old('rate', $rate->rate) }}" required>
                                    @error('rate') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="transfer_fee" class="form-label">Transfer Fee</label>
                                    <input type="number" step="0.01" name="transfer_fee" id="transfer_fee" class="form-control"
                                           value="{{ old('transfer_fee', $rate->transfer_fee) }}">
                                    @error('transfer_fee') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Update Rate</button>
                                <a href="{{ route('admin.exchangerate') }}" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@include('admin.footer')
