@include('components.till.invoice')
<table class="table app-table-hover table-bordered mb-0 text-left" id="stocks-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Total</th>
            <th>Tax</th>
            <th>Discount</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody id="transaction-tbody">
        @foreach ($data as $transaction)
            <tr onclick="getInvoice({{ $transaction['id'] }})"
            data-bs-toggle="modal"
            data-bs-target="#invoiceModal"
            id="invoice-button"
            style="cursor: pointer;"
            >
                <td>{{ $transaction['id'] }}</td>
                <td>{{ $transaction['user_id'] }}</td>
                <td>{{ $transaction['total'] }}</td>
                <td>{{ $transaction['tax'] }}</td>
                <td>{{ $transaction['discount'] }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d-m-Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="{{ asset('assets/js/transactions/invoice.js') }}" defer></script>
