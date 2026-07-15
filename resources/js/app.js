import $ from 'jquery';
import * as bootstrap from 'bootstrap';
import DataTable from 'datatables.net-bs5';
import 'jquery-validation';

window.$ = window.jQuery = $;
window.bootstrap = bootstrap;
window.DataTable = DataTable;

const currency = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
});

const escapeHtml = (value) => $('<div>').text(value ?? '').html();

const formatNumber = (value) => new Intl.NumberFormat('en-US').format(Number(value || 0));

const showToast = (message, type = 'success') => {
    const title = type === 'success' ? 'Saved' : 'Something went wrong';
    const toast = $(
        `<div class="toast border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <span class="rounded-circle me-2 ${type === 'success' ? 'bg-success' : 'bg-danger'}" style="width:10px;height:10px"></span>
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">${escapeHtml(message)}</div>
        </div>`,
    );

    $('#toastContainer').append(toast);
    const instance = new bootstrap.Toast(toast[0], { delay: 4000 });
    toast.on('hidden.bs.toast', () => toast.remove());
    instance.show();
};

const ajaxErrorMessage = (xhr) => {
    if (xhr.responseJSON?.message) return xhr.responseJSON.message;
    return 'The request could not be completed. Please try again.';
};

const renderServerErrors = ($form, xhr) => {
    $form.find('.server-error').remove();
    $form.find('.is-invalid').removeClass('is-invalid');

    const errors = xhr.responseJSON?.errors || {};
    Object.entries(errors).forEach(([field, messages]) => {
        const $input = $form.find(`[name="${field}"]`).last();
        $input.addClass('is-invalid');
        $(`<div class="invalid-feedback server-error">${escapeHtml(messages[0])}</div>`).insertAfter($input);
    });
};

const baseTableOptions = {
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    autoWidth: false,
    language: {
        search: '',
        searchPlaceholder: 'Search records...',
        lengthMenu: 'Show _MENU_',
        info: 'Showing _START_ to _END_ of _TOTAL_',
        infoEmpty: 'No records available',
        zeroRecords: 'No matching records found',
    },
};

const initShell = () => {
    $('#sidebarToggle').on('click', () => $('body').toggleClass('sidebar-open'));
    $('#sidebarBackdrop, .sidebar-link').on('click', () => $('body').removeClass('sidebar-open'));

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            Accept: 'application/json',
        },
    });
};

const initCustomers = () => {
    const $table = $('#customersTable');
    if (!$table.length) return;

    const showUrl = $table.data('show-url');
    const table = new DataTable('#customersTable', {
        ...baseTableOptions,
        ajax: $table.data('source'),
        order: [[1, 'asc']],
        columns: [
            { data: 'CustomerID', className: 'fw-bold text-primary' },
            { data: 'CompanyName' },
            { data: 'ContactName', defaultContent: '—' },
            { data: 'ContactTitle', defaultContent: '—' },
            { data: 'Phone', defaultContent: '—' },
            { data: 'Country', defaultContent: '—' },
            {
                data: 'CustomerID',
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: (data, type) => type === 'display'
                    ? `<a class="btn btn-sm btn-outline-primary" href="${showUrl.replace('__ID__', encodeURIComponent(data))}">View</a>`
                    : data,
            },
        ],
        columnDefs: [{ targets: [1, 2, 3, 4, 5], render: (data, type) => type === 'display' ? escapeHtml(data || '—') : data }],
    });

    table.on('click', 'tbody tr', function (event) {
        if ($(event.target).closest('a, button').length) return;
        const row = table.row(this).data();
        if (row) window.location.href = showUrl.replace('__ID__', encodeURIComponent(row.CustomerID));
    });
};

