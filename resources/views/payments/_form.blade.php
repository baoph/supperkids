@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Học sinh *</label>
        <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
            <option value="">-- Chọn học sinh --</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected(old('student_id', $payment->student_id ?? '') == $student->id)>{{ $student->name }}</option>
            @endforeach
        </select>
        @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Lớp học</label>
        <select name="class_id" class="form-select">
            <option value="">-- Không chọn --</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $payment->class_id ?? '') == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Mã hóa đơn *</label>
        <input type="text" name="invoice_code" value="{{ old('invoice_code', $payment->invoice_code ?? '') }}" class="form-control @error('invoice_code') is-invalid @enderror">
        @error('invoice_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Số tiền phải thu *</label>
        <input type="number" min="0" step="0.01" name="amount" value="{{ old('amount', $payment->amount ?? 0) }}" class="form-control @error('amount') is-invalid @enderror">
        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Số tiền đã thu *</label>
        <input type="number" min="0" step="0.01" name="paid_amount" value="{{ old('paid_amount', $payment->paid_amount ?? 0) }}" class="form-control @error('paid_amount') is-invalid @enderror">
        @error('paid_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Hạn thanh toán *</label>
        <input type="date" name="payment_due_date" value="{{ old('payment_due_date', isset($payment->payment_due_date) ? $payment->payment_due_date->format('Y-m-d') : '') }}" class="form-control @error('payment_due_date') is-invalid @enderror">
        @error('payment_due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Ngày thanh toán</label>
        <input type="date" name="payment_date" value="{{ old('payment_date', isset($payment->payment_date) ? $payment->payment_date->format('Y-m-d') : '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Trạng thái *</label>
        <select name="status" class="form-select">
            <option value="unpaid" @selected(old('status', $payment->status ?? 'unpaid') === 'unpaid')>Chưa thu</option>
            <option value="partial" @selected(old('status', $payment->status ?? '') === 'partial')>Thu một phần</option>
            <option value="paid" @selected(old('status', $payment->status ?? '') === 'paid')>Đã thu</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Ghi chú</label>
        <textarea name="note" rows="3" class="form-control">{{ old('note', $payment->note ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary">Lưu</button>
    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
