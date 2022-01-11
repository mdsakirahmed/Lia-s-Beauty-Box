<div class="row">
    <style>
        .hoverable {
            border-radius: 4px;
            background: #fff;
            box-shadow: 0 6px 10px rgba(0, 0, 0, .08), 0 0 6px rgba(0, 0, 0, .05);
            transition: .3s transform cubic-bezier(.155, 1.105, .295, 1.12), .3s box-shadow, .3s -webkit-transform cubic-bezier(.155, 1.105, .295, 1.12);
            /* padding: 14px 80px 18px 36px; */
            cursor: pointer;
        }

        .hoverable:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
        }

        .product-card {
            height: 5.5in;
            overflow: auto;
            background: #fff;
        }
    </style>
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-header bg-info">
                <h4 class="m-b-0 text-white">Create appointment</h4>
            </div>
            <div class="card-body">
                <input type="date" wire:model='date'>
                @if($schedules)
                <div class="alert alert-success text-center" role="alert">
                    <h3 class="alert-heading">{{ $schedules['day_name'] }}</h3>
                    <hr>
                    <p class="mb-0">{{ $schedules['date'] }}</p>
                </div>
                <div class="list-group">
                    @foreach($schedules['data_set'] as $key => $schedule)
                    <a href="javascript:void(0)" wire:click="select_schedule('{{ $schedule->id }}')"
                        class="list-group-item list-group-item-action flex-column align-items-start @if($selected_schedule == $schedule) active @endif">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $loop->iteration }} . {{ $schedule->title }}</h5>
                        </div>
                        <small>{{ $schedule->starting_time }}-{{ $schedule->ending_time }}</small>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="alert alert-danger mt-3 text-center">
                    <h3>Please Choose a date.</h3>
                </div>
                @endif
            </div>
        </div>
    </div>
    @if($selected_schedule)
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-header bg-danger">
                <h4 class="m-b-0 text-white">Services</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($services as $service)
                    <div class="col-6">
                        <div class="card m-1 hoverable" wire:click="addToCard({{ $service->id }})">
                            <img class="card-img-top rounded mx-auto d-block" height="90px;"
                                style="margin-bottom: -10px;"
                                src="{{ asset($service->image ?? 'uploads/images/no_image.png') }}">
                            <div class="card-body text-center">
                                <p class="card-text" style="margin-bottom: -5px;">{{ $service->id }}) {{
                                    $service->name}}</p>
                                <span class="badge bg-primary">{{ $service->price }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-header bg-danger">
                <h4 class="m-b-0 text-white">{{ $selected_schedule->title }}</h4>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="store">
                    <div class="form-group">
                        <input wire:model="name" type="text" class="form-control" id="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input wire:model="email" type="email" class="form-control" id="email"
                            placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <input wire:model="phone" type="text" class="form-control" id="phone" placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <input wire:model="address" type="text" class="form-control" id="address" placeholder="Address">
                    </div>
                    <div class="form-group">
                        <input wire:model="transaction_id" type="text" class="form-control" id="transaction_id"
                            placeholder="Transaction Id">
                    </div>
                    <div class="form-group">
                        <input wire:model="advance_amount" type="text" class="form-control" id="advance_amount"
                            placeholder="Advance Amount">
                    </div>
                    <div class="form-group">
                        <select class="form-control">
                            <option value="" wire:model="services">Please Choose A Service</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>