const initProducts = () => {
    const $table = $('#productsTable');
    if (!$table.length) return;

    const showUrl = $table.data('show-url');
    const table = new DataTable('#productsTable', {
        ...baseTableOptions,
        pageLength: 25,
        ajax: $table.data('source'),
        order: [[0, 'asc']],
        columns: [
            { data: 'ProductID', className: 'fw-bold text-primary' },
            { data: 'ProductName' },
            { data: 'category.CategoryName', defaultContent: 'Uncategorized' },
            { data: 'supplier.CompanyName', defaultContent: 'Unknown supplier' },
            { data: 'QuantityPerUnit', defaultContent: '—' },
            { data: 'UnitPrice', className: 'text-end', render: (data, type) => type === 'display' ? currency.format(data) : data },
            { data: 'UnitsInStock', className: 'text-end' },
            { data: 'UnitsOnOrder', className: 'text-end' },
            {
                data: 'ProductID',
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: (data, type) => type === 'display'
                    ? `<a class="btn btn-sm btn-outline-primary" href="${showUrl.replace('__ID__', data)}">View</a>`
                    : data,
            },
        ],
        columnDefs: [{ targets: [1, 2, 3, 4], render: (data, type) => type === 'display' ? escapeHtml(data || '—') : data }],
    });

    table.on('click', 'tbody tr', function (event) {
        if ($(event.target).closest('a, button').length) return;
        const row = table.row(this).data();
        if (row) window.location.href = showUrl.replace('__ID__', row.ProductID);
    });
};

const setEditMode = ($form, editing) => {
    $form.find('[data-editable]').each(function () {
        const $field = $(this);
        if ($field.is('select') || $field.is(':checkbox')) {
            $field.prop('disabled', !editing);
        } else {
            $field.prop('readonly', !editing);
        }
    });

    $('#editButton').toggleClass('d-none', editing);
    $('#saveActions').toggleClass('d-none', !editing);
};

const initEditableForm = () => {
    const $form = $('.ajax-edit-form');
    if (!$form.length) return;

    $('#editButton').on('click', () => {
        setEditMode($form, true);
        $form.find('[data-editable]').first().trigger('focus');
    });

    $('#cancelEdit').on('click', () => window.location.reload());

    $form.validate({
        errorClass: 'invalid-feedback',
        errorElement: 'div',
        highlight: (element) => $(element).addClass('is-invalid'),
        unhighlight: (element) => $(element).removeClass('is-invalid'),
        errorPlacement: (error, element) => {
            if (element.is(':checkbox')) error.insertAfter(element.closest('.form-check'));
            else error.insertAfter(element);
        },
        submitHandler: (form) => {
            const $submit = $form.find('[type="submit"]');
            const original = $submit.html();
            $submit.prop('disabled', true).text('Saving...');

            $.ajax({
                url: $form.attr('action'),
                method: 'PUT',
                data: $form.serialize(),
            })
                .done((response) => {
                    showToast(response.message);
                    setEditMode($form, false);
                })
                .fail((xhr) => {
                    renderServerErrors($form, xhr);
                    showToast(ajaxErrorMessage(xhr), 'error');
                })
                .always(() => $submit.prop('disabled', false).html(original));
        },
    });
};

const initials = (name) => String(name || '')
    .split(/\s+/)
    .slice(0, 2)
    .map((part) => part.charAt(0))
    .join('')
    .toUpperCase();

const renderOrders = (orders) => {
    if (!orders.length) {
        return '<div class="empty-state"><strong>No orders found</strong><div class="small mt-1">This customer has no order history.</div></div>';
    }

    const rows = orders.map((order) => {
        const products = order.products.map((product) => `
            <tr>
                <td>${escapeHtml(product.product_name)}</td>
                <td class="text-end">${formatNumber(product.quantity)}</td>
                <td class="text-end">${currency.format(product.unit_price)}</td>
                <td class="text-end">${product.discount > 0 ? `${Math.round(product.discount * 100)}%` : '—'}</td>
                <td class="text-end fw-semibold">${currency.format(product.subtotal)}</td>
            </tr>`).join('');

        return `
            <tr>
                <td class="fw-bold text-primary">#${escapeHtml(order.order_id)}</td>
                <td>${escapeHtml(order.order_date || '—')}</td>
                <td>${escapeHtml(order.ship_name || '—')}</td>
                <td>${escapeHtml(order.ship_city || '—')}</td>
                <td class="text-end fw-bold">${currency.format(order.total_price)}</td>
            </tr>
            <tr class="order-products-row">
                <td colspan="5">
                    <div class="nested-table-wrap">
                        <div class="small fw-bold text-uppercase text-secondary mb-2">Products in order #${escapeHtml(order.order_id)}</div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead><tr><th>Product Name</th><th class="text-end">Quantity</th><th class="text-end">Unit Price</th><th class="text-end">Discount</th><th class="text-end">Subtotal</th></tr></thead>
                                <tbody>${products}</tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>`;
    }).join('');

    return `<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Order ID</th><th>Order Date</th><th>Ship Name</th><th>Ship City</th><th class="text-end">Total Price</th></tr></thead><tbody>${rows}</tbody></table></div>`;
};

