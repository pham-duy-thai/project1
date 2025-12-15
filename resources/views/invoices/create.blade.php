@extends('layouts.app')

@section('title', 'Tạo hóa đơn mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Tạo hóa đơn mới</h4>
                    </div>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                        @csrf
                        
                        <!-- Contract Selection -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contract_id">Hợp đồng <span class="text-danger">*</span></label>
                                    <select name="contract_id" id="contract_id" class="form-control @error('contract_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn hợp đồng --</option>
                                        @forelse($contracts as $contract)
                                        <option value="{{ $contract->id }}" 
                                                data-monthly-rent="{{ $contract->monthly_rent }}"
                                                data-student-name="{{ $contract->student->full_name ?? 'N/A' }}"
                                                data-room="{{ optional($contract->room->building)->name }} - {{ optional($contract->room)->room_number }}"
                                                {{ old('contract_id', $selectedContractId ?? '') == $contract->id ? 'selected' : '' }}>
                                            {{ $contract->contract_number }} - {{ $contract->student->full_name ?? 'N/A' }} - {{ optional($contract->room->building)->name }} Phòng {{ optional($contract->room)->room_number }} ({{ number_format($contract->monthly_rent) }}đ/tháng)
                                        </option>
                                        @empty
                                        <option value="" disabled>Không có hợp đồng nào</option>
                                        @endforelse
                                    </select>
                                    @error('contract_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Chọn hợp đồng để tạo hóa đơn (Có {{ $contracts->count() }} hợp đồng)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Contract Info Display -->
                        <div id="contractInfo" class="alert alert-info" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Sinh viên:</strong> <span id="studentName"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Phòng:</strong> <span id="roomInfo"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Giá thuê:</strong> <span id="monthlyRent"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Invoice Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_type">Loại hóa đơn <span class="text-danger">*</span></label>
                                    <select name="invoice_type" id="invoice_type" class="form-control @error('invoice_type') is-invalid @enderror" required>
                                        <option value="">-- Chọn loại --</option>
                                        <option value="rent" {{ old('invoice_type') == 'rent' ? 'selected' : '' }}>Tiền thuê phòng</option>
                                        <option value="electricity" {{ old('invoice_type') == 'electricity' ? 'selected' : '' }}>Tiền điện</option>
                                        <option value="water" {{ old('invoice_type') == 'water' ? 'selected' : '' }}>Tiền nước</option>
                                        <option value="service" {{ old('invoice_type') == 'service' ? 'selected' : '' }}>Phí dịch vụ</option>
                                        <option value="other" {{ old('invoice_type') == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('invoice_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date">Hạn thanh toán <span class="text-danger">*</span></label>
                                    <input type="text" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', date('d/m/Y', strtotime('+7 days'))) }}" placeholder="dd/mm/yyyy" required>
                                    @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Billing Period -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing_period_start">Từ ngày <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_period_start" id="billing_period_start" class="form-control @error('billing_period_start') is-invalid @enderror" value="{{ old('billing_period_start', date('d/m/Y', strtotime('first day of this month'))) }}" placeholder="dd/mm/yyyy" required>
                                    @error('billing_period_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing_period_end">Đến ngày <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_period_end" id="billing_period_end" class="form-control @error('billing_period_end') is-invalid @enderror" value="{{ old('billing_period_end', date('d/m/Y', strtotime('last day of this month'))) }}" placeholder="dd/mm/yyyy" required>
                                    @error('billing_period_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>Chi tiết chi phí</h5>

                        <!-- Room Price -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_price">Tiền phòng <span class="text-danger">*</span></label>
                                    <input type="number" name="room_price" id="room_price" class="form-control cost-input @error('room_price') is-invalid @enderror" value="{{ old('room_price', 0) }}" min="0" step="1000" required>
                                    @error('room_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Tự động tính theo số ngày. Có thể chỉnh sửa thủ công.</small>
                                    <div id="roomPriceInfo" class="text-info small mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_cost">Tổng phí dịch vụ</label>
                                    <input type="number" name="service_cost" id="service_cost" class="form-control cost-input @error('service_cost') is-invalid @enderror" value="{{ old('service_cost', 0) }}" min="0" step="1000" readonly>
                                    @error('service_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Tự động tính từ dịch vụ đã chọn</small>
                                </div>
                            </div>
                        </div>

                        <!-- Service Selection -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Chọn dịch vụ</label>
                                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                        @forelse($services as $service)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input service-checkbox" 
                                                   name="selected_services[]" 
                                                   id="service_{{ $service->id }}" 
                                                   value="{{ $service->id }}"
                                                   data-price="{{ $service->price }}"
                                                   data-type="{{ $service->type }}"
                                                   {{ in_array($service->id, old('selected_services', [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="service_{{ $service->id }}">
                                                <strong>{{ $service->name }}</strong>
                                                <span class="text-muted"> - {{ number_format($service->price) }}đ</span>
                                                @if($service->type === 'monthly')
                                                <span class="badge badge-info badge-sm">/tháng</span>
                                                @elseif($service->type === 'one_time')
                                                <span class="badge badge-success badge-sm">một lần</span>
                                                @else
                                                <span class="badge badge-warning badge-sm">/lần</span>
                                                @endif
                                                @if($service->description)
                                                <br><small class="text-muted">{{ $service->description }}</small>
                                                @endif
                                            </label>
                                        </div>
                                        @empty
                                        <p class="text-muted mb-0">Chưa có dịch vụ nào</p>
                                        @endforelse
                                    </div>
                                    <small class="text-muted">Chọn các dịch vụ để tự động tính phí. Dịch vụ theo tháng sẽ được tính theo số ngày trong kỳ.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Electricity -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="electricity_usage">Điện tiêu thụ (kWh)</label>
                                    <input type="number" name="electricity_usage" id="electricity_usage" class="form-control @error('electricity_usage') is-invalid @enderror" value="{{ old('electricity_usage', 0) }}" min="0" step="0.1">
                                    @error('electricity_usage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Giá: 3,000đ/kWh</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="electricity_cost">Tiền điện</label>
                                    <input type="number" name="electricity_cost" id="electricity_cost" class="form-control cost-input @error('electricity_cost') is-invalid @enderror" value="{{ old('electricity_cost', 0) }}" min="0" step="1000" readonly>
                                    @error('electricity_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Tự động tính</small>
                                </div>
                            </div>
                        </div>

                        <!-- Water -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="water_usage">Nước tiêu thụ (m³)</label>
                                    <input type="number" name="water_usage" id="water_usage" class="form-control @error('water_usage') is-invalid @enderror" value="{{ old('water_usage', 0) }}" min="0" step="0.1">
                                    @error('water_usage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Giá: 15,000đ/m³</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="water_cost">Tiền nước</label>
                                    <input type="number" name="water_cost" id="water_cost" class="form-control cost-input @error('water_cost') is-invalid @enderror" value="{{ old('water_cost', 0) }}" min="0" step="1000" readonly>
                                    @error('water_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Tự động tính</small>
                                </div>
                            </div>
                        </div>

                        <!-- Other Costs -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="other_costs">Chi phí khác</label>
                                    <input type="number" name="other_costs" id="other_costs" class="form-control cost-input @error('other_costs') is-invalid @enderror" value="{{ old('other_costs', 0) }}" min="0" step="1000">
                                    @error('other_costs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-block"><strong>Tổng cộng</strong></label>
                                    <h3 class="text-success mb-0">
                                        <i class="ri-money-dollar-circle-line"></i>
                                        <span id="totalAmount">0</span> đ
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Ghi chú</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line"></i> Tạo hóa đơn
                            </button>
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                <i class="ri-close-line"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contractSelect = document.getElementById('contract_id');
    const contractInfo = document.getElementById('contractInfo');
    const studentName = document.getElementById('studentName');
    const roomInfo = document.getElementById('roomInfo');
    const monthlyRentSpan = document.getElementById('monthlyRent');
    const roomPriceInput = document.getElementById('room_price');
    
    // Declare costInputs first
    const costInputs = document.querySelectorAll('.cost-input');
    
    // Calculate total function
    function calculateTotal() {
        let total = 0;
        costInputs.forEach(function(input) {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        document.getElementById('totalAmount').textContent = total.toLocaleString('vi-VN');
    }
    
    // Calculate room price based on billing period
    function calculateRoomPrice() {
        const startDate = document.getElementById('billing_period_start').value;
        const endDate = document.getElementById('billing_period_end').value;
        const selected = contractSelect.options[contractSelect.selectedIndex];
        
        if (!contractSelect.value || !startDate || !endDate) {
            return;
        }
        
        // Parse dates (dd/mm/yyyy)
        const [startDay, startMonth, startYear] = startDate.split('/');
        const [endDay, endMonth, endYear] = endDate.split('/');
        const start = new Date(startYear, startMonth - 1, startDay);
        const end = new Date(endYear, endMonth - 1, endDay);
        
        // Calculate days
        const daysInPeriod = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
        const daysInMonth = new Date(startYear, startMonth, 0).getDate(); // Days in the month
        const monthlyRent = parseFloat(selected.dataset.monthlyRent) || 0;
        
        // Calculate price
        const calculatedPrice = (monthlyRent / daysInMonth) * daysInPeriod;
        roomPriceInput.value = Math.round(calculatedPrice);
        
        // Show info
        document.getElementById('roomPriceInfo').textContent = 
            `Tính toán: ${monthlyRent.toLocaleString('vi-VN')}đ/tháng ÷ ${daysInMonth} ngày × ${daysInPeriod} ngày = ${Math.round(calculatedPrice).toLocaleString('vi-VN')}đ`;
        
        calculateTotal();
    }

    // Calculate service cost from selected services
    function calculateServiceCost() {
        const startDate = document.getElementById('billing_period_start').value;
        const endDate = document.getElementById('billing_period_end').value;
        const serviceCheckboxes = document.querySelectorAll('.service-checkbox:checked');
        const serviceCostInput = document.getElementById('service_cost');
        
        let totalServiceCost = 0;
        
        if (startDate && endDate && serviceCheckboxes.length > 0) {
            // Parse dates
            const [startDay, startMonth, startYear] = startDate.split('/');
            const [endDay, endMonth, endYear] = endDate.split('/');
            const start = new Date(startYear, startMonth - 1, startDay);
            const end = new Date(endYear, endMonth - 1, endDay);
            
            const daysInPeriod = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
            const daysInMonth = new Date(startYear, startMonth, 0).getDate();
            
            serviceCheckboxes.forEach(function(checkbox) {
                const price = parseFloat(checkbox.dataset.price) || 0;
                const type = checkbox.dataset.type;
                
                if (type === 'monthly') {
                    // Monthly service: calculate based on days
                    const servicePrice = (price / daysInMonth) * daysInPeriod;
                    totalServiceCost += servicePrice;
                } else {
                    // One-time or per-use: use full price
                    totalServiceCost += price;
                }
            });
        } else if (serviceCheckboxes.length > 0) {
            // If dates not set, just sum up prices
            serviceCheckboxes.forEach(function(checkbox) {
                const price = parseFloat(checkbox.dataset.price) || 0;
                totalServiceCost += price;
            });
        }
        
        serviceCostInput.value = Math.round(totalServiceCost);
        calculateTotal();
    }

    // Show contract info when selected
    function updateContractInfo() {
        const selected = contractSelect.options[contractSelect.selectedIndex];
        if (contractSelect.value) {
            const rent = parseFloat(selected.dataset.monthlyRent) || 0;
            const student = selected.dataset.studentName || 'N/A';
            const room = selected.dataset.room || 'N/A';
            
            studentName.textContent = student;
            roomInfo.textContent = room;
            monthlyRentSpan.textContent = rent.toLocaleString('vi-VN') + ' đ';
            contractInfo.style.display = 'block';
            
            // Calculate room price based on billing period
            calculateRoomPrice();
        } else {
            contractInfo.style.display = 'none';
            roomPriceInput.value = 0;
            document.getElementById('roomPriceInfo').textContent = '';
            calculateTotal();
        }
    }
    
    contractSelect.addEventListener('change', updateContractInfo);
    
    // Trigger on page load if pre-selected
    if (contractSelect.value) {
        updateContractInfo();
    }
    
    // Recalculate room price when billing period changes
    document.getElementById('billing_period_start').addEventListener('change', function() {
        if (contractSelect.value) {
            calculateRoomPrice();
        }
        calculateServiceCost();
    });
    
    document.getElementById('billing_period_end').addEventListener('change', function() {
        if (contractSelect.value) {
            calculateRoomPrice();
        }
        calculateServiceCost();
    });
    
    // Recalculate service cost when services are selected/deselected
    document.querySelectorAll('.service-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', calculateServiceCost);
    });
    
    // Calculate electricity cost
    document.getElementById('electricity_usage').addEventListener('input', function() {
        const usage = parseFloat(this.value) || 0;
        const pricePerKwh = 3000; // 3,000 VND per kWh
        const cost = Math.round(usage * pricePerKwh);
        document.getElementById('electricity_cost').value = cost;
        calculateTotal();
    });
    
    // Calculate water cost
    document.getElementById('water_usage').addEventListener('input', function() {
        const usage = parseFloat(this.value) || 0;
        const pricePerM3 = 15000; // 15,000 VND per m³
        const cost = Math.round(usage * pricePerM3);
        document.getElementById('water_cost').value = cost;
        calculateTotal();
    });
    
    // Calculate total when any cost input changes
    costInputs.forEach(function(input) {
        input.addEventListener('input', calculateTotal);
    });
    
    // Initial calculation
    calculateTotal();
});
</script>
@endpush
