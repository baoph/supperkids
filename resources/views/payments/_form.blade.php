@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Học sinh <span class="text-danger">*</span></label>
        <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
            <option value="">— Chọn học sinh —</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected(old('student_id', $payment->student_id ?? '') == $student->id)>{{ $student->name }}</option>
            @endforeach
        </select>
        @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Lớp học</label>
        <select name="class_id" class="form-select">
            <option value="">— Không chọn —</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $payment->class_id ?? '') == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Số tiền phải thu <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" name="amount_display" value="{{ old('amount', $payment->amount ?? 0) }}" class="form-control currency-input @error('amount') is-invalid @enderror" placeholder="VD: 1.500.000" data-target="amount">
            <span class="input-group-text">đ</span>
            <input type="hidden" name="amount" value="{{ old('amount', $payment->amount ?? 0) }}">
            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Số tiền đã thu <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" name="paid_amount_display" value="{{ old('paid_amount', $payment->paid_amount ?? 0) }}" class="form-control currency-input @error('paid_amount') is-invalid @enderror" placeholder="VD: 1.500.000" data-target="paid_amount">
            <span class="input-group-text">đ</span>
            <input type="hidden" name="paid_amount" value="{{ old('paid_amount', $payment->paid_amount ?? 0) }}">
            @error('paid_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Hình thức thanh toán <span class="text-danger">*</span></label>
        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
            <option value="cash" @selected(old('payment_method', $payment->payment_method ?? 'cash') === 'cash')>Tiền mặt</option>
            <option value="transfer" @selected(old('payment_method', $payment->payment_method ?? '') === 'transfer')>Chuyển khoản</option>
        </select>
        @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Ngày thanh toán</label>
        <input type="date" name="payment_date" value="{{ old('payment_date', isset($payment->payment_date) ? $payment->payment_date->format('Y-m-d') : '') }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
        <select name="status" class="form-select">
            <option value="unpaid" @selected(old('status', $payment->status ?? 'unpaid') === 'unpaid')>Chưa thu</option>
            <option value="partial" @selected(old('status', $payment->status ?? '') === 'partial')>Thu một phần</option>
            <option value="paid" @selected(old('status', $payment->status ?? '') === 'paid')>Đã thu</option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Ghi chú</label>
        <textarea name="note" rows="3" class="form-control" placeholder="Nhập ghi chú (nếu có)...">{{ old('note', $payment->note ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary px-4">Lưu</button>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary px-4">Hủy</a>
</div>