const initOrders = () => {
    const $accordion = $('#customerOrdersAccordion');
    if (!$accordion.length) return;

    const urlTemplate = $accordion.data('url');

    $accordion.on('show.bs.collapse', '.accordion-collapse', function () {
        const $collapse = $(this);
        if ($collapse.data('loaded')) return;

        const customerId = $collapse.data('customer-id');
        const $body = $collapse.find('.accordion-body');
        $body.html('<div class="loading-block"><span class="spinner-border spinner-border-sm me-2"></span>Loading orders...</div>');

        $.getJSON(urlTemplate.replace('__ID__', encodeURIComponent(customerId)))
            .done((response) => {
                $body.html(renderOrders(response.orders));
                $collapse.data('loaded', true);
            })
            .fail((xhr) => {
                $body.html(`<div class="alert alert-danger mb-0">${escapeHtml(ajaxErrorMessage(xhr))}</div>`);
            });
    });

    $('#customerAccordionSearch').on('input', function () {
        const query = $(this).val().trim().toLowerCase();
        let matches = 0;

        $('.customer-accordion-item').each(function () {
            const visible = !query || $(this).data('search').includes(query);
            $(this).toggle(visible);
            if (visible) matches += 1;
        });

        $('#accordionNoResults').toggleClass('d-none', matches !== 0);
    });
};

const initSalesReport = () => {
    const $form = $('#salesReportForm');
    if (!$form.length) return;

    const table = new DataTable('#salesOrdersTable', {
        ...baseTableOptions,
        data: [],
        order: [[1, 'desc']],
        columns: [
            { data: 'order_id', render: (data, type) => type === 'display' ? `<span class="fw-bold text-primary">#${escapeHtml(data)}</span>` : data },
            { data: 'order_date' },
            { data: 'company_name' },
            { data: 'ship_country', defaultContent: '—' },
            { data: 'total_price', className: 'text-end fw-semibold', render: (data, type) => type === 'display' ? currency.format(data) : data },
        ],
        columnDefs: [{ targets: [1, 2, 3], render: (data, type) => type === 'display' ? escapeHtml(data || '—') : data }],
    });

    const loadReport = () => {
        const $submit = $form.find('[type="submit"]');
        $submit.prop('disabled', true).text('Generating...');

        $.getJSON($form.attr('action'), $form.serialize())
            .done((response) => {
                $('#totalSalesValue').text(currency.format(response.summary.total_sales));
                $('#orderCountValue').text(formatNumber(response.summary.order_count));
                $('#averageOrderValue').text(currency.format(response.summary.average_order));
                $('#salesRangeLabel').text(`${response.summary.start_date} – ${response.summary.end_date}`);
                table.clear().rows.add(response.data).draw();
            })
            .fail((xhr) => showToast(ajaxErrorMessage(xhr), 'error'))
            .always(() => $submit.prop('disabled', false).text('Generate report'));
    };

    $form.on('submit', (event) => {
        event.preventDefault();
        const start = $('#start_date').val();
        const end = $('#end_date').val();
        if (!start || !end || start > end) {
            showToast('Choose a valid date range. The end date must not be before the start date.', 'error');
            return;
        }
        loadReport();
    });

    loadReport();
};

const initInventoryReport = () => {
    if (!$('#inventoryTable').length) return;
    new DataTable('#inventoryTable', {
        ...baseTableOptions,
        pageLength: 25,
        order: [[0, 'asc']],
    });
};

$(function () {
    initShell();
    initCustomers();
    initProducts();
    initEditableForm();
    initOrders();
    initSalesReport();
    initInventoryReport();
});
