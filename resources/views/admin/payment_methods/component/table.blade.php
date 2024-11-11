<table class="table table-striped table-bordered table-hover dataTables-payment-methods">
    <thead>
        <tr>
            <th>ID</th>
            <th>Phương thức thanh toán</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($paymentMethods as $paymentMethod)
            <tr>
                <td>{{ $paymentMethod->id }}</td>
                <td>
                    <div style="display: flex; align-items: center;">
                        @if (!empty($paymentMethod->icon) && file_exists(public_path('icons/' . $paymentMethod->icon)))
                            <img src="{{ asset('icons/' . $paymentMethod->icon) }}" alt="Icon" width="30" style="margin-right: 10px;">
                        @else
                            <img src="{{ asset('logo/default-icon.jpg') }}" alt="Default Icon" width="30" style="margin-right: 10px;">
                        @endif
                        {{ $paymentMethod->name }}
                    </div>
                </td>
                
                <!-- Status Toggle -->
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $paymentMethod->id }}" data-type="payment_method" {{ $paymentMethod->is_active ? 'checked' : '' }}>
                </td>
                
                          

                <!-- Action Buttons -->
                <td>
                    <a href="{{ route('payment_methods.edit', $paymentMethod->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('payment_methods.destroy', $paymentMethod->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>                   
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- <script src="{{ asset('backend/js/status/status.js') }}"></script> --}}
{{-- <link rel="stylesheet" href="{{ asset('backend/css/plugins/switchery/switchery.css') }}">
<script src="{{ asset('backend/js/plugins/switchery/switchery.js') }}"></script> --}}

<!-- Toastr CSS and JS -->
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}